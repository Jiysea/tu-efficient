<?php

namespace App\Livewire\Focal\Implementations;

use App\Models\Batch;
use App\Models\Beneficiary;
use App\Models\Credential;
use App\Models\Implementation;
use App\Models\UserSetting;
use App\Services\JaccardSimilarity;
use App\Services\MoneyFormat;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
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

    # ------------------------------------

    public $beneficiariesFromDatabase;
    protected $jaccardSimilarity;
    public $includeBirthdate = false;
    public $similarityResults;
    public $isResults = false;
    public $isResolved = false;
    public $isPerfectDuplicate = false;
    public $ignorePossibleDuplicates = false;
    public $addReasonModal = false;

    # ----------------------------------------------

    #[Validate]
    public $first_name;
    #[Validate]
    public $middle_name;
    #[Validate]
    public $last_name;
    #[Validate]
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
    public $skills_training;
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

    # --------------------------------------------

    #[Validate]
    public $reason_image_file_path;
    #[Validate]
    public $image_description;

    # The validation rules, it runs every model update or calling validate()/validateOnly() methods
    public function rules()
    {
        return [
            'first_name' => [
                'required',
                # Check if the name has illegal characters
                function ($attribute, $value, $fail) {
                    $illegal = ".!@#$%^&*()+=-[]';,/{}|:<>?~\"`\\";

                    # throws validation errors whenever it detects illegal characters on names
                    if (strpbrk($value, $illegal)) {
                        $fail('Illegal characters are not allowed.');
                    }
                    # throws validation error whenever the name has a number
                    else if (preg_match('~[0-9]+~', $value)) {
                        $fail('Numbers on names are not allowed.');
                    }
                },
            ],
            'middle_name' => [
                # Check if the name has illegal characters
                function ($attribute, $value, $fail) {
                    $illegal = ".!@#$%^&*()+=-[]';,/{}|:<>?~\"`\\";

                    # throws validation errors whenever it detects illegal characters on names
                    if (strpbrk($value, $illegal)) {
                        $fail('Illegal characters are not allowed.');
                    }
                    # throws validation error whenever the name has a number
                    else if (preg_match('~[0-9]+~', $value)) {
                        $fail('Numbers on names are not allowed.');
                    }
                },
            ],
            'last_name' => [
                'required',
                # Check if the name has illegal characters
                function ($attribute, $value, $fail) {
                    $illegal = ".!@#$%^&*()+=-[]';,/{}|:<>?~\"`\\";

                    # throws validation errors whenever it detects illegal characters on names
                    if (strpbrk($value, $illegal)) {
                        $fail('Illegal characters are not allowed.');
                    }
                    # throws validation error whenever the name has a number
                    else if (preg_match('~[0-9]+~', $value)) {
                        $fail('Numbers on names are not allowed.');
                    }
                },
            ],
            'extension_name' => [
                # Check if the name has illegal characters
                function ($attribute, $value, $fail) {
                    $illegal = "!@#$%^&*()+=-[]';,/{}|:<>?~\"`\\";

                    # throws validation errors whenever it detects illegal characters on names
                    if (strpbrk($value, $illegal)) {
                        $fail('No illegal characters.');
                    }
                    # throws validation error whenever the name has a number
                    else if (preg_match('~[0-9]+~', $value)) {
                        $fail('No numbers.');
                    }
                },
            ],
            'birthdate' => 'required',
            'contact_num' => [
                'required',
                function ($attr, $value, $fail) {
                    if (!preg_match('~[0-9]+~', $value)) {
                        $fail('Value only accepts numbers.');
                    } else if ((strlen($value) !== 11)) {
                        $fail('Valid number should be 11 digits.');
                    }
                }
            ],
            'avg_monthly_income' => [
                'required_unless:occupation,null',
                function ($attr, $value, $fail) {
                    if ($this->avg_monthly_income) {
                        $money = new MoneyFormat();

                        if ($money->isNegative($value)) {
                            $fail('The value should be more than 1.');
                        }
                        if (!$money->isMaskInt($value)) {

                            $fail('The value should be a valid amount.');
                        }
                    }
                },
            ],
            'occupation' => [
                # hard-coded since `required_unless` is messy with `$money($input)` x-mask
                function ($attr, $value, $fail) {
                    if ($this->avg_monthly_income && !$value) {
                        $fail('This field is required.');
                    }
                },
            ],
            'image_file_path' => 'nullable|image|mimes:png,jpg,jpeg|max:5120',
            'id_number' => 'required',
            'spouse_first_name' => 'required_if:civil_status,Married',
            'spouse_last_name' => 'required_if:civil_status,Married',
            'reason_image_file_path' => 'exclude_if:isResults,false|required_if:isResults,true|image|mimes:png,jpg,jpeg|max:5120',
            'image_description' => 'exclude_if:isResults,false|required_if:isResults,true',
        ];
    }

    # The validation error messages
    public function messages()
    {
        return [
            'first_name.required' => 'This field is required.',
            'last_name.required' => 'This field is required.',
            'birthdate.required' => 'This field is required.',
            'contact_num.required' => 'This field is required.',
            'avg_monthly_income.required_unless' => 'This field is required.',
            'id_number.required' => 'This field is required.',
            'spouse_first_name.required_if' => 'This field is required.',
            'spouse_last_name.required_if' => 'This field is required.',

            'image_file_path.image' => ':attribute should be an image type.',
            'image_file_path.mimes' => ':attribute should be in PNG or JPG format.',

            'image_description.required_if' => 'Description must not be left blank.',

            'reason_image_file_path.required_if' => 'Case proof is required.',
            'reason_image_file_path.image' => 'Case proof must be an image type.',
            'reason_image_file_path.mimes' => 'It must be in PNG or JPG format.',
            'reason_image_file_path.max' => 'Image size must not exceed 5MB.',
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

    public function updatedCivilStatus()
    {
        if ($this->civil_status === 'Single') {
            $this->resetValidation('spouse_first_name');
            $this->resetValidation('spouse_last_name');
        }
    }

    public function updatedBeneficiaryType()
    {
        if ($this->beneficiary_type === 'Underemployed') {
            $this->reset('reason_image_file_path', 'image_description');
            $this->isResolved = false;
        }
    }

    // public function updatedOccupation()
    // {
    //     if (!$this->avg_monthly_income) {
    //         $this->resetValidation('occupation');
    //         $this->resetValidation('avg_monthly_income');
    //     }
    // }

    public function updatedAvgMonthlyIncome()
    {
        if (!$this->occupation) {
            $this->resetValidation('avg_monthly_income');
            $this->reset('occupation');
        }
    }

    # ----------------------------------------------------------------------------------------------
    # ADD REASON MODAL AREA

    public function saveReason()
    {
        $this->validateOnly('reason_image_file_path');
        $this->validateOnly('image_description');

        $this->isResolved = true;
        $this->addReasonModal = false;
    }

    # END OF ADD REASON MODAL AREA
    # ----------------------------------------------------------------------------------------------

    # a livewire action executes after clicking the `Add` button
    public function saveBeneficiary()
    {
        if (!$this->occupation && !$this->avg_monthly_income) {
            $this->reset('occupation');
            $this->resetValidation('avg_monthly_income');
        }

        $this->validate();
        $money = new MoneyFormat();
        $this->avg_monthly_income = $money->unmask($this->avg_monthly_income);

        $batch = Batch::find($this->batchId);
        $implementation = Implementation::find($batch->value('implementations_id'));

        $beneficiary = Beneficiary::create([
            'batches_id' => $this->batchId,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name ?? null,
            'last_name' => $this->last_name,
            'extension_name' => $this->extension_name ?? null,
            'birthdate' => $this->birthdate,
            'barangay_name' => $batch->value('barangay_name'),
            'contact_num' => $this->contact_num,
            'occupation' => $this->occupation ?? null,
            'avg_monthly_income' => $this->avg_monthly_income ?? null,
            'city_municipality' => $implementation->value('city_municipality'),
            'province' => $implementation->value('province'),
            'district' => $implementation->value('district'),
            'type_of_id' => $this->type_of_id,
            'id_number' => $this->id_number,
            'e_payment_acc_num' => $this->e_payment_acc_num ?? null,
            'beneficiary_type' => strtolower($this->beneficiary_type),
            'sex' => strtolower($this->sex),
            'civil_status' => strtolower($this->civil_status),
            'age' => $this->beneficiaryAge($this->birthdate),
            'dependent' => $this->dependent ?? null,
            'self_employment' => strtolower($this->self_employment),
            'skills_training' => $this->skills_training ?? null,
            'is_pwd' => strtolower($this->is_pwd),
            'is_senior_citizen' => intval($this->beneficiaryAge($this->birthdate)) > intval(config('settings.senior_age_threshold') ?? 60) ? 'yes' : 'no',
            'spouse_first_name' => $this->spouse_first_name,
            'spouse_middle_name' => $this->spouse_middle_name,
            'spouse_last_name' => $this->spouse_last_name,
            'spouse_extension_name' => $this->spouse_extension_name,
        ]);

        if ($this->image_file_path) {
            $file = $this->image_file_path->store(path: 'credentials');

            Credential::create([
                'beneficiaries_id' => $beneficiary->id,
                'image_description' => null,
                'image_file_path' => $file ?? null,
                'for_duplicates' => 'no',
            ]);
        }

        if ($this->isResults) {
            $file = $this->reason_image_file_path->store(path: 'credentials');
            Credential::create([
                'beneficiaries_id' => $beneficiary->id,
                'image_description' => $this->image_description,
                'image_file_path' => $file,
                'for_duplicates' => 'yes',
            ]);
        }

        $this->resetExcept(
            'batchId',
            'maxDate',
            'minDate',
        );

        $this->dispatch('add-beneficiaries');
    }

    public function nameCheck()
    {
        // $start = microtime(true); # FOR TESTING
        # the illegal characters for a name
        $illegal = ".!@#$%^&*()+=-[]';,/{}|:<>?~\"`\\";

        # clear out any previous similarity results
        $this->similarityResults = [];
        $this->isResults = false;
        $this->isPerfectDuplicate = false;

        # the filtering process won't go through if first_name, last_name, & birthdate are empty fields
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
            if (!strpbrk($filteredInputString, $illegal)) {

                # checks if there's an extension_name input
                if ($this->extension_name) {
                    $filteredInputString .= ' ' . $this->extension_name;
                }

                # checks the extension_name
                # if there are symbols except "."
                $illegal = "!@#$%^&*()+=-[]';,/{}|:<>?~\"`\\";
                if (!strpbrk($filteredInputString, $illegal)) {

                    # removes excess whitespaces between words
                    $filteredInputString = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $filteredInputString)));

                    # checks if the string has numbers
                    if (!preg_match('~[0-9]+~', $filteredInputString)) {
                        # gets the matching mode settings of the user
                        $settings = UserSetting::where('users_id', Auth::id())
                            ->pluck('value', 'key');
                        $matchingMode = $settings->get('extensive_matching', config('settings.extensive_matching'));
                        $duplicationThreshold = intval($settings->get('duplication_threshold', config('settings.duplication_threshold'))) / 100;

                        # removes excess whitespaces
                        $filteredInputString = preg_replace('/\s+/', ' ', $filteredInputString);

                        # only take beneficiaries from the start of the year until today
                        $startDate = now()->startOfYear();
                        $endDate = now();

                        $beneficiariesFromDatabase = null;

                        # basically a setting to enable extensive matching of duplication checks
                        # now, what is `Extensive Matching`?
                        # basically, it uses the first letter of every name fields
                        # and loops them to get the same names with the same first letters.
                        # 0 = exact matching; 1 = soft matching; 2 = extensive matching enabled
                        if (intval($matchingMode) === 2) # extensive matching
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
                                ->select([
                                    'beneficiaries.*',
                                    'implementations.project_num',
                                    'batches.batch_num'
                                ])
                                ->get();
                        } else if (intval($matchingMode) === 1) # soft matching
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
                                ->select([
                                    'beneficiaries.*',
                                    'implementations.project_num',
                                    'batches.batch_num'
                                ])
                                ->get();
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
                                ->select([
                                    'beneficiaries.*',
                                    'implementations.project_num',
                                    'batches.batch_num'
                                ])
                                ->get();
                        }

                        # add birthdate to da mix if the user allows it
                        if ($this->includeBirthdate) {
                            $filteredInputString .= ' ' . $this->birthdate;
                        }

                        # and also filter the beneficiaries from the database (NOT USING IT)
                        // $filteredBeneficariesFromDatabase = $this->beneficaryNamesToArrayStrings($beneficiariesFromDatabase, $this->middle_name, $this->extension_name);

                        # call the JaccardSimilarity class object instance
                        $this->jaccardSimilarity = new JaccardSimilarity();

                        # initialize possible duplicates variable
                        $possibleDuplicates = [];

                        # this is where it checks the similarities
                        foreach ($beneficiariesFromDatabase as $key => $beneficiary) {
                            # gets the full name of the beneficiary
                            $name = $this->beneficiaryName($beneficiary, $this->middle_name, $this->extension_name);

                            # gets the co-efficient/jaccard index of the 2 names (without birthdate by default)
                            $coEfficient = $this->jaccardSimilarity->calculateSimilarity($name, $filteredInputString);

                            # then check if it goes over the Threshold
                            if ($coEfficient > $duplicationThreshold) {
                                $this->isResults = true;

                                if (intval($coEfficient * 100) === 100 && !$this->isPerfectDuplicate) {
                                    $this->isPerfectDuplicate = true;
                                }

                                # if it does, then do some shit...
                                $possibleDuplicates[] = [
                                    'project_num' => $beneficiary->project_num,
                                    'batch_num' => $beneficiary->batch_num,
                                    'first_name' => $beneficiary->first_name,
                                    'middle_name' => $beneficiary->middle_name,
                                    'last_name' => $beneficiary->last_name,
                                    'extension_name' => $beneficiary->extension_name,
                                    'birthdate' => Carbon::parse($beneficiary->birthdate)->format('M d, Y'),
                                    'barangay_name' => $beneficiary->barangay_name,
                                    'contact_num' => $beneficiary->contact_num,
                                    'sex' => $beneficiary->sex,
                                    'age' => $beneficiary->age,
                                    'beneficiary_type' => $beneficiary->beneficiary_type,
                                    'type_of_id' => $beneficiary->type_of_id,
                                    'id_number' => $beneficiary->id_number,
                                    'is_pwd' => $beneficiary->is_pwd,
                                    'dependent' => $beneficiary->dependent,
                                    'coEfficient' => $coEfficient * 100,
                                ];
                            }
                        }

                        $this->similarityResults = $possibleDuplicates;

                        // dump($possibleDuplicates);

                        # test purposes
                        // $end = microtime(true); # FOR TESTING
                        // $count = 0;
                        // # the names and coefficient;
                        // $result = [];
                        // foreach ($jaccard as $output) {
                        //     if ($output['coEfficient'] > $duplicationThreshold) {
                        //         $count++;

                        //         $result[] = $output;
                        //     }
                        // }

                        // $jaccardResult = $count . ' / ' . sizeof($jaccard) . ' are possible duplicates, ' . $duplicationThreshold * 100 . '% threshold';
                        // $results = [
                        //     'user input' => $filteredInputString,
                        //     'beneficiaries indexed' => sizeof($beneficiariesFromDatabase),
                        //     'names being compared' => $filteredBeneficariesFromDatabase,
                        //     'jaccard' => $jaccardResult,
                        //     'time processed' => strval(number_format($end - $start, 4)) . 's',
                        //     'results' => $result
                        // ];
                        // dump($results);

                        # end of test
                    }
                }
            }
        }
    }

    protected function beneficiaryAge($birthdate)
    {
        return Carbon::parse($birthdate)->age;
    }

    # returns the full name of the beneficiary
    protected function beneficiaryName($name, $is_middle_name_present, $is_extension_name_present)
    {
        $returnedName = null;
        $returnedName = $name->first_name;

        # checks if middle_name is present on user input then adds it if true
        if ($is_middle_name_present && $name->middle_name) {
            $returnedName .= ' ' . $name->middle_name;
        }

        $returnedName .= ' ' . $name->last_name;

        # checks if extension_name is present on user input then adds it if true
        if ($is_extension_name_present && $name->extension_name) {
            $returnedName .= ' ' . $name->extension_name;
        }

        # add birthdate to da mix if the user allows it
        if ($this->includeBirthdate) {
            $formatBirthdate = Carbon::parse($name->birthdate)->format('Y-m-d');
            $returnedName .= ' ' . $formatBirthdate;
        }

        return $returnedName;
    }

    # compiles all beneficiaries (from database matching) into an array
    // protected function beneficaryNamesToArrayStrings($names, $is_middle_name_present, $is_extension_name_present)
    // {
    //     $arrayNames = [];
    //     foreach ($names as $name) {

    //         $filteredInputString = $name->first_name;

    //         if ($is_middle_name_present && $name->middle_name) {
    //             $filteredInputString .= ' ' . $name->middle_name;
    //         }

    //         $filteredInputString .= ' ' . $name->last_name;

    //         if ($is_extension_name_present && $name->extension_name) {
    //             $filteredInputString .= ' ' . $name->extension_name;
    //         }
    //         $formatBirthdate = Carbon::parse($name->birthdate)->format('Y-m-d');
    //         $filteredInputString .= ' ' . $formatBirthdate;

    //         $arrayNames[] = $filteredInputString;
    //     }
    //     return $arrayNames;
    // }

    public function render()
    {
        $this->maxDate = date('m-d-Y', strtotime(Carbon::now()->subYears(18)));
        $this->minDate = date('m-d-Y', strtotime(Carbon::now()->subYears(100)));

        return view('livewire.focal.implementations.add-beneficiaries-modal');
    }
}
