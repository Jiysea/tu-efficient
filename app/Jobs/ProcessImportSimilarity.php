<?php

namespace App\Jobs;

use App\Models\Batch as Batches;
use App\Models\Beneficiary;
use App\Models\Credential;
use App\Models\Implementation;
use App\Models\User;
use App\Models\UserSetting;
use App\Services\Barangays;
use App\Services\Districts;
use App\Services\Essential;
use App\Services\LogIt;
use App\Services\JaccardSimilarity;
use App\Services\MoneyFormat;
use DateTime;
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
    public $maximumIncome;

    /**
     * Create a new job instance.
     */
    public function __construct($file_path, $users_id, $batches_id, $duplicationThreshold, $maximumIncome)
    {
        $this->file_path = $file_path;
        $this->users_id = $users_id;
        $this->batches_id = $batches_id;
        $this->duplicationThreshold = $duplicationThreshold;
        $this->maximumIncome = $maximumIncome;
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
        $minRow = 12;
        $maxRow = $worksheet->getHighestDataRow();

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
            'U' => 'dependent',
            'V' => 'self_employment',
            'W' => 'skills_training',
            'X' => 'spouse_first_name',
            'Y' => 'spouse_middle_name',
            'Z' => 'spouse_last_name',
            'AA' => 'spouse_extension_name',
            'AB' => 'is_pwd'
        ];

        $batch = Batches::find($this->batches_id);
        $implementation = Implementation::find($batch->implementations_id);

        foreach ($worksheet->getRowIterator($minRow, $maxRow) as $row) {
            if ($row->isEmpty(3, 'A', 'AB')) {
                continue;
            }

            foreach ($row->getCellIterator('A', 'AB') as $keyCell => $cell) {

                # Trims the cell value and removes extra whitespaces, then add it to the array
                $value = Essential::trimmer($cell->getValue());

                # The Sheet Row from the file assigned as a unique identifier for these temporary beneficiaries
                if ($keyCell === 'A') {
                    $value = $row->getRowIndex();
                }

                # first_name, middle_name, last_name, extension_name, and dependent 
                elseif (in_array($keyCell, ['B', 'C', 'D', 'E', 'U'])) {
                    if (!isset($value) || empty($value) || $value === '-' || strtolower($value) === 'none' || strtolower($value) === 'n/a') {
                        $value = null;
                    } else {
                        $value = mb_strtoupper($value, "UTF-8");
                    }
                }

                # birthdate
                elseif ($keyCell === 'F') {
                    $value = self::extract_dateTime($value);
                }

                # Values that are empty, null, or `-` will be assigned as `null` to uniform the data
                elseif (in_array($keyCell, ['N', 'W', 'X', 'Y', 'Z', 'AA'])) {
                    if (!isset($value) || empty($value) || $value === '-' || strtolower($value) === 'none' || strtolower($value) === 'n/a') {
                        $value = null;
                    }
                }

                # Interested in Self Employment && Person with Disability values that are empty or null will be assigned with `no` as default
                elseif (in_array($keyCell, ['V', 'AB'])) {
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
                    if ($cell->isFormula()) {
                        $value = $cell->getCalculatedValue();
                    } elseif (!isset($value) || empty($value) || $value === '-' || strtolower($value) === 'none' || strtolower($value) === 'n/a') {
                        $value = null;
                    }
                }

                # Type of Beneficiary value will be defaulted as `underemployed`
                elseif ($keyCell === 'O') {
                    if (!isset($value) || empty($value) || $value === '-' || strtolower($value) === 'none' || strtolower($value) === 'n/a') {
                        $value = 'underemployed';
                    } else {
                        $value = mb_strtolower($cell->getValue(), "UTF-8");
                    }
                }

                # Project Location Values assigned based on the selected batch in `Implementations` page
                elseif ($keyCell === 'G') {
                    $value = self::resolveBarangay($cell->getValue(), $batch);
                } elseif ($keyCell === 'H') {
                    $value = $implementation->city_municipality;
                } elseif ($keyCell === 'I') {
                    $value = $implementation->province;
                } elseif ($keyCell === 'J') {
                    $value = self::resolveDistrict($cell->getValue(), $batch);
                }

                $beneficiary[$columnNames[$keyCell]] = $value;

            }

            # Add the batches_id first to prevent the user from opening other batches while it is still fresh
            $list['batches_id'] = encrypt($this->batches_id);

            # Also validate each row and flag a row that has some validation errors
            $beneficiary = self::validateAndReturn($beneficiary, $this->maximumIncome, $this->batches_id);

            # Then we start the similarity checker
            $beneficiary = self::checkSimilaritiesAndReturn($beneficiary, $this->duplicationThreshold);

            # Check the rows that were unique and insert to the database without errors && similarities
            $beneficiary = self::insertUniqueRows($beneficiary, $this->batches_id);

            if ($beneficiary['success'])
                $successCounter++;
            # Compile them all to the list
            $list['beneficiaries'][] = $beneficiary;
        }

        if ($successCounter > 0 && in_array($list, ['success' => true])) {
            $user = User::find($this->users_id);
            LogIt::set_import_success($batch, $user, $successCounter);
        }

        # End Game
        $time = now()->addMinutes(10)->addSecond();
        cache(
            [
                "importing_" . $this->users_id => $list,
            ],
            $time
        );

        cache(
            [
                "importing_expiration_" . $this->users_id => ($time->format('Y-m-d H:i:s'))
            ],
            $time
        );
    }

    protected static function validateAndReturn(array $beneficiary, string $maximumIncome, int $batches_id)
    {
        $batch = Batches::find($batches_id);
        $implementation = Implementation::find($batch->implementations_id);
        $list = $beneficiary;

        # First Name
        $errors = [];
        $errors['required'] = self::required($beneficiary['first_name']);
        $errors['illegal'] = self::illegal($beneficiary['first_name']);
        $errors['is_string'] = self::numbers_on_name($beneficiary['first_name']);
        $list['errors']['first_name'] = $errors;

        # Middle Name
        $errors = [];
        $errors['illegal'] = self::illegal($beneficiary['middle_name']);
        $errors['is_string'] = self::numbers_on_name($beneficiary['middle_name']);
        $list['errors']['middle_name'] = $errors;

        # Last Name
        $errors = [];
        $errors['required'] = self::required($beneficiary['last_name']);
        $errors['illegal'] = self::illegal($beneficiary['last_name']);
        $errors['is_string'] = self::numbers_on_name($beneficiary['last_name']);
        $list['errors']['last_name'] = $errors;

        # Extension Name
        $errors = [];
        $errors['illegal'] = self::illegal($beneficiary['extension_name'], true);
        $errors['is_string'] = self::numbers_on_name($beneficiary['extension_name']);
        $list['errors']['extension_name'] = $errors;

        # Birthdate
        $errors = [];
        $errors['required'] = self::required($beneficiary['birthdate']);
        $errors['date'] = $beneficiary['birthdate'] === null ? 'Invalid date format. Please use YYYY/MM/DD.' : null;
        $list['errors']['birthdate'] = $errors;

        # Contact Number
        $errors = [];
        $errors['required'] = self::required($beneficiary['contact_num']);
        $errors['integer'] = self::is_integer($beneficiary['contact_num']);
        $errors['starts_with'] = self::starts_with($beneficiary['contact_num']);
        $errors['digits'] = self::digits($beneficiary['contact_num']);
        if (empty(array_filter($errors, fn($value) => !is_null(($value)))))
            $list['contact_num'] = self::filter_contact_num($beneficiary['contact_num']);
        $list['errors']['contact_num'] = $errors;

        # Sex
        $errors = [];
        $errors['required'] = self::required($beneficiary['sex']);
        $errors['invalid'] = self::checkInvalidValue($beneficiary['sex'], 'sex');
        $list['errors']['sex'] = $errors;

        # Civil Status
        $errors = [];
        $errors['required'] = self::required($beneficiary['civil_status']);
        $errors['invalid'] = self::checkInvalidValue($beneficiary['civil_status'], 'civil status');
        $list['errors']['civil_status'] = $errors;

        # Beneficiary Type
        $errors = [];
        $errors['required'] = self::required($beneficiary['beneficiary_type']);
        $errors['invalid'] = self::checkInvalidValue($beneficiary['beneficiary_type'], 'beneficiary type');
        $list['errors']['beneficiary_type'] = $errors;

        # District
        $errors = [];
        $errors['required'] = self::required($beneficiary['district']);
        $errors['invalid'] = self::checkValidDistrict($beneficiary['district'], $implementation);
        $list['errors']['district'] = $errors;

        # Barangay Name
        $errors = [];
        $errors['required'] = self::required($beneficiary['barangay_name']);
        $errors['invalid'] = self::checkValidBarangay($beneficiary['barangay_name'], $beneficiary['district'] ?? null, $implementation);
        $list['errors']['barangay_name'] = $errors;

        # Average Monthly Income
        $errors = [];
        $errors['required'] = self::required($beneficiary['avg_monthly_income']);
        $errors['negative'] = self::is_negative($beneficiary['avg_monthly_income']);
        $errors['integer'] = self::is_money_integer($beneficiary['avg_monthly_income']);
        $errors['limit'] = self::is_above_maximum_income($beneficiary['avg_monthly_income'], $maximumIncome);
        if (empty(array_filter($errors, fn($value) => !is_null(($value))))) {
            $list['avg_monthly_income'] = MoneyFormat::unmask($beneficiary['avg_monthly_income']);
        }
        $list['errors']['avg_monthly_income'] = $errors;

        # Occupation
        $errors = [];
        $errors['required'] = self::required($beneficiary['occupation']);
        $list['errors']['occupation'] = $errors;

        # Dependent
        $errors = [];
        $errors['required'] = self::required($beneficiary['dependent']);
        $errors['illegal'] = self::illegal($beneficiary['dependent'], false, true);
        $errors['is_string'] = self::numbers_on_name($beneficiary['dependent']);
        $list['errors']['dependent'] = $errors;

        # Type of ID
        $errors = [];
        $errors['required'] = self::required($beneficiary['type_of_id']);
        if (!isset($errors) || empty($errors))
            $errors = null;
        $list['errors']['type_of_id'] = $errors;

        # ID Number
        $errors = [];
        $errors['required'] = self::required($beneficiary['id_number']);
        $list['errors']['id_number'] = $errors;

        # For Spouse Information (if married)
        if ($beneficiary['civil_status'] === 'married') {

            # Spouse First Name
            $errors = [];
            $errors['required'] = self::required($beneficiary['spouse_first_name']);
            $errors['illegal'] = self::illegal($beneficiary['spouse_first_name']);
            $errors['is_string'] = self::numbers_on_name($beneficiary['spouse_first_name']);
            $list['errors']['spouse_first_name'] = $errors;

            # Spouse Middle Name
            $errors = [];
            $errors['illegal'] = self::illegal($beneficiary['spouse_middle_name']);
            $errors['is_string'] = self::numbers_on_name($beneficiary['spouse_middle_name']);
            $list['errors']['spouse_middle_name'] = $errors;

            # Spouse Last Name
            $errors = [];
            $errors['required'] = self::required($beneficiary['spouse_last_name']);
            $errors['illegal'] = self::illegal($beneficiary['spouse_last_name']);
            $errors['is_string'] = self::numbers_on_name($beneficiary['spouse_last_name']);
            $list['errors']['spouse_last_name'] = $errors;

            # Spouse Extension Name
            $errors = [];
            $errors['illegal'] = self::illegal($beneficiary['spouse_extension_name'], true);
            $errors['is_string'] = self::numbers_on_name($beneficiary['spouse_extension_name']);
            $list['errors']['spouse_extension_name'] = $errors;
        } else {
            # Spouse First Name
            $errors = [];
            $errors['required'] = null;
            $errors['illegal'] = null;
            $errors['is_string'] = null;
            $list['errors']['spouse_first_name'] = $errors;

            # Spouse Middle Name
            $errors = [];
            $errors['illegal'] = null;
            $errors['is_string'] = null;
            $list['errors']['spouse_middle_name'] = $errors;

            # Spouse Last Name
            $errors = [];
            $errors['required'] = null;
            $errors['illegal'] = null;
            $errors['is_string'] = null;
            $list['errors']['spouse_last_name'] = $errors;

            # Spouse Extension Name
            $errors = [];
            $errors['illegal'] = null;
            $errors['is_string'] = null;
            $list['errors']['spouse_extension_name'] = $errors;
        }

        return $list;
    }

    protected static function checkSimilaritiesAndReturn(array $beneficiary, mixed $duplicationThreshold)
    {
        $list = $beneficiary;

        if (!self::checkNameErrors($beneficiary['errors'])) {
            $list['similarities'] = JaccardSimilarity::getResults($beneficiary['first_name'], $beneficiary['middle_name'], $beneficiary['last_name'], $beneficiary['extension_name'], $duplicationThreshold);
        } else {
            $list['similarities'] = false;
        }

        return $list;
    }

    protected static function insertUniqueRows(array $beneficiary, $batches_id)
    {
        DB::transaction(function () use ($beneficiary, $batches_id) {
            try {
                $list = $beneficiary;
                $batch = Batches::find($batches_id);
                $implementation = Implementation::find($batch->implementations_id);
                $beneficiaryCount = self::checkBeneficiaryCount($batch);

                if ((!self::checkIfErrors($beneficiary['errors']) && $beneficiary['similarities'] === null) && $batch->slots_allocated > $beneficiaryCount) {

                    $beneficiaryModel = Beneficiary::create([
                        'batches_id' => $batches_id,
                        'first_name' => mb_strtoupper($beneficiary['first_name'], "UTF-8"),
                        'middle_name' => $beneficiary['middle_name'] ? mb_strtoupper($beneficiary['middle_name'], "UTF-8") : null,
                        'last_name' => mb_strtoupper($beneficiary['last_name'], "UTF-8"),
                        'extension_name' => $beneficiary['extension_name'] ? mb_strtoupper($beneficiary['extension_name'], "UTF-8") : null,
                        'birthdate' => $beneficiary['birthdate'],
                        'barangay_name' => $batch->is_sectoral ? ucwords(mb_strtolower($beneficiary['barangay_name'], 'UTF-8')) : $batch->barangay_name,
                        'contact_num' => $beneficiary['contact_num'],
                        'occupation' => ucwords($beneficiary['occupation']),
                        'avg_monthly_income' => $beneficiary['avg_monthly_income'],
                        'city_municipality' => $implementation->city_municipality,
                        'province' => $implementation->province,
                        'district' => $batch->is_sectoral ? ucwords(mb_strtolower($beneficiary['district'], 'UTF-8')) : $batch->district,
                        'type_of_id' => $beneficiary['type_of_id'],
                        'id_number' => $beneficiary['id_number'],
                        'e_payment_acc_num' => $beneficiary['e_payment_acc_num'],
                        'beneficiary_type' => mb_strtolower($beneficiary['beneficiary_type'], 'UTF-8'),
                        'sex' => mb_strtolower($beneficiary['sex'], 'UTF-8'),
                        'civil_status' => $beneficiary['civil_status'],
                        'age' => self::beneficiaryAge($beneficiary['birthdate']),
                        'dependent' => mb_strtoupper($beneficiary['dependent'], "UTF-8"),
                        'self_employment' => $beneficiary['self_employment'],
                        'skills_training' => $beneficiary['skills_training'],
                        'is_pwd' => $beneficiary['is_pwd'],
                        'is_senior_citizen' => intval(self::beneficiaryAge($beneficiary['birthdate'])) > intval(config('settings.senior_age_threshold') ?? 60) ? 'yes' : 'no',
                        'spouse_first_name' => $beneficiary['spouse_first_name'] ? mb_strtoupper($beneficiary['spouse_first_name'], "UTF-8") : null,
                        'spouse_middle_name' => $beneficiary['spouse_middle_name'] ? mb_strtoupper($beneficiary['spouse_middle_name'], "UTF-8") : null,
                        'spouse_last_name' => $beneficiary['spouse_last_name'] ? mb_strtoupper($beneficiary['spouse_last_name'], "UTF-8") : null,
                        'spouse_extension_name' => $beneficiary['spouse_extension_name'] ? mb_strtoupper($beneficiary['spouse_extension_name'], "UTF-8") : null,
                    ]);

                    Credential::create([
                        'beneficiaries_id' => $beneficiaryModel->id,
                        'image_description' => null,
                        'image_file_path' => null,
                        'for_duplicates' => 'no',
                    ]);


                    $list['success'] = true;
                } else {
                    $list['success'] = false;
                }
            }

            return $list;
        });

        return [];
    }

    # Some validation rules ------------------------------------------------------------------------

    static function checkInvalidValue($value, $field)
    {
        if ($value) {
            return null;
        }

        return 'Invalid ' . $field . ' value.';
    }

    static function checkValidDistrict($district, mixed $implementation)
    {
        foreach (Districts::getDistricts($implementation->city_municipality, $implementation->province) as $dist) {
            if ($dist === $district) {
                return null;
            }
        }

        return 'Invalid district.';
    }

    static function checkValidBarangay($barangay_name, $district, mixed $implementation)
    {
        if ($district) {

            foreach (Barangays::getBarangays($implementation->city_municipality, $district) as $bar) {
                if (mb_strtolower($bar, "UTF-8") === mb_strtolower($barangay_name, "UTF-8")) {
                    return null;
                }
            }

            return 'The barangay does not belong to the district.';

        } else {
            foreach (Barangays::getBarangays($implementation->city_municipality, '1st District') as $bar) {
                if (mb_strtolower($bar, "UTF-8") === mb_strtolower($barangay_name, "UTF-8")) {
                    return null;
                }
            }

            foreach (Barangays::getBarangays($implementation->city_municipality, '2nd District') as $bar) {
                if (mb_strtolower($bar, "UTF-8") === mb_strtolower($barangay_name, "UTF-8")) {
                    return null;
                }
            }

            foreach (Barangays::getBarangays($implementation->city_municipality, '3rd District') as $bar) {
                if (mb_strtolower($bar, "UTF-8") === mb_strtolower($barangay_name, "UTF-8")) {
                    return null;
                }
            }

            return 'The barangay does not exist. Please check the values from \'List (do not modify)\' worksheet.';
        }
    }

    static function resolveDistrict($district, $batch)
    {
        if ($batch->is_sectoral) {
            if ($district) {
                if (
                    mb_strtolower($district, "UTF-8") === '1st' ||
                    mb_strtolower($district, "UTF-8") === '1st district' ||
                    mb_strtolower($district, "UTF-8") === '1'
                ) {
                    return '1st District';
                } elseif (
                    mb_strtolower($district, "UTF-8") === '2nd' ||
                    mb_strtolower($district, "UTF-8") === '2nd district' ||
                    mb_strtolower($district, "UTF-8") === '2'
                ) {
                    return '2nd District';
                } elseif (
                    mb_strtolower($district, "UTF-8") === '3rd' ||
                    mb_strtolower($district, "UTF-8") === '3rd district' ||
                    mb_strtolower($district, "UTF-8") === '3'
                ) {
                    return '3rd District';
                }
            }
        } elseif (!$batch->is_sectoral) {
            return $batch->district;
        }
        return null;
    }

    static function resolveBarangay($barangay_name, $batch)
    {
        if ($batch->is_sectoral) {
            if ($barangay_name) {
                $short = mb_strtolower($barangay_name, "UTF-8");
                $barangay_name = ucwords($short);
                if (
                    in_array(mb_strtoupper($barangay_name, 'UTF-8'), [
                        '1-A',
                        '2-A',
                        '3-A',
                        '4-A',
                        '5-A',
                        '6-A',
                        '7-A',
                        '8-A',
                        '9-A',
                        '10-A',
                        '11-B',
                        '12-B',
                        '13-B',
                        '14-B',
                        '15-B',
                        '16-B',
                        '17-B',
                        '18-B',
                        '19-B',
                        '20-B',
                        '21-C',
                        '22-C',
                        '23-C',
                        '24-C',
                        '25-C',
                        '26-C',
                        '27-C',
                        '28-C',
                        '29-C',
                        '30-C',
                        '31-D',
                        '32-D',
                        '33-D',
                        '34-D',
                        '35-D',
                        '36-D',
                        '37-D',
                        '38-D',
                        '39-D',
                        '40-D',
                    ])
                ) {
                    return mb_strtoupper($barangay_name, 'UTF-8');
                }
                return $barangay_name;
            }
        } elseif (!$batch->is_sectoral) {
            return $batch->barangay_name;
        }

        return null;
    }

    # throws validation error if it's not set or it's empty
    static function required($data)
    {
        if (!isset($data) || empty($data)) {
            return 'This field is required.';
        }

        return null;
    }

    # throws validation errors whenever it detects illegal characters on names
    static function illegal($data, $ext = false, $full = false)
    {
        if ($ext) {
            if (strpbrk($data, "!@#$%^&*()+=-[]';,/{}|:<>?~\"`\\")) {
                return 'Illegal characters are not allowed.';
            }
        } elseif ($full) {

            if (strpbrk($data, "!@#$%^&*()+=[]';,/{}|:<>?~\"`\\")) {
                return 'Illegal characters are not allowed.';
            }

        } else {
            if (strpbrk($data, ".!@#$%^&*()+=[]';,/{}|:<>?~\"`\\")) {
                return 'Illegal characters are not allowed.';
            }
        }

        return null;
    }

    # throws validation error whenever the name has a number
    static function numbers_on_name($data)
    {
        if (preg_match('~[0-9]+~', $data)) {
            return 'Numbers on names are not allowed.';
        }

        return null;
    }

    static function is_integer($data)
    {
        if ($data) {
            if (substr($data, 0, 2) === '09') {
                if (Essential::hasNumber(substr($data, 0, 2))) {
                    return null;
                }
            } elseif (substr($data, 0, 4) === '+639') {
                if (Essential::hasNumber(substr($data, 0, 4))) {
                    return null;
                }
            }

            return 'Value only accepts numbers.';
        }

        return 'Invalid phone number format.';
    }

    static function digits($data)
    {
        if ($data) {
            if (substr($data, 0, 2) === '09') {
                if ((strlen($data) !== 11)) {
                    return 'This number should be 11 digits.';
                }

                return null;

            } elseif (substr($data, 0, 4) === '+639') {
                if ((strlen($data) !== 13)) {
                    return 'This number should be 12 digits with \'+\' symbol.';
                }

                return null;

            }
        }

        return 'Invalid phone number format.';
    }

    static function starts_with($data)
    {
        if ($data) {

            if (substr((string) $data, 0, 2) === '09') {
                return null;
            } elseif (substr((string) $data, 0, 4) === '+639') {
                return null;
            }

            return 'Valid number should start with \'09\' or \'+639\'';
        }

        return 'Invalid phone number format.';
    }

    static function is_above_maximum_income($data, $maximumIncome)
    {
        if ($data) {

            if (!ctype_digit((string) $data)) {
                return 'Invalid money format.';
            } elseif (MoneyFormat::unmask($data) > ($maximumIncome ? intval($maximumIncome) : intval(config('settings.maximum_income')))) {
                return 'The value should be more than 1.';
            }

            return null;
        }

        return 'Invalid money format.';
    }

    static function required_if($data, $other_field, $value)
    {
        if ($other_field === $value) {
            if (!isset($data) || empty($data)) {
                return 'This field is required.';
            }
        }

        return null;
    }

    static function is_negative($data)
    {
        if ($data) {
            if (!ctype_digit((string) $data)) {
                return 'Invalid money format.';
            } elseif (MoneyFormat::isNegative($data)) {
                return 'The value should be more than 1.';
            }
            return null;
        }

        return 'Invalid money format.';
    }

    static function is_money_integer($data)
    {
        if ($data) {
            if (!ctype_digit((string) $data)) {
                return 'Invalid money format.';
            } elseif (!MoneyFormat::isMaskInt($data)) {
                return 'The value should be a valid amount.';
            }

            return null;
        }

        return 'Invalid money format.';
    }

    # End of Some validation rules ------------------------------------------------------------------------

    static function checkNameErrors($errors, $keys = ['first_name', 'middle_name', 'last_name', 'extension_name', 'birthdate'])
    {
        foreach ($keys as $key) {
            foreach ($errors[$key] as $value) {
                if (!is_null($value)) {
                    return true; # Found a non-null value
                }
            }
        }
        return false;
    }

    static function checkIfErrors($errors)
    {
        $keys = array_keys($errors);

        foreach ($keys as $key) {
            foreach ($errors[$key] as $value) {
                if (!is_null($value)) {
                    return true; # Found a non-null value
                }
            }

        }
        return false;
    }

    static function checkBeneficiaryCount(mixed $batch)
    {
        $count = Beneficiary::where('batches_id', $batch->id)->count();
        return $count;
    }

    static function beneficiaryAge($birthdate)
    {
        return Carbon::parse($birthdate)->age;
    }

    static function filter_contact_num($data)
    {
        if (substr($data, 0, 2) === '09') {
            return '+63' . substr($data, 1);
        } elseif (substr($data, 0, 4) === '+639') {
            return $data;
        } else {
            return false;
        }
    }

    static function extract_dateTime($message, $returnFormat = 'Y-m-d')
    {
        $date_patterns = [
            '/\b\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])\b/' => 'Y-m-d',
            '/\b\d{4}-(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])\b/' => 'Y-d-m',
            '/\b(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])-\d{4}\b/' => 'm-d-Y',
            '/\b(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])-\d{4}\b/' => 'd-m-Y',
            '/\b(0[1-9]|1[0-2])-\d{4}-(0[1-9]|[1-2][0-9]|3[0-1])\b/' => 'm-Y-d',
            '/\b(0[1-9]|[1-2][0-9]|3[0-1])-\d{4}-(0[1-9]|1[0-2])\b/' => 'd-Y-m',

            '/\b\d{4}\/(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])\b/' => 'Y/m/d',
            '/\b\d{4}\/(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\b/' => 'Y/d/m',
            '/\b(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])\/\d{4}\b/' => 'm/d/Y',
            '/\b(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/\d{4}\b/' => 'd/m/Y',
            '/\b(0[1-9]|1[0-2])\/\d{4}\/(0[1-9]|[1-2][0-9]|3[0-1])\b/' => 'm/Y/d',
            '/\b(0[1-9]|[1-2][0-9]|3[0-1])\/\d{4}\/(0[1-9]|1[0-2])\b/' => 'd/Y/m',

            '/\b\d{4}\.(0[1-9]|1[0-2])\.(0[1-9]|[1-2][0-9]|3[0-1])\b/' => 'Y.m.d',
            '/\b\d{4}\.(0[1-9]|[1-2][0-9]|3[0-1])\.(0[1-9]|1[0-2])\b/' => 'Y.d.m',
            '/\b(0[1-9]|1[0-2])\.(0[1-9]|[1-2][0-9]|3[0-1])\.\d{4}\b/' => 'm.d.Y',
            '/\b(0[1-9]|[1-2][0-9]|3[0-1])\.(0[1-9]|1[0-2])\.\d{4}\b/' => 'd.m.Y',
            '/\b(0[1-9]|1[0-2])\.\d{4}\.(0[1-9]|[1-2][0-9]|3[0-1])\b/' => 'm.Y.d',
            '/\b(0[1-9]|[1-2][0-9]|3[0-1])\.\d{4}\.(0[1-9]|1[0-2])\b/' => 'd.Y.m',
        ];

        $dateTimeStr = null;
        $dateTimeFormat = null;

        foreach ($date_patterns as $date_pattern => $format) {
            if (preg_match($date_pattern, $message, $matches)) {
                $dateTimeFormat = $format;
                $dateTimeStr = $matches[0];
                break;
            }
        }

        if ($dateTimeStr == null || $dateTimeFormat == null) {
            return null;
        }
        $d = Carbon::createFromFormat($dateTimeFormat, $dateTimeStr);
        return $d->format($returnFormat);
    }
}
