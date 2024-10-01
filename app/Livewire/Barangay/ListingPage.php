<?php

namespace App\Livewire\Barangay;

use App\Models\Batch;
use App\Models\Beneficiary;
use App\Models\Code;
use App\Models\Credential;
use App\Models\Implementation;
use App\Models\UserSetting;
use App\Services\JaccardSimilarity;
use App\Services\MoneyFormat;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Session;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Storage;

#[Layout('layouts.app')]
class ListingPage extends Component
{
    use WithFileUploads;

    #[Locked]
    #[Session('code')]
    public $accessCode; # the access code, the pinnacle of truth, the root of all evil
    public $addBeneficiariesModal = false;
    public $beneficiaryDeleteModal = false;
    public $submitBatchModal = false;
    public $searchBeneficiaries;
    public $selectedBeneficiaryRow = -1;
    protected $defaultPages = 15;
    #[Locked]
    public $beneficiaries_on_page;
    #[Locked]
    public $beneficiaryId;

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
                # Check if the name has illegal characters except "."
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
                    } else if (substr($value, 0, 2) !== '09') {
                        $fail("Valid number should start with '09'.");
                    }
                }
            ],
            'avg_monthly_income' => [
                'required_unless:occupation,null',
                function ($attr, $value, $fail) {
                    if ($this->avg_monthly_income) {

                        if (MoneyFormat::isNegative($value)) {
                            $fail('The value should be more than 1.');
                        }
                        if (!MoneyFormat::isMaskInt($value)) {

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
            'image_file_path' => 'required|image|mimes:png,jpg,jpeg|max:5120',
            'id_number' => 'required',
            'spouse_first_name' => 'required_if:civil_status,Married',
            'spouse_last_name' => 'required_if:civil_status,Married',
            'reason_image_file_path' => 'exclude_if:isPerfectDuplicate,false|required_if:isPerfectDuplicate,true|image|mimes:png,jpg,jpeg|max:5120',
            'image_description' => 'exclude_if:isPerfectDuplicate,false|required_if:isPerfectDuplicate,true',
            'confirming' => [
                'required',
                function ($attr, $value, $fail) {
                    if ($value !== 'CONFIRM') {
                        $fail('Incorrect.');
                    }
                },
            ],
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
            'confirming.required' => 'This field is required.',

            'image_file_path.required' => 'The proof of identity is required.',
            'image_file_path.image' => 'The proof should be an image type.',
            'image_file_path.mimes' => 'The proof should be in PNG or JPG format.',
            'image_file_path.max' => 'Image size must not exceed 5MB.',

            'image_description.required_if' => 'Description must not be left blank.',

            'reason_image_file_path.required_if' => 'Case proof is required.',
            'reason_image_file_path.image' => 'Case proof must be an image type.',
            'reason_image_file_path.mimes' => 'It must be in PNG or JPG format.',
            'reason_image_file_path.max' => 'Image size must not exceed 5MB.',
        ];
    }

    # Validation attribute names for human readability purpose
    public function validationAttributes()
    {
        return [
            'first_name' => 'First name',
            'last_name' => 'Last name',
            'birthdate' => 'Birth date',
            'contact_num' => 'Contact number',
            'id_number' => 'ID Number',
        ];
    }

    #[Computed]
    public function implementation()
    {
        $implementation = Implementation::find($this->batch->implementations_id)
            ->first();

        return $implementation;
    }
    #[Computed]
    public function batch()
    {
        # do a query of the batch using the access code
        $batch = Batch::join('codes', 'codes.batches_id', '=', 'batches.id')
            ->where('codes.access_code', decrypt($this->accessCode))
            ->select('batches.*')
            ->first();

        return $batch;
    }

    #[Computed]
    public function beneficiaries()
    {
        # do a query of all the beneficiaries listed on a particular batch
        $beneficiaries = Beneficiary::where('batches_id', $this->batch->id)
            ->when($this->searchBeneficiaries, function ($q) {
                # Check if the search field starts with '#' and filter by contact number
                if (str_contains($this->searchBeneficiaries, '#')) {
                    $searchValue = trim(str_replace('#', '', $this->searchBeneficiaries));

                    if (strpos($searchValue, '0') === 0) {
                        $searchValue = substr($searchValue, 1);
                    }
                    $q->where('beneficiaries.contact_num', 'LIKE', '%' . $searchValue . '%');
                }

                # Or search for male beneficiaries
                else if (str_contains(strtolower(trim($this->searchBeneficiaries)), 'male')) {
                    $searchValue = strtolower(trim($this->searchBeneficiaries));
                    $q->where('beneficiaries.sex', $searchValue);
                }

                # Or search for female beneficiaries
                else if (str_contains(strtolower(trim($this->searchBeneficiaries)), 'female')) {
                    $searchValue = strtolower(trim($this->searchBeneficiaries));
                    $q->where('beneficiaries.sex', $searchValue);
                }

                # Otherwise, search by first, middle, or last name
                else {

                    $q->where(function ($query) {
                        $query->where('beneficiaries.first_name', 'LIKE', '%' . $this->searchBeneficiaries . '%')
                            ->orWhere('beneficiaries.middle_name', 'LIKE', '%' . $this->searchBeneficiaries . '%')
                            ->orWhere('beneficiaries.last_name', 'LIKE', '%' . $this->searchBeneficiaries . '%');
                    });
                }
            })

            ->take($this->beneficiaries_on_page)
            ->get();

        return $beneficiaries;
    }

    #[Computed]
    public function proofImages()
    {
        $credentials = Credential::where('beneficiaries_id', $this->beneficiaries[$this->selectedBeneficiaryRow]->id)
            ->get();

        $images = collect();
        if ($credentials) {

            foreach ($credentials as $key => $credential) {

                if ($credential->for_duplicates === 'yes') {
                    $images->put('specialCase', $credential->image_file_path);
                } else {
                    $images->put('identity', $credential->image_file_path);
                }

            }
        }

        return $images;
    }

    #[Computed]
    public function batchInformation()
    {

        $batchInformation = [
            'barangay' => $this->batch->barangay_name,
            'location' => $this->implementation->province . ', ' . $this->implementation->city_municipality . ', ' . $this->implementation->district
        ];

        return $batchInformation;
    }

    public function openDeleteModal($encryptedId)
    {
        $this->beneficiaryId = $encryptedId;
        $this->beneficiaryDeleteModal = true;
    }

    public function closeDeleteModal()
    {
        $this->beneficiaryDeleteModal = false;
        $this->beneficiaryId = null;
    }

    #[Computed]
    public function full_name($key)
    {
        $full_name = $this->beneficiaries[$key]->first_name;

        if ($this->beneficiaries[$key]->middle_name) {
            $full_name .= ' ' . $this->beneficiaries[$key]->middle_name;
        }

        $full_name .= ' ' . $this->beneficiaries[$key]->last_name;

        if ($this->beneficiaries[$key]->extension_name) {
            $full_name .= ' ' . $this->beneficiaries[$key]->extension_name;
        }

        return $full_name;
    }

    #[Computed]
    public function full_name_by_id()
    {
        $beneficiary = Beneficiary::find(decrypt($this->beneficiaryId));
        $full_name = $beneficiary->first_name;

        if ($beneficiary->middle_name) {
            $full_name .= ' ' . $beneficiary->middle_name;
        }

        $full_name .= ' ' . $beneficiary->last_name;

        if ($beneficiary->extension_name) {
            $full_name .= ' ' . $beneficiary->extension_name;
        }

        return $full_name;
    }
    public function selectBeneficiaryRow($key)
    {
        if ($this->selectedBeneficiaryRow === $key) {
            $this->selectedBeneficiaryRow = -1;
        } else {
            $this->selectedBeneficiaryRow = $key;
        }
    }

    public function loadMoreBeneficiaries()
    {
        $this->beneficiaries_on_page += $this->defaultPages;
        $this->dispatch('init-reload')->self();
    }

    # Should be called for every execution/action to check if the code is accessible
    public function authorizeBeforeExecuting()
    {
        # do a query for that code (if it's set)
        $access = Code::where('access_code', decrypt($this->accessCode))
            ->where('is_accessible', 'yes')
            ->first();

        # check if it's null
        if (!$access) {

            # then redirect to their intended page...
            $this->redirectIntended();
        }
    }

    # START OF DELETE BENEFICIARY MODAL ----------------------------------------

    #[Validate]
    public $confirming;

    public function deleteBeneficiary()
    {
        $this->validateOnly('confirming');
        $beneficiary = Beneficiary::find(decrypt($this->beneficiaryId));
        $this->authorizeBeforeExecuting();
        $beneficiary->delete();

        $this->beneficiaryDeleteModal = false;
    }

    # END OF DELETE BENEFICIARY MODAL ------------------------------------------

    # START OF SUBMIT BATCH MODAL ----------------------------------------

    public function submitBatch()
    {
        $batch = Batch::find($this->batch->id);

        $this->authorizeBeforeExecuting();

        $batch->submission_status = 'submitted';
        Code::where('access_code', decrypt($this->accessCode))
            ->where('is_accessible', 'yes')
            ->update([
                'is_accessible' => 'no'
            ]);
        $batch->save();

        $this->submitBatchModal = false;
        $this->redirectRoute('login');
    }

    # END OF SUBMIT BATCH MODAL ------------------------------------------

    # start of add-beneficiaries-modal -----------------------------------------

    #[Locked]
    public $maxDate;
    #[Locked]
    public $minDate;
    public bool $isResolved = false;
    public bool $isPerfectDuplicate = false;
    public bool $addReasonModal = false;
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
    #[Validate]
    public $reason_image_file_path;
    #[Validate]
    public $image_description;

    // #[On('birthdate-change')]
    // public function setBirthdate($value)
    // {
    //     if ($value) {
    //         $choosenDate = Carbon::createFromFormat('m-d-Y', $value)->format('Y-m-d');

    //         $this->birthdate = $choosenDate;

    //         # `Senior Citizen ID` will show up as one of the options if it's determined that the beneficiary is more than 60 years old
    //         $seniorAge = intval(config('settings.senior_age_threshold', 60));
    //         if ($this->type_of_id === 'Senior Citizen ID' && strtotime($this->birthdate) > strtotime(Carbon::now()->subYears($seniorAge))) {
    //             $this->type_of_id = 'e-Card / UMID';
    //         }
    //         $this->nameCheck();
    //     } else {
    //         $this->birthdate = null;
    //     }

    //     $this->validateOnly('birthdate');
    // }

    # the action that runs the algorithm where it checks each prefetched names and calculate the similarities
    public function nameCheck()
    {
        # clear out any previous similarity results / initialize
        $this->isPerfectDuplicate = false;

        # the filtering process won't go through if first_name, last_name, & birthdate are empty fields
        if ($this->first_name && $this->last_name && $this->birthdate) {

            # double checking again before handing over to the algorithm
            # basically we filter the user input along the way
            $this->first_name = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $this->first_name)));
            $filteredInputString = $this->first_name;
            $this->validateOnly('first_name');

            if ($this->middle_name) {
                $this->middle_name = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $this->middle_name)));
                $filteredInputString .= ' ' . $this->middle_name;
                $this->validateOnly('middle_name');
            }

            $this->last_name = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $this->last_name)));
            $filteredInputString .= ' ' . $this->last_name;
            $this->validateOnly('last_name');

            # checks if there's an extension_name input
            if ($this->extension_name) {
                $this->extension_name = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $this->extension_name)));
                $filteredInputString .= ' ' . $this->extension_name;
                $this->validateOnly('extension_name');
            }

            # removes excess whitespaces between words
            $filteredInputString = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $filteredInputString)));

            # gets the matching mode settings
            $settings = UserSetting::where('users_id', Auth::id())
                ->pluck('value', 'key');
            $matchingMode = $settings->get('extensive_matching', config('settings.extensive_matching'));
            $duplicationThreshold = intval($settings->get('duplication_threshold', config('settings.duplication_threshold'))) / 100;

            # fetch all the potential duplicating names from the database
            $beneficiariesFromDatabase = $this->prefetchNames($filteredInputString, $matchingMode);

            # initialize the algorithm instance
            $algorithm = new JaccardSimilarity();

            # initialize possible duplicates variable
            $possibleDuplicates = [];

            # this is where it checks the similarities
            foreach ($beneficiariesFromDatabase as $key => $beneficiary) {
                # gets the full name of the beneficiary
                $name = $this->beneficiaryName($beneficiary);

                # gets the co-efficient/jaccard index of the 2 names (without birthdate by default)
                $coEfficient = $algorithm->calculateSimilarity($name, $filteredInputString);

                # check if it's a perfect duplicate
                if (intval($coEfficient * 100) === 100 && !$this->isPerfectDuplicate) {
                    $this->isPerfectDuplicate = true;
                }
            }
        }
    }

    public function saveBeneficiary()
    {
        # if both occupation and avg. monthly income fields are left empty, then they get reset
        if (!$this->occupation && !$this->avg_monthly_income) {
            $this->reset('occupation');
            $this->reset('avg_monthly_income');
        }

        $this->validate();

        if ($this->avg_monthly_income) {
            $this->avg_monthly_income = MoneyFormat::unmask($this->avg_monthly_income);
        }

        $this->authorizeBeforeExecuting();
        // $this->avg_monthly_income = $this->avg_monthly_income ? MoneyFormat::unmask($this->avg_monthly_income) : null;

        // $this->birthdate = Carbon::parse($this->birthdate)->format('Y-m-d');
        // $this->contact_num = '+63' . substr($this->contact_num, 1);

        $beneficiary = Beneficiary::create([
            'batches_id' => $this->batch->id,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name ?? null,
            'last_name' => $this->last_name,
            'extension_name' => $this->extension_name ?? null,
            'birthdate' => $this->birthdate,
            'barangay_name' => $this->batch->value('barangay_name'),
            'contact_num' => $this->contact_num,
            'occupation' => $this->occupation ?? null,
            'avg_monthly_income' => $this->avg_monthly_income ?? null,
            'city_municipality' => $this->implementation->city_municipality,
            'province' => $this->implementation->province,
            'district' => $this->implementation->district,
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

        Credential::create([
            'beneficiaries_id' => $beneficiary->id,
            'image_description' => null,
            'image_file_path' => $this->image_file_path->store(path: 'credentials'),
            'for_duplicates' => 'no',
        ]);

        if ($this->isPerfectDuplicate) {
            Credential::create([
                'beneficiaries_id' => $beneficiary->id,
                'image_description' => $this->image_description,
                'image_file_path' => $this->reason_image_file_path->store(path: 'credentials'),
                'for_duplicates' => 'yes',
            ]);
        }

        $this->resetExcept(
            'accessCode',
            'addBeneficiariesModal',
            'searchBeneficiaries',
            'maxDate',
            'minDate',
        );

        unset($this->beneficiaries);
    }

    # ADD REASON MODAL AREA
    public function saveReason()
    {
        $this->validateOnly('reason_image_file_path');
        $this->validateOnly('image_description');

        $this->isResolved = true;
        $this->addReasonModal = false;
    }
    # END OF ADD REASON MODAL AREA

    /**
     * A simple `age` parser utilizing Carbon API
     * @param string $birthdate The birthdate of the beneficiary in string format
     * @return int Returns the `age` in years of the beneficiary
     */
    protected function beneficiaryAge(string $birthdate): int
    {
        return Carbon::parse($birthdate)->age;
    }

    /**
     * A simple name merger to filter the full name
     * @param \Illuminate\Database\Eloquent\Collection|array $name The model instance of the beneficiary
     * @return string Returns the full name of the beneficiary
     */
    protected function beneficiaryName($name)
    {
        $returnedName = null;
        $returnedName = $name->first_name;

        # checks if middle_name is present on user input then adds it if true
        if ($name->middle_name) {
            $returnedName .= ' ' . $name->middle_name;
        }

        $returnedName .= ' ' . $name->last_name;

        # checks if extension_name is present on user input then adds it if true
        if ($name->extension_name) {
            $returnedName .= ' ' . $name->extension_name;
        }

        return $returnedName;
    }

    protected function prefetchNames(string $filteredInputString, int $matchingMode)
    {
        $beneficiariesFromDatabase = null;

        # only take beneficiaries from the start of the year until today
        $startDate = now()->startOfYear();
        $endDate = now();

        # basically a setting to enable extensive matching of duplication checks
        # now, what is `Extensive Matching`?
        # basically, it uses the first letter of every name fields
        # and loops them to get the same names with the same first letters.
        # 0 = exact matching; 1 = soft matching; 2 = extensive matching enabled
        if ($matchingMode === 2) # extensive matching
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
                    'beneficiaries.*'
                ])
                ->get();
        } else if ($matchingMode === 1) # soft matching
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
                    'beneficiaries.*'
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
                    'beneficiaries.*'
                ])
                ->get();
        }

        return $beneficiariesFromDatabase;
    }

    public function updatedBirthdate()
    {
        if ($this->birthdate) {
            $choosenDate = Carbon::parse($this->birthdate)->format('Y-m-d');

            if ($this->type_of_id === 'Senior Citizen ID' && strtotime($choosenDate) > strtotime(Carbon::now()->subYears(60))) {
                $this->type_of_id = 'Barangay ID';
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

    public function updatedContactNum()
    {
        if ($this->contact_num === '') {
            $this->contact_num = null;
            $this->resetValidation('contact_num');
        }
    }

    public function updatedAvgMonthlyIncome()
    {
        if (!$this->occupation && !$this->avg_monthly_income) {
            $this->resetValidation('occupation');
            $this->resetValidation('avg_monthly_income');
            $this->reset('occupation');
        }
    }

    public function updatedOccupation()
    {
        if (!$this->avg_monthly_income && !$this->occupation) {
            $this->resetValidation('avg_monthly_income');
            $this->resetValidation('occupation');
            $this->reset('avg_monthly_income');
        }
    }
    # end of add-beneficiaries-modal -------------------------------------------

    public function mount()
    {
        # check if there's an authenticated user logged in
        if (Auth::check()) {
            # then check if it's a Focal user
            if (Auth::user()->user_type === 'focal')

                # then redirect to their index page...
                return redirect()->route('focal.dashboard');

            # check if it's a Coordinator user
            else if (Auth::user()->user_type === 'coordinator')

                # then redirect to their index page...
                return redirect()->route('coordinator.assignments');
        } else {
            # checks if there's an access code in the session
            if (!$this->accessCode) {
                # redirects to login page if none...
                $this->redirectIntended();
            } else {
                # else, it would then check if the access code is accessible.
                $this->authorizeBeforeExecuting();
            }
        }

        $this->beneficiaries_on_page = $this->defaultPages;
    }

    public function render()
    {
        $this->maxDate = date('m-d-Y', strtotime(Carbon::now()->subYears(18)));
        $this->minDate = date('m-d-Y', strtotime(Carbon::now()->subYears(100)));

        return view('livewire.barangay.listing-page')
            ->title("Brgy. " . $this->batch->barangay_name . " | TU-Efficient");
    }
}
