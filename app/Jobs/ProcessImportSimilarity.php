<?php

namespace App\Jobs;

use App\Models\Batch as Batches;
use App\Models\Beneficiary;
use App\Models\Credential;
use App\Models\Implementation;
use App\Models\UserSetting;
use App\Services\GenerateActivityLogs;
use App\Services\JaccardSimilarity;
use App\Services\MoneyFormat;
use DB;
use Illuminate\Support\Facades\Auth;
use Cache;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ProcessImportSimilarity implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $file_path;
    public $users_id;
    public $batches_id;
    public $duplicationThreshold;

    /**
     * Create a new job instance.
     */
    public function __construct($file_path, $users_id, $batches_id, $duplicationThreshold)
    {
        $this->file_path = $file_path;
        $this->users_id = $users_id;
        $this->batches_id = $batches_id;
        $this->duplicationThreshold = $duplicationThreshold;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        # Load the uploaded Excel file using PhpSpreadsheet
        $testAgainstFormats = [
            IOFactory::READER_CSV,
            IOFactory::READER_XLSX,
        ];
        $spreadsheet = null;

        try {
            $spreadsheet = IOFactory::load(storage_path('app/' . $this->file_path), 0, $testAgainstFormats);
        } catch (Exception $e) {
            $this->batch()->cancel();
            \Log::error('File processing error: ' . $e->getMessage());
            return;
        }

        $worksheet = $spreadsheet->getActiveSheet();
        $maxDataRow = $worksheet->getHighestDataRow();

        $successCounter = 0;
        $list = [];
        $beneficiary = [];
        $columnNames = [
            'A' => 'row',
            'B' => 'first_name',
            'C' => 'middle_name',
            'D' => 'last_name',
            'E' => 'extension_name',
            'F' => 'birthdate',
            'G' => 'barangay_name',
            'H' => 'city_municipality',
            'I' => 'province',
            'J' => 'district',
            'K' => 'type_of_id',
            'L' => 'id_number',
            'M' => 'contact_num',
            'N' => 'e_payment_acc_num',
            'O' => 'beneficiary_type',
            'P' => 'occupation',
            'Q' => 'sex',
            'R' => 'civil_status',
            'S' => 'age',
            'T' => 'avg_monthly_income',
            'U' => 'is_pwd',
            'V' => 'dependent',
            'W' => 'self_employment',
            'X' => 'skills_training',
            'Y' => 'spouse_first_name',
            'Z' => 'spouse_middle_name',
            'AA' => 'spouse_last_name',
            'AB' => 'spouse_extension_name'
        ];

        $batch = Batches::find($this->batches_id);
        $implementation = Implementation::find($batch->implementations_id);

        foreach ($worksheet->getRowIterator(13, $maxDataRow - 16) as $row) {
            if ($row->isEmpty(startColumn: 'A', endColumn: 'AB')) {
                continue;
            }

            foreach ($row->getCellIterator('A', 'AB') as $keyCell => $cell) {

                # Trims the cell value and removes extra whitespaces, then add it to the array
                $value = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $cell->getValue())));

                # The Sheet Row from the file assigned as a unique identifier for these temporary beneficiaries
                if ($keyCell === 'A') {
                    $value = $row->getRowIndex();
                }

                # Values that are empty, null, or `-` will be assigned as `null` to uniform the data
                elseif (in_array($keyCell, ['C', 'E', 'N', 'P', 'T', 'V', 'X', 'Y', 'Z', 'AA', 'AB'])) {
                    if (!isset($value) || empty($value) || $value === '-') {
                        $value = null;
                    }
                }

                # Interested in Self Employment && Person with Disability values that are empty or null will be assigned with `no` as default
                elseif (in_array($keyCell, ['U', 'W'])) {
                    if (in_array(strtolower($value), ['no', 'yes'])) {
                        $value = strtolower($value);
                    } else {
                        $value = 'no';
                    }
                }

                # Uniforming the Sex values
                elseif ($keyCell === 'Q') {
                    if (in_array(strtolower($value), ['male', 'female'])) {
                        $value = strtolower($value);
                    } elseif (strtolower($value) === 'm') {
                        $value = 'male';
                    } elseif (strtolower($value) === 'f') {
                        $value = 'female';
                    } else {
                        $value = null;
                    }
                }

                # Uniforming the Civil Status values
                elseif ($keyCell === 'R') {
                    if (in_array(strtolower($value), ['single', 'married', 'divorced', 'separated', 'widowed'])) {
                        $value = strtolower($value);
                    } elseif (strtolower($value) === 's') {
                        $value = 'single';
                    } elseif (strtolower($value) === 'm') {
                        $value = 'married';
                    } elseif (strtolower($value) === 'd') {
                        $value = 'divorced';
                    } elseif (strtolower($value) === 'sp') {
                        $value = 'separated';
                    } elseif (strtolower($value) === 'w') {
                        $value = 'widowed';
                    } else {
                        $value = null;
                    }
                }

                # The age value (will not be used)
                elseif ($keyCell === 'S') {
                    $value = $cell->getCalculatedValue();
                }

                # Type of Beneficiary value will be defaulted as `underemployed`
                elseif ($keyCell === 'O') {
                    if (!isset($value) || empty($value) || $value === '-') {
                        $value = 'underemployed';
                    } else {
                        $value = strtolower($value);
                    }
                }

                # Project Location Values assigned based on the selected batch in `Implementations` page
                elseif ($keyCell === 'G') {
                    $value = $batch->barangay_name;
                } elseif ($keyCell === 'H') {
                    $value = $implementation->city_municipality;
                } elseif ($keyCell === 'I') {
                    $value = $implementation->province;
                } elseif ($keyCell === 'J') {
                    $value = $implementation->district;
                }

                $beneficiary[$columnNames[$keyCell]] = $value;

            }

            # Also validate each row and flag a row that has some validation errors
            $beneficiary = self::validateAndReturn($beneficiary);

            # Then we start the similarity checker
            $beneficiary = self::checkSimilaritiesAndReturn($beneficiary, $this->duplicationThreshold);

            # Check the rows that were unique and insert to the database without errors && similarities
            $beneficiary = self::insertUniqueRows($beneficiary, $this->batches_id);

            if ($beneficiary['success'])
                $successCounter++;
            # Compile them all to the list
            $list[] = $beneficiary;
        }

        # Maybe try logging into Activity Logs
        $barangay_name = Batches::find($this->batches_id)->barangay_name;
        GenerateActivityLogs::set_import_beneficiaries_success_log($this->users_id, $barangay_name, $successCounter);

        # End Game
        cache(["similarity_" . $this->users_id => $list], now()->addMinutes(10));
    }

    protected static function validateAndReturn(array $beneficiary)
    {
        $list = $beneficiary;

        # First Name
        $errors = '';
        $errors .= self::required($beneficiary['first_name']);
        $errors .= self::illegal($beneficiary['first_name']);
        $errors .= self::numbers($beneficiary['first_name']);
        if (!isset($errors) || empty($errors))
            $errors = null;
        $list['errors']['first_name'] = $errors;

        # Middle Name
        $errors = '';
        $errors .= self::illegal($beneficiary['middle_name']);
        $errors .= self::numbers($beneficiary['middle_name']);
        if (!isset($errors) || empty($errors))
            $errors = null;
        $list['errors']['middle_name'] = $errors;

        # Last Name
        $errors = '';
        $errors .= self::required($beneficiary['last_name']);
        $errors .= self::illegal($beneficiary['last_name']);
        $errors .= self::numbers($beneficiary['last_name']);
        if (!isset($errors) || empty($errors))
            $errors = null;
        $list['errors']['last_name'] = $errors;

        # Extension Name
        $errors = '';
        $errors .= self::illegal($beneficiary['extension_name'], true);
        $errors .= self::numbers($beneficiary['extension_name']);
        if (!isset($errors) || empty($errors))
            $errors = null;
        $list['errors']['extension_name'] = $errors;

        # Birthdate
        $errors = '';
        $errors .= self::required($beneficiary['birthdate']);
        $errors .= self::valid_date($beneficiary['birthdate']);
        if (!isset($errors) || empty($errors))
            $errors = null;
        if (is_null($errors))
            $list['birthdate'] = Carbon::createFromFormat('Y/m/d', $beneficiary['birthdate'])->format('Y-m-d');
        $list['errors']['birthdate'] = $errors;

        # Contact Number
        $errors = '';
        $errors .= self::required($beneficiary['contact_num']);
        $errors .= self::phone_requirement($beneficiary['contact_num']);
        if (!isset($errors) || empty($errors))
            $errors = null;
        if (is_null($errors))
            $list['contact_num'] = self::type_of_contact_num($beneficiary['contact_num']);
        $list['errors']['contact_num'] = $errors;

        # Average Monthly Income
        $errors = '';
        if (isset($beneficiary['avg_monthly_income']) && empty(self::required_unless($beneficiary['avg_monthly_income'], $beneficiary['occupation'], null))) {
            $errors .= self::required_unless($beneficiary['avg_monthly_income'], $beneficiary['occupation'], null);
            $errors .= self::is_negative($beneficiary['avg_monthly_income']);
            $errors .= self::is_money_integer($beneficiary['avg_monthly_income']);

            if (empty($errors)) {
                $list['avg_monthly_income'] = MoneyFormat::unmask($beneficiary['avg_monthly_income']);
            }
        }
        if (!isset($errors) || empty($errors))
            $errors = null;
        $list['errors']['avg_monthly_income'] = $errors;

        # Occupation
        $errors = '';
        $errors .= self::required_unless($beneficiary['occupation'], $beneficiary['avg_monthly_income'], null);
        if (!isset($errors) || empty($errors))
            $errors = null;
        $list['errors']['occupation'] = $errors;

        # Type of ID
        $errors = '';
        $errors .= self::required($beneficiary['type_of_id']);
        if (!isset($errors) || empty($errors))
            $errors = null;
        $list['errors']['type_of_id'] = $errors;

        # ID Number
        $errors = '';
        $errors .= self::required($beneficiary['id_number']);
        if (!isset($errors) || empty($errors))
            $errors = null;
        $list['errors']['id_number'] = $errors;

        # Spouse First Name
        $errors = '';
        if ($beneficiary['civil_status'] === 'married') {
            $errors .= self::required_if($beneficiary['spouse_first_name'], $beneficiary['civil_status'], 'married');
            $errors .= self::illegal($beneficiary['spouse_first_name']);
            $errors .= self::numbers($beneficiary['spouse_first_name']);
        }
        if (!isset($errors) || empty($errors))
            $errors = null;
        $list['errors']['spouse_first_name'] = $errors;

        # Spouse Middle Name
        $errors = '';
        if ($beneficiary['civil_status'] === 'married') {
            $errors .= self::illegal($beneficiary['spouse_middle_name']);
            $errors .= self::numbers($beneficiary['spouse_middle_name']);
        }
        if (!isset($errors) || empty($errors))
            $errors = null;
        $list['errors']['spouse_middle_name'] = $errors;

        # Spouse Last Name
        $errors = '';
        if ($beneficiary['civil_status'] === 'married') {
            $errors .= self::required_if($beneficiary['spouse_last_name'], $beneficiary['civil_status'], 'married');
            $errors .= self::illegal($beneficiary['spouse_last_name']);
            $errors .= self::numbers($beneficiary['spouse_last_name']);
        }
        if (!isset($errors) || empty($errors))
            $errors = null;
        $list['errors']['spouse_last_name'] = $errors;

        # Spouse Extension Name
        $errors = '';
        if ($beneficiary['civil_status'] === 'married') {
            $errors .= self::illegal($beneficiary['spouse_extension_name'], true);
            $errors .= self::numbers($beneficiary['spouse_extension_name']);
        }
        if (!isset($errors) || empty($errors))
            $errors = null;
        $list['errors']['spouse_extension_name'] = $errors;

        return $list;
    }

    protected static function checkSimilaritiesAndReturn(array $beneficiary, mixed $duplicationThreshold)
    {
        $list = $beneficiary;

        if (!self::check_name_errors($beneficiary['errors'])) {

            $list['similarities'] = JaccardSimilarity::getResults($beneficiary['first_name'], $beneficiary['middle_name'], $beneficiary['last_name'], $beneficiary['extension_name'], $beneficiary['birthdate'], $duplicationThreshold);

        } else {
            $list['similarities'] = false;
        }

        return $list;
    }

    protected static function insertUniqueRows(array $beneficiary, $batches_id)
    {
        $list = $beneficiary;

        if (array_unique($beneficiary['errors']) === ["first_name" => null] && $beneficiary['similarities'] === null) {

            DB::transaction(function () use ($beneficiary, $batches_id) {
                $batch = Batches::find($batches_id);
                $implementation = Implementation::find($batch->implementations_id);

                $beneficiaryModel = Beneficiary::create([
                    'batches_id' => $batches_id,
                    'first_name' => $beneficiary['first_name'],
                    'middle_name' => $beneficiary['middle_name'],
                    'last_name' => $beneficiary['last_name'],
                    'extension_name' => $beneficiary['extension_name'],
                    'birthdate' => $beneficiary['birthdate'],
                    'barangay_name' => $batch->barangay_name,
                    'contact_num' => $beneficiary['contact_num'],
                    'occupation' => $beneficiary['occupation'],
                    'avg_monthly_income' => $beneficiary['avg_monthly_income'],
                    'city_municipality' => $implementation->city_municipality,
                    'province' => $implementation->province,
                    'district' => $implementation->district,
                    'type_of_id' => $beneficiary['type_of_id'],
                    'id_number' => $beneficiary['id_number'],
                    'e_payment_acc_num' => $beneficiary['e_payment_acc_num'],
                    'beneficiary_type' => $beneficiary['beneficiary_type'],
                    'sex' => $beneficiary['sex'],
                    'civil_status' => $beneficiary['civil_status'],
                    'age' => self::beneficiaryAge($beneficiary['birthdate']),
                    'dependent' => $beneficiary['dependent'],
                    'self_employment' => $beneficiary['self_employment'],
                    'skills_training' => $beneficiary['skills_training'],
                    'is_pwd' => $beneficiary['is_pwd'],
                    'is_senior_citizen' => intval(self::beneficiaryAge($beneficiary['birthdate'])) > intval(config('settings.senior_age_threshold') ?? 60) ? 'yes' : 'no',
                    'spouse_first_name' => $beneficiary['spouse_first_name'],
                    'spouse_middle_name' => $beneficiary['spouse_middle_name'],
                    'spouse_last_name' => $beneficiary['spouse_last_name'],
                    'spouse_extension_name' => $beneficiary['spouse_extension_name'],
                ]);

                Credential::create([
                    'beneficiaries_id' => $beneficiaryModel->id,
                    'image_description' => null,
                    'image_file_path' => null,
                    'for_duplicates' => 'no',
                ]);

            });
            $list['success'] = true;
        } else {
            $list['success'] = false;
        }

        # TESTING
        // $list['success'] = false;
        return $list;
    }

    # Some validation rules ------------------------------------------------------------------------

    # throws validation error if it's not set or it's empty
    static function required($data)
    {
        if (!isset($data) || empty($data)) {
            return 'This field is required.';
        }

        return '';
    }

    # throws validation errors whenever it detects illegal characters on names
    static function illegal($data, $ext = false)
    {
        if ($ext) {
            if (strpbrk($data, "!@#$%^&*()+=-[]';,/{}|:<>?~\"`\\")) {
                return 'Illegal characters are not allowed.';
            }
        } else {

            if (strpbrk($data, ".!@#$%^&*()+=-[]';,/{}|:<>?~\"`\\")) {
                return 'Illegal characters are not allowed.';
            }
        }

        return '';
    }

    # throws validation error whenever the name has a number
    static function numbers($data)
    {
        if (preg_match('~[0-9]+~', $data)) {
            return 'Numbers on names are not allowed.';
        }

        return '';
    }

    static function valid_date($data)
    {
        if (!Carbon::createFromFormat('Y/m/d', $data)) {
            return 'Invalid date format. Please use `YYYY/MM/DD`';
        }

        return '';
    }

    static function phone_requirement($data)
    {
        $errors = '';
        if (!preg_match('~[0-9]+~', $data)) {
            $errors .= 'This only accepts numbers.';
        }

        $check_09 = substr($data, 0, 2) === '09' ?? false;
        $check_639 = substr($data, 0, 4) === '+639' ?? false;

        if ($check_09) {
            if ((strlen($data) !== 11)) {
                $errors .= 'This number should be 11 digits.';
            }
        } elseif ($check_639) {
            if ((strlen($data) !== 13)) {
                $errors .= 'This number should be 12 digits.';
            }
        } else {
            $errors .= 'This number should start with 09 or +639.';
        }

        return $errors;
    }

    static function type_of_contact_num($data)
    {
        if (substr($data, 0, 2) === '09') {
            return '+63' . substr($data, 1);
        }

        return $data;
    }

    static function required_unless($data, $other_field, $value)
    {
        if ($other_field !== $value) {
            if (!isset($data) || empty($data)) {
                return 'This field is required.';
            }
        }

        return '';
    }

    static function required_if($data, $other_field, $value)
    {
        if ($other_field === $value) {
            if (!isset($data) || empty($data)) {
                return 'This field is required.';
            }
        }

        return '';
    }

    static function is_negative($data)
    {
        if (MoneyFormat::isNegative($data)) {
            return 'The value should be more than 1.';
        }

        return '';
    }

    static function is_money_integer($data)
    {
        if (!MoneyFormat::isMaskInt($data)) {
            return 'The value should be a valid amount.';
        }
        return '';
    }

    static function check_name_errors($errors)
    {
        foreach ($errors as $key => $error) {
            if (in_array($key, ['first_name', 'middle_name', 'last_name', 'extension_name', 'birthdate'])) {
                if (!is_null($error)) {
                    return true;
                }
            }
        }
        return false;
    }

    static function beneficiaryAge($birthdate)
    {
        return Carbon::parse($birthdate)->age;
    }

    # End of Some validation rules ------------------------------------------------------------------------
}
