<?php

namespace App\Livewire\Focal\Implementations;

use App\Models\Batch;
use App\Models\Beneficiary;
use App\Models\Credential;
use App\Models\Implementation;
use App\Models\UserSetting;
use App\Services\Essential;
use App\Services\JaccardSimilarity;
use App\Services\MoneyFormat;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
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
    #[Locked]
    public $duplicationThreshold;
    #[Locked]
    public $maximumIncome;

    # ------------------------------------

    public $includeBirthdate = false;
    public $similarityResults = null;
    public $isResolved = false;
    public $isPerfectDuplicate = false;
    public $isSameImplementation = false;
    public $isSamePending = false;
    public $isIneligible = false;
    public $expanded = false;
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
    public $type_of_id = 'Barangay ID';
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
                function ($attr, $value, $fail) {

                    # throws validation errors whenever it detects illegal characters on names
                    if (Essential::hasIllegal($value)) {
                        $fail('Illegal characters are not allowed.');
                    }
                    # throws validation error whenever the name has a number
                    elseif (Essential::hasNumber($value)) {
                        $fail('Numbers on names are not allowed.');
                    }

                },
            ],
            'middle_name' => [
                # Check if the name has illegal characters
                function ($attr, $value, $fail) {

                    # throws validation errors whenever it detects illegal characters on names
                    if (Essential::hasIllegal($value)) {
                        $fail('Illegal characters are not allowed.');
                    }
                    # throws validation error whenever the name has a number
                    else if (Essential::hasNumber($value)) {
                        $fail('Numbers on names are not allowed.');
                    }
                },
            ],
            'last_name' => [
                'required',
                # Check if the name has illegal characters
                function ($attr, $value, $fail) {

                    # throws validation errors whenever it detects illegal characters on names
                    if (Essential::hasIllegal($value)) {
                        $fail('Illegal characters are not allowed.');
                    }
                    # throws validation error whenever the name has a number
                    else if (Essential::hasNumber($value)) {
                        $fail('Numbers on names are not allowed.');
                    }
                },
            ],
            'extension_name' => [
                # Check if the name has illegal characters
                function ($attr, $value, $fail) {

                    # throws validation errors whenever it detects illegal characters on names
                    if (Essential::hasIllegal($value, true)) {
                        $fail('No illegal characters.');
                    }
                    # throws validation error whenever the name has a number
                    else if (Essential::hasNumber($value)) {
                        $fail('No numbers.');
                    }
                },
            ],
            'birthdate' => 'required',
            'contact_num' => [
                'required',
                function ($attr, $value, $fail) {
                    if (!Essential::hasNumber($value)) {
                        $fail('Value only accepts numbers.');
                    }
                },
                'starts_with:09',
                'digits:11',
            ],
            'occupation' => [
                'required',
                # hard-coded since `required_unless` is messy with `$money($input)` x-mask
                function ($attr, $value, $fail) {
                    if ($this->avg_monthly_income && !$value) {
                        $fail('This field is required.');
                    }
                },
            ],
            'avg_monthly_income' => [
                'required',
                function ($attr, $value, $fail) {

                    if (MoneyFormat::isNegative($value)) {
                        $fail('The value should be more than 1.');
                    }
                    if (!MoneyFormat::isMaskInt($value)) {
                        $fail('The value should be a valid amount.');
                    }
                    if (MoneyFormat::unmask($value) > ($this->maximumIncome ? intval($this->maximumIncome) : intval(config('settings.maximum_income')))) {
                        $fail('Maximum amount is ₱' . MoneyFormat::mask($this->maximumIncome ? intval($this->maximumIncome) : intval(config('settings.maximum_income'))));
                    }

                },
            ],
            'dependent' => [
                'required',
                # Check if the name has illegal characters
                function ($attribute, $value, $fail) {

                    # throws validation errors whenever it detects illegal characters on names
                    if (Essential::hasIllegal($value, false, true)) {
                        $fail('Illegal characters are not allowed.');
                    }
                    # throws validation error whenever the name has a number
                    else if (Essential::hasNumber($value)) {
                        $fail('Numbers on names are not allowed.');
                    }
                },
            ],
            'image_file_path' => 'nullable|image|mimes:png,jpg,jpeg|max:5120',
            'id_number' => 'required',
            'spouse_first_name' => [
                'exclude_unless:civil_status,Married',
                function ($attr, $value, $fail) {
                    if (!isset($value) && empty($value)) {
                        $fail('This field is required.');
                    }
                },
            ],
            'spouse_last_name' => [
                'exclude_unless:civil_status,Married',
                function ($attr, $value, $fail) {
                    if (!isset($value) && empty($value)) {
                        $fail('This field is required.');
                    }
                },
            ],
            'reason_image_file_path' => 'nullable|image|mimes:png,jpg,jpeg|max:5120',
            'image_description' => [
                'required_unless:isPerfectDuplicate,false,isSameImplementation,false,isIneligible,false,isSamePending,false',
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
            'contact_num.required' => 'Contact number is required.',
            'contact_num.digits' => 'Valid number should be 11 digits.',
            'contact_num.starts_with' => 'Valid number should start with \'09\'',
            'occupation.required' => 'This field is required.',
            'avg_monthly_income.required' => 'This field is required.',
            'dependent.required' => 'This field is required.',
            'avg_monthly_income.required_unless' => 'This field is required.',
            'id_number.required' => 'This field is required.',

            'image_file_path.image' => 'It should be an image type.',
            'image_file_path.mimes' => 'Image should be in PNG or JPG format.',
            'image_file_path.max' => 'Image size must not exceed 5MB.',

            'reason_image_file_path.required_if' => 'Case proof is required.',
            'reason_image_file_path.image' => 'Case proof must be an image type.',
            'reason_image_file_path.mimes' => 'Image should be in PNG or JPG format.',
            'reason_image_file_path.max' => 'Image size must not exceed 5MB.',
            'image_description.required_unless' => 'Description must not be left blank.'
        ];
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
        $this->validate();

        # Re-Check for Duplicates
        $this->nameCheck();

        $this->avg_monthly_income = $this->avg_monthly_income ? MoneyFormat::unmask($this->avg_monthly_income) : null;
        $this->birthdate = Carbon::createFromFormat('m-d-Y', $this->birthdate)->format('Y-m-d');
        $this->contact_num = '+63' . substr($this->contact_num, 1);
        $batch = Batch::find(decrypt($this->batchId));
        $implementation = Implementation::find($batch->implementations_id);

        if (strtolower($this->middle_name) === 'n/a' || strtolower($this->middle_name) === 'none' || strtolower($this->middle_name) == '-' || empty($this->middle_name)) {
            $this->middle_name = null;
        }

        if (strtolower($this->extension_name) === 'n/a' || strtolower($this->extension_name) === 'none' || strtolower($this->extension_name) == '-' || empty($this->extension_name)) {
            $this->extension_name = null;
        }

        if (strtolower($this->e_payment_acc_num) === 'n/a' || strtolower($this->e_payment_acc_num) === 'none' || strtolower($this->e_payment_acc_num) == '-' || empty($this->e_payment_acc_num)) {
            $this->e_payment_acc_num = null;
        }

        if (strtolower($this->skills_training) === 'n/a' || strtolower($this->skills_training) === 'none' || strtolower($this->skills_training) == '-' || empty($this->skills_training)) {
            $this->skills_training = null;
        }

        if (strtolower($this->spouse_first_name) === 'n/a' || strtolower($this->spouse_first_name) === 'none' || strtolower($this->spouse_first_name) == '-' || empty($this->spouse_first_name)) {
            $this->spouse_first_name = null;
        }

        if (strtolower($this->spouse_middle_name) === 'n/a' || strtolower($this->spouse_middle_name) === 'none' || strtolower($this->spouse_middle_name) == '-' || empty($this->spouse_middle_name)) {
            $this->spouse_middle_name = null;
        }

        if (strtolower($this->spouse_last_name) === 'n/a' || strtolower($this->spouse_last_name) === 'none' || strtolower($this->spouse_last_name) == '-' || empty($this->spouse_last_name)) {
            $this->spouse_last_name = null;
        }

        if (strtolower($this->spouse_extension_name) === 'n/a' || strtolower($this->spouse_extension_name) === 'none' || strtolower($this->spouse_extension_name) == '-' || empty($this->spouse_extension_name)) {
            $this->spouse_extension_name = null;
        }

        # And then use DB::Transaction to ensure that only 1 record can be saved
        DB::transaction(function () use ($batch, $implementation) {
            $beneficiary = Beneficiary::create([
                'batches_id' => decrypt($this->batchId),
                'first_name' => mb_strtoupper($this->first_name, "UTF-8"),
                'middle_name' => $this->middle_name ? mb_strtoupper($this->middle_name, "UTF-8") : null,
                'last_name' => mb_strtoupper($this->last_name, "UTF-8"),
                'extension_name' => $this->extension_name ? mb_strtoupper($this->extension_name, "UTF-8") : null,
                'birthdate' => $this->birthdate,
                'barangay_name' => $batch->barangay_name,
                'contact_num' => $this->contact_num,
                'occupation' => $this->occupation ?? null,
                'avg_monthly_income' => $this->avg_monthly_income ?? null,
                'city_municipality' => $implementation->city_municipality,
                'province' => $implementation->province,
                'district' => $implementation->district,
                'type_of_id' => $this->type_of_id,
                'id_number' => $this->id_number,
                'e_payment_acc_num' => $this->e_payment_acc_num ?? null,
                'beneficiary_type' => strtolower($this->beneficiary_type),
                'sex' => strtolower($this->sex),
                'civil_status' => strtolower($this->civil_status),
                'age' => $this->beneficiaryAge($this->birthdate),
                'dependent' => strtoupper($this->dependent),
                'self_employment' => strtolower($this->self_employment),
                'skills_training' => $this->skills_training ?? null,
                'is_pwd' => strtolower($this->is_pwd),
                'is_senior_citizen' => intval($this->beneficiaryAge($this->birthdate)) > intval(config('settings.senior_age_threshold') ?? 60) ? 'yes' : 'no',
                'spouse_first_name' => $this->spouse_first_name ? mb_strtoupper($this->spouse_first_name, "UTF-8") : null,
                'spouse_middle_name' => $this->spouse_middle_name ? mb_strtoupper($this->spouse_middle_name, "UTF-8") : null,
                'spouse_last_name' => $this->spouse_last_name ? mb_strtoupper($this->spouse_last_name, "UTF-8") : null,
                'spouse_extension_name' => $this->spouse_extension_name ? mb_strtoupper($this->spouse_extension_name, "UTF-8") : null,
            ]);

            $file = null;

            if ($this->image_file_path) {
                $file = $this->image_file_path->store('credentials');
            }

            Credential::create([
                'beneficiaries_id' => $beneficiary->id,
                'image_description' => null,
                'image_file_path' => $file,
                'for_duplicates' => 'no',
            ]);

            $file = null;

            if ($this->isPerfectDuplicate) {
                if (isset($this->reason_image_file_path) && !empty($this->reason_image_file_path)) {
                    $file = $this->reason_image_file_path->store('credentials');
                }
                Credential::create([
                    'beneficiaries_id' => $beneficiary->id,
                    'image_description' => $this->image_description,
                    'image_file_path' => $file,
                    'for_duplicates' => 'yes',
                ]);
            }

        });
        $this->dispatch('add-beneficiaries');
        $this->resetBeneficiaries();
    }

    public function nameCheck()
    {
        # clear out any previous similarity results
        $this->reset('similarityResults', 'isPerfectDuplicate', 'isSameImplementation', 'isSamePending', 'isIneligible');

        # the filtering process won't go through if first_name, last_name, & birthdate are empty fields
        if ($this->first_name && $this->last_name && $this->birthdate) {

            # double checking again before handing over to the algorithm
            # basically we filter the user input along the way
            $this->first_name = Essential::trimmer($this->first_name);
            $filteredInputString = $this->first_name;
            $this->validateOnly('first_name');

            if ($this->middle_name && $this->middle_name !== '') {
                $this->middle_name = Essential::trimmer($this->middle_name);
                $filteredInputString .= ' ' . $this->middle_name;
                $this->validateOnly('middle_name');
            } else {
                $this->middle_name = null;
            }

            $this->last_name = Essential::trimmer($this->last_name);
            $filteredInputString .= ' ' . $this->last_name;
            $this->validateOnly('last_name');

            # checks if there's an extension_name input
            if ($this->extension_name && $this->extension_name !== '') {
                $this->extension_name = Essential::trimmer($this->extension_name);
                $filteredInputString .= ' ' . $this->extension_name;
                $this->validateOnly('extension_name');
            } else {
                $this->extension_name = null;
            }

            $this->similarityResults = JaccardSimilarity::getResults($this->first_name, $this->middle_name, $this->last_name, $this->extension_name, Carbon::createFromFormat('m-d-Y', $this->birthdate)->format('Y-m-d'), $this->duplicationThreshold);

            $this->setCheckers($this->similarityResults);

            if (!isset($this->similarityResults)) {
                $this->expanded = false;
            }

            $this->dispatch('init-reload')->self();
        }
    }

    protected function setCheckers(?array $results)
    {
        # Queries the project number of this editted beneficiary
        $project_num = Batch::join('implementations', 'implementations.id', '=', 'batches.implementations_id')
            ->where('batches.id', decrypt($this->batchId))
            ->select([
                'implementations.project_num'
            ])
            ->first();

        # Checks if there are any results
        if ($results) {

            # counts how many perfect duplicates encountered from the database
            $perfectCounter = 0;
            foreach ($results as $result) {

                # Queries the batch if it's pending on the possible duplicate beneficiary
                $batch_pending = Batch::where('batch_num', $result['batch_num'])
                    ->where('approval_status', 'pending')
                    ->exists();

                # checks if the result row is a perfect duplicate
                if ($result['is_perfect'] === true) {
                    $this->isPerfectDuplicate = true;
                    $perfectCounter++;
                }

                # checks if the result row is in the same project implementation as this editted beneficiary
                if (isset($project_num)) {
                    if ($result['project_num'] === $project_num->project_num && $this->isPerfectDuplicate) {
                        $this->isSameImplementation = true;
                    }
                }

                if ($result['is_perfect'] && $batch_pending) {
                    $this->isSamePending = true;
                }
            }

            # checks if there are already more than 2 perfect duplicates and mark this editted beneficiary as `ineligible`
            if ($perfectCounter >= 2) {
                $this->isIneligible = true;
            }
        }
    }

    protected function beneficiaryAge($birthdate)
    {
        return Carbon::parse($birthdate)->age;
    }

    #[Computed]
    public function listOfIDs()
    {
        $ids = [
            'Barangay ID',
            'Barangay Certificate',
            'e-Card / UMID',
            "Driver's License",
            'Passport',
            'Phil-health ID',
            'Philippine Postal ID',
            'SSS ID',
            "COMELEC / Voter's ID / COMELEC Registration Form",
            'Philippine Identification (PhilID / ePhilID)',
            'NBI Clearance',
            'Pantawid Pamilya Pilipino Program (4Ps) ID',
            'Integrated Bar of the Philippines (IBP) ID',
            'BIR (TIN)',
            'Pag-ibig ID',
            'Solo Parent ID'
        ];

        return $ids;
    }

    public function updated($property)
    {
        if ($property === 'birthdate') {
            if ($this->birthdate) {
                $choosenDate = Carbon::createFromFormat('m-d-Y', $this->birthdate)->format('Y-m-d');

                if ($this->type_of_id === 'Senior Citizen ID' && strtotime($choosenDate) > strtotime(Carbon::now()->subYears(60))) {
                    $this->type_of_id = 'Barangay ID';
                }

            } else {
                $this->birthdate = null;
            }
        }

        if ($property === 'civil_status') {
            if ($this->civil_status === 'Single') {
                $this->spouse_first_name = null;
                $this->spouse_middle_name = null;
                $this->spouse_last_name = null;
                $this->spouse_extension_name = null;
                $this->resetValidation('spouse_first_name');
                $this->resetValidation('spouse_last_name');
            }
        }

        if ($property === 'beneficiary_type') {
            if ($this->beneficiary_type === 'Underemployed') {
                $this->reset('reason_image_file_path', 'image_description');
                $this->isResolved = false;
            }
        }

        if ($property === 'contact_num') {
            if ($this->contact_num === '') {
                $this->contact_num = null;
                $this->resetValidation('contact_num');
            }
        }
        if ($property === 'is_pwd') {
            if ($this->is_pwd === 'No' && $this->type_of_id === "Person's With Disability (PWD) ID") {
                $this->type_of_id = 'Barangay ID';
            }
        }
    }

    #[Computed]
    public function batch()
    {
        return Batch::find($this->batchId ? decrypt($this->batchId) : null);
    }

    public function resetBeneficiaries()
    {
        $this->resetExcept(
            'batchId',
            'duplicationThreshold',
            'maximumIncome',
        );
        $this->resetValidation();
    }

    public function mount()
    {
        # gets the settings of the user
        $settings = UserSetting::where('users_id', Auth::id())
            ->pluck('value', 'key');
        $this->duplicationThreshold = floatval($settings->get('duplication_threshold', config('settings.duplication_threshold'))) / 100;
        $this->maximumIncome = $settings->get('maximum_income', config('settings.maximum_income'));
    }

    public function render()
    {
        $this->maxDate = date('m-d-Y', strtotime(Carbon::now()->subYears(18)));
        $this->minDate = date('m-d-Y', strtotime(Carbon::now()->subYears(100)));

        return view('livewire.focal.implementations.add-beneficiaries-modal');
    }
}
