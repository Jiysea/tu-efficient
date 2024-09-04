<?php

namespace App\Livewire\Focal\Implementations;

use App\Models\Beneficiary;
use App\Services\JaccardSimilarity;
use Carbon\Carbon;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class AddBeneficiariesModal extends Component
{
    use WithFileUploads;

    #[Reactive]
    #[Locked]
    public $batchId;
    #[Locked]
    public $maxDate;
    #[Locked]
    public $minDate;
    public $beneficiariesFromDatabase;

    # ------------------------------------

    protected $jaccardSimilarity;

    # ------------------------------------

    #[Validate]
    public $first_name;
    public $middle_name;
    #[Validate]
    public $last_name;
    public $extension_name;
    #[Validate]
    public $birthdate;
    public $sex = 'Male';
    #[Validate]
    public $contact_num;
    #[Validate]
    public $occupation;
    public $civil_status = 'Single';
    #[Validate]
    public $avg_monthly_income;
    #[Validate]
    public $dependent;
    public $e_payment_acc_num;
    public $self_employment = 'No';
    public $beneficiary_type = 'Underemployed';
    public $skills_training = 'No';
    public $is_pwd = 'No';
    #[Validate]
    public $image_file_path;
    public $type_of_id = 'e-Card / UMID';
    #[Validate]
    public $id_number;
    #[Validate]
    public $spouse_first_name;
    public $spouse_middle_name;
    #[Validate]
    public $spouse_last_name;
    public $spouse_extension_name;

    # Runs real-time depending on wire:model suffix
    public function rules()
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'birthdate' => 'required',
            'contact_num' => 'required',
            'avg_monthly_income' => 'required_unless:occupation,null',
            'image_file_path' => 'nullable|image|mimes:png,jpg,jpeg|max:5120',
            'id_number' => 'required',
            'spouse_first_name' => 'required_if:civil_status,Married',
            'spouse_last_name' => 'required_if:civil_status,Married',
        ];
    }

    # The validation error messages
    public function messages()
    {
        return [
            'first_name.required' => ':attribute should not be empty.',
            'last_name.required' => ':attribute should not be empty.',
            'birthdate.required' => ':attribute should not be empty.',
            'contact_num.required' => ':attribute should not be empty.',
            'avg_monthly_income.required_unless' => 'This field is required.',
            'id_number.required' => ':attribute should not be empty.',
            'spouse_first_name.required_if' => 'This field is required.',
            'spouse_last_name.required_if' => 'This field is required.',

            'image_file_path.image' => ':attribute should be an image type.',
            'image_file_path.mimes' => ':attribute should be in PNG or JPG format.',
        ];
    }

    # Validation attribute names for human readability purpose
    # for example: The project_num should not be empty.
    # instead of that: The project number should not be empty.
    public function validationAttributes()
    {
        return [
            'first_name' => 'First name',
            'last_name' => 'Last name',
            'birthdate' => 'Birth date',
            'contact_num' => 'Contact number',
            'id_number' => 'ID Number',
            'image_file_path' => 'File',
        ];
    }

    #[On('birthdate-change')]
    public function setBirthdate($value)
    {
        if ($value) {
            $choosenDate = Carbon::createFromFormat('m-d-Y', $value)->format('Y-m-d');

            $this->birthdate = $choosenDate;

            if ($this->type_of_id === 'Senior Citizen ID' && strtotime($this->birthdate) > strtotime(Carbon::now()->subYears(60))) {
                $this->type_of_id = 'e-Card / UMID';
            }
            $this->nameCheck();
        } else {
            $this->birthdate = null;
        }
    }

    # a livewire action executes after clicking the `Create Project` button
    public function saveBeneficiary()
    {
        $this->validate();

        # other attributes not in this form:
        # city_municipality, province, district, age, is_senior_citizen, is_pwd,

        // Beneficiary::create([
        //     'users_id' => Auth()->id(),
        //     'project_num' => $this->project_num,
        //     'project_title' => $this->project_title,
        //     'purpose' => $this->purpose,
        //     'district' => $this->district,
        //     'province' => $this->province,
        //     'city_municipality' => $this->city_municipality,
        //     'budget_amount' => $this->budget_amount,
        //     'total_slots' => $this->total_slots,
        //     'days_of_work' => $this->days_of_work
        // ]);

        // $this->reset();
        // $this->dispatch('add-beneficiaries');

    }

    public function nameCheck()
    {
        $start = microtime(true);
        if ($this->first_name && $this->last_name && $this->birthdate) {

            # double checking again before handing over to the algorithm
            # basically we filter the user input along the way
            $filteredInputString = $this->first_name;

            if ($this->middle_name) {
                $filteredInputString .= ' ' . $this->middle_name;
            }

            $filteredInputString .= ' ' . $this->last_name;

            # checks the first_name, middle_name, and last_name
            # if there are symbols
            $illegal = ".!@#$%^&*()+=-[]';,/{}|:<>?~\"`\\";
            if (!strpbrk($filteredInputString, $illegal)) {

                # checks if there's an extension_name input
                if ($this->extension_name) {
                    $filteredInputString .= ' ' . $this->extension_name;
                }

                # checks the extension_name
                # if there are symbols except "."
                $illegal = "!@#$%^&*()+=-[]';,/{}|:<>?~\"`\\";
                if (!strpbrk($filteredInputString, $illegal)) {

                    # checks if the string has numbers
                    if (!preg_match('~[0-9]+~', $filteredInputString)) {

                        # removes excess whitespaces
                        $filteredInputString = preg_replace('/\s+/', ' ', $filteredInputString);
                        // dump($filteredInputString);

                        # preprocess / indexing the table
                        $startDate = now()->startOfYear();
                        $endDate = now();

                        $beneficiariesFromDatabase = null;

                        # basically a setting to enable extensive matching of duplication checks
                        # now, what is `Extensive Matching`?
                        # basically, it uses the first letter of every name fields
                        # and loops them to get the same names with the same first letters.
                        # 0 = exact matching; 1 = soft matching; 2 = extensive matching enabled
                        if (intval(config('settings.extensive_matching')) === 2) # extensive matching
                        {
                            # separate each word from all the name fields
                            $namesToLetters = array_map(fn($word) => $word[0], explode(' ', $filteredInputString));

                            # get the beneficiaries with indexing and filtering
                            # really complicated but basically here's how it works:

                            #   - Before the query, we tried to get the substrings of the firstname, middlename, and lastname.
                            #   - Since there's a possibility that a person can have more than 1 firstname, I map the first
                            #           letter of each word of their first name.
                            #   - Also, getting the dates from the start of this year (ex. January 1st of this year)
                            #           then the date of today to scope the data since you could only apply to TUPAD
                            #           once a year.
                            #   - Then we start the query with the INNER JOINS (beneficiaries->batches->implementations)
                            #   - A where clause for the dates in implementations table to query its creation date.
                            #   - Then we loop all the firstname substrings for the LIKE query.
                            #   - Next, we have the lastname where clause followed by an OR where clause for the middlename
                            #           since it's optional.
                            #   - Then finally select the first_name, middle_name, last_name, extension_name,
                            #           birthdate, and barangay_name and get it as an array.

                            $beneficiariesFromDatabase = Beneficiary::join('batches', 'beneficiaries.batches_id', '=', 'batches.id')
                                ->join('implementations', 'batches.implementations_id', '=', 'implementations.id')
                                ->whereBetween('implementations.created_at', [$startDate, $endDate])
                                ->where(function ($query) use ($namesToLetters) {
                                    foreach ($namesToLetters as $letter) {
                                        $query->orWhere('beneficiaries.first_name', 'LIKE', '%' . $letter . '%');
                                        $query->orWhere('beneficiaries.middle_name', 'LIKE', $letter . '%');
                                        $query->orWhere('beneficiaries.last_name', 'LIKE', $letter . '%');
                                    }
                                })
                                ->select(['beneficiaries.first_name', 'beneficiaries.middle_name', 'beneficiaries.last_name', 'beneficiaries.extension_name', 'beneficiaries.birthdate', 'beneficiaries.barangay_name', 'beneficiaries.contact_num'])
                                ->get()
                                ->toArray();
                        } else if (intval(config('settings.extensive_matching')) === 1) # soft matching
                        {
                            # separate each word from all the name fields
                            # and get the first letter of each word
                            $namesToLetters = array_map(fn($word) => $word[0], explode(' ', $filteredInputString));

                            $beneficiariesFromDatabase = Beneficiary::join('batches', 'beneficiaries.batches_id', '=', 'batches.id')
                                ->join('implementations', 'batches.implementations_id', '=', 'implementations.id')
                                ->whereBetween('implementations.created_at', [$startDate, $endDate])
                                ->where(function ($query) use ($namesToLetters) {
                                    foreach ($namesToLetters as $letter) {
                                        $query->orWhere('beneficiaries.first_name', 'LIKE', $letter . '%');
                                    }
                                })
                                ->where(function ($q) use ($namesToLetters) {
                                    $q->when($this->middle_name, function ($q) use ($namesToLetters) {
                                        foreach ($namesToLetters as $letter) {
                                            $q->orWhere('beneficiaries.middle_name', 'LIKE', $letter . '%');
                                        }
                                    });
                                    foreach ($namesToLetters as $letter) {
                                        $q->orWhere('beneficiaries.last_name', 'LIKE', $letter . '%');
                                    }
                                })
                                ->select(['beneficiaries.first_name', 'beneficiaries.middle_name', 'beneficiaries.last_name', 'beneficiaries.extension_name', 'beneficiaries.birthdate', 'beneficiaries.barangay_name', 'beneficiaries.contact_num'])
                                ->get()
                                ->toArray();
                        } else # 0 or direct matching (not recommended)
                        {
                            # direct matching basically works similarly to soft matching
                            # except that it queries by word instead of by letter.
                            # Although it's more performant than the other matching mechanics,
                            # we wouldn't recommend using this if you need better accuracy.
                            $namesToWords = explode(' ', $filteredInputString);

                            $beneficiariesFromDatabase = Beneficiary::join('batches', 'beneficiaries.batches_id', '=', 'batches.id')
                                ->join('implementations', 'batches.implementations_id', '=', 'implementations.id')
                                ->whereBetween('implementations.created_at', [$startDate, $endDate])
                                ->where(function ($query) use ($namesToWords) {
                                    foreach ($namesToWords as $word) {
                                        $query->orWhere('beneficiaries.first_name', 'LIKE', '%' . $word . '%');
                                    }
                                })
                                ->where(function ($q) use ($namesToWords) {
                                    foreach ($namesToWords as $word) {
                                        $q->when($this->middle_name, function ($query) use ($word) {
                                            return $query->orWhere('beneficiaries.middle_name', 'LIKE', $word . '%');
                                        });
                                    }
                                    foreach ($namesToWords as $word) {
                                        $q->orWhere('beneficiaries.last_name', 'LIKE', $word . '%');
                                    }
                                })
                                ->select(['beneficiaries.first_name', 'beneficiaries.middle_name', 'beneficiaries.last_name', 'beneficiaries.extension_name', 'beneficiaries.birthdate', 'beneficiaries.barangay_name', 'beneficiaries.contact_num'])
                                ->get()
                                ->toArray();
                        }

                        # add birthdate to da mix
                        $filteredInputString .= ' ' . $this->birthdate;

                        # and also filter the beneficiaries from the database
                        $filteredBeneficariesFromDatabase = $this->beneficaryNamesToArrayStrings($beneficiariesFromDatabase, $this->middle_name, $this->extension_name);

                        # algorithm bullshet
                        $jaccard = [];
                        $threshold = config('settings.duplication_threshold') / 100;
                        $this->jaccardSimilarity = new JaccardSimilarity();
                        foreach ($filteredBeneficariesFromDatabase as $key => $beneficiaryFromDatabase) {
                            $coEfficient = $this->jaccardSimilarity->calculateSimilarity($beneficiaryFromDatabase, $filteredInputString);
                            if ($coEfficient > $threshold) {
                                $jaccard[] = [
                                    'id' => $beneficiaryFromDatabase['id'],
                                    'coEfficient' => $coEfficient,

                                ];
                            }
                        }

                        $end = microtime(true);

                        # test purposes
                        // $count = 0;
                        // foreach ($jaccard as $output) {
                        //     if ($output > $threshold) {
                        //         $count++;
                        //     }
                        // }

                        // $jaccardResult = $count . ' / ' . sizeof($jaccard) . ' are possible duplicates, ' . $threshold * 100 . '% threshold';
                        // $results = [
                        //     'user input' => $filteredInputString,
                        //     'beneficiaries indexed' => sizeof($beneficiariesFromDatabase),
                        //     'names being compared' => $filteredBeneficariesFromDatabase,
                        //     'jaccard' => $jaccardResult,
                        //     'time processed' => strval(number_format($end - $start, 4)) . 's',
                        //     'results' => $jaccard
                        // ];
                        // dump($results);
                        # end of test
                    }
                }
            }
        }
    }

    protected function beneficaryNamesToArrayStrings($names, $is_middle_name_present, $is_extension_name_present)
    {
        $arrayNames = [];
        foreach ($names as $name) {

            $filteredInputString = $name['first_name'];

            if ($is_middle_name_present && $name['middle_name']) {
                $filteredInputString .= ' ' . $name['middle_name'];
            }

            $filteredInputString .= ' ' . $name['last_name'];

            if ($is_extension_name_present && $name['extension_name']) {
                $filteredInputString .= ' ' . $name['extension_name'];
            }
            $formatBirthdate = Carbon::parse($name['birthdate'])->format('Y-m-d');
            $filteredInputString .= ' ' . $formatBirthdate;

            $arrayNames[] = $filteredInputString;
        }
        return $arrayNames;
    }

    public function render()
    {
        $this->maxDate = date('m-d-Y', strtotime(Carbon::now()->subYears(18)));
        $this->minDate = date('m-d-Y', strtotime(Carbon::now()->subYears(100)));

        return view('livewire.focal.implementations.add-beneficiaries-modal');
    }
}
