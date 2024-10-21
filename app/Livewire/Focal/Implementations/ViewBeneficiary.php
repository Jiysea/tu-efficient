<?php

namespace App\Livewire\Focal\Implementations;

use App\Models\Archive;
use App\Models\Batch;
use App\Models\Beneficiary;
use App\Models\Credential;
use App\Models\Implementation;
use App\Models\UserSetting;
use App\Services\GenerateActivityLogs;
use App\Services\JaccardSimilarity;
use App\Services\MoneyFormat;
use Auth;
use Carbon\Carbon;
use DB;
use Hash;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Reactive;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Storage;

class ViewBeneficiary extends Component
{
    use WithFileUploads;
    #[Reactive]
    #[Locked]
    public $passedBeneficiaryId;
    #[Locked]
    public $passedCredentialId;
    #[Locked]
    public $duplicationThreshold;
    public $editMode = false;
    public $deleteBeneficiaryModal = false;
    public $viewCredentialsModal = false;
    public $maxDate;
    public $minDate;

    # ---------------------------------------

    public $similarityResults;
    public $isResolved = false;
    public $isPerfectDuplicate = false;
    public $isSpecialCase = false;
    public $isSameImplementation = false;
    public $isIneligible = false;
    public $expanded = false;
    public $addReasonModal = false;

    # ----------------------------------------

    public $oldValues = [];
    public $confirmTypeChangeModal = false;
    public $confirmChangeType = '';

    # ----------------------------------------

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
    #[Validate]
    public $reason_image_file_path;
    #[Validate]
    public $image_description;
    #[Validate]
    public $password_delete;
    public $saved_image_path;
    public $reason_saved_image_path;

    # ---------------------------------------

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
                    }
                },
                'starts_with:09',
                'digits:11',
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
            'reason_image_file_path' => [
                'nullable',
                'image',
                'mimes:png,jpg,jpeg',
                'max:5120',
            ],
            'image_description' => [
                'exclude_if:isPerfectDuplicate,false,isSameImplementation,false,isIneligible,false',
                function ($attr, $value, $fail) {
                    if (!isset($value) && empty($value) && !$this->isResolved) {
                        $fail('Description must not be left blank.', );
                    }
                },
            ],
            'password_delete' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!Hash::check($value, Auth::user()->password)) {
                        $fail('Wrong password.');
                    }
                },
            ],
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'This field is required.',
            'last_name.required' => 'This field is required.',
            'birthdate.required' => 'This field is required.',
            'contact_num.required' => 'Contact number is required.',
            'contact_num.digits' => 'Valid number should be 11 digits.',
            'contact_num.starts_with' => 'Valid number should start with \'09\'',
            'avg_monthly_income.required_unless' => 'This field is required.',
            'id_number.required' => 'This field is required.',

            'image_file_path.image' => 'It should be an image type.',
            'image_file_path.mimes' => 'It should be in PNG or JPG format.',

            'image_description.required_if' => 'Description must not be left blank.',

            'reason_image_file_path.required_if' => 'Case proof is required.',
            'reason_image_file_path.image' => 'Case proof must be an image type.',
            'reason_image_file_path.mimes' => 'It must be in PNG or JPG format.',
            'reason_image_file_path.max' => 'Image size must not exceed 5MB.',
            'password_delete.required' => 'This field is required.',
        ];
    }

    public function toggleEdit()
    {
        $this->editMode = !$this->editMode;

        if ($this->editMode) {
            $this->first_name = $this->beneficiary->first_name;
            $this->middle_name = $this->beneficiary->middle_name ?? null;
            $this->last_name = $this->beneficiary->last_name;
            $this->extension_name = $this->beneficiary->extension_name ?? null;
            $this->birthdate = Carbon::parse($this->beneficiary->birthdate)->format('m-d-Y');
            $this->sex = ucwords($this->beneficiary->sex);
            $this->contact_num = "0" . substr($this->beneficiary->contact_num, 3);
            $this->occupation = $this->beneficiary->occupation ?? null;
            $this->civil_status = ucwords($this->beneficiary->civil_status);
            $this->avg_monthly_income = $this->beneficiary->avg_monthly_income ? MoneyFormat::mask($this->beneficiary->avg_monthly_income) : null;
            $this->dependent = $this->beneficiary->dependent ?? null;
            $this->e_payment_acc_num = $this->beneficiary->e_payment_acc_num ?? null;
            $this->self_employment = ucwords($this->beneficiary->self_employment);
            $this->beneficiary_type = ucwords($this->beneficiary->beneficiary_type);
            $this->skills_training = $this->beneficiary->skills_training ?? null;
            $this->is_pwd = ucwords($this->beneficiary->is_pwd);
            $this->type_of_id = $this->beneficiary->type_of_id;
            $this->id_number = $this->beneficiary->id_number;
            $this->spouse_first_name = $this->beneficiary->spouse_first_name ?? null;
            $this->spouse_middle_name = $this->beneficiary->spouse_middle_name ?? null;
            $this->spouse_last_name = $this->beneficiary->spouse_last_name ?? null;
            $this->spouse_extension_name = $this->beneficiary->spouse_extension_name ?? null;
            $this->saved_image_path = null;
            $this->reason_saved_image_path = null;
            $this->image_description = null;

            foreach ($this->credentials as $credential) {
                if ($credential->for_duplicates === 'no') {
                    $this->saved_image_path = $credential->image_file_path;
                } elseif ($credential->for_duplicates === 'yes') {
                    $this->reason_saved_image_path = $credential->image_file_path;
                    $this->image_description = $credential->image_description;
                }
            }

            # Check if this is a special case edit then make it resolved
            if ($this->beneficiary->beneficiary_type === 'special case') {
                $this->isSpecialCase = true;
                $this->isResolved = true;
            }
            # Otherwise, if the Special Case is removed, it's unresolved.
            else {
                $this->isSpecialCase = false;
                $this->isResolved = false;
            }

            # Add old values in here for ConfirmChangeTypeModal
            $this->oldValues = [
                'first_name' => $this->first_name,
                'middle_name' => $this->middle_name,
                'last_name' => $this->last_name,
                'extension_name' => $this->extension_name,
                'birthdate' => $this->birthdate,
                'beneficiary_type' => $this->beneficiary_type,
            ];

            $this->resetValidation();
            $this->nameCheck();

            $this->dispatch('init-reload')->self();
        } else {
            $this->resetExcept(
                'passedBeneficiaryId',
                'duplicationThreshold',
                'deleteBeneficiaryModal',
            );
        }
    }

    public function saveReason()
    {
        $this->validateOnly('reason_image_file_path');
        $this->validateOnly('image_description');

        $this->isResolved = true;
        $this->addReasonModal = false;
    }

    public function nameCheck()
    {
        # clear out any previous similarity results
        $this->similarityResults = null;
        $this->isPerfectDuplicate = false;
        $this->isSameImplementation = false;
        $this->isIneligible = false;

        # the filtering process won't go through if first_name, last_name, & birthdate are empty fields
        if ($this->first_name && $this->last_name && $this->birthdate) {

            # double checking again before handing over to the algorithm
            # basically we filter the user input along the way
            $this->first_name = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $this->first_name)));
            $filteredInputString = $this->first_name;
            $this->validateOnly('first_name');

            if ($this->middle_name && $this->middle_name !== '') {
                $this->middle_name = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $this->middle_name)));
                $filteredInputString .= ' ' . $this->middle_name;
                $this->validateOnly('middle_name');
            } else {
                $this->middle_name = null;
            }

            $this->last_name = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $this->last_name)));
            $filteredInputString .= ' ' . $this->last_name;
            $this->validateOnly('last_name');

            # checks if there's an extension_name input
            if ($this->extension_name && $this->extension_name !== '') {
                $this->extension_name = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $this->extension_name)));
                $filteredInputString .= ' ' . $this->extension_name;
                $this->validateOnly('extension_name');
            } else {
                $this->extension_name = null;
            }

            $this->similarityResults = JaccardSimilarity::getResultsFromEdit($this->first_name, $this->middle_name, $this->last_name, $this->extension_name, Carbon::createFromFormat('m-d-Y', $this->birthdate)->format('Y-m-d'), $this->duplicationThreshold, $this->passedBeneficiaryId);
            $this->setCheckers($this->similarityResults);

            if (!isset($this->similarityResults)) {
                $this->expanded = false;
            }
        }

    }

    protected function setCheckers(?array $results)
    {
        # Queries the project number of this editted beneficiary
        $project_num = Batch::join('implementations', 'implementations.id', '=', 'batches.implementations_id')
            ->where('batches.id', $this->batch->id)
            ->select([
                'implementations.project_num'
            ])
            ->first();

        # Checks if there are any results
        if ($results) {

            # counts how many perfect duplicates encountered from the database
            $perfectCounter = 0;
            foreach ($results as $result) {

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
            }

            # checks if there are already more than 2 perfect duplicates and mark this editted beneficiary as `ineligible`
            if ($perfectCounter >= 2) {
                $this->isIneligible = true;
            }
        }
    }

    # a livewire action executes after clicking the `Save` button
    public function editBeneficiary()
    {
        $this->validate(
            [
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
                        }
                    },
                    'starts_with:09',
                    'digits:11',
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
                'reason_image_file_path' => [
                    'nullable',
                    'image',
                    'mimes:png,jpg,jpeg',
                    'max:5120',
                ],
                'image_description' => [
                    'exclude_if:isPerfectDuplicate,false,isSameImplementation,false,isIneligible,false',
                    function ($attr, $value, $fail) {
                        if (!isset($value) && empty($value) && !$this->isResolved) {
                            $fail('Description must not be left blank.', );
                        }
                    },
                ],
            ],
            [
                'first_name.required' => 'This field is required.',
                'last_name.required' => 'This field is required.',
                'birthdate.required' => 'This field is required.',
                'contact_num.required' => 'Contact number is required.',
                'contact_num.digits' => 'Valid number should be 11 digits.',
                'contact_num.starts_with' => 'Valid number should start with \'09\'',
                'avg_monthly_income.required_unless' => 'This field is required.',
                'id_number.required' => 'This field is required.',

                'image_file_path.image' => 'It should be an image type.',
                'image_file_path.mimes' => 'Image must be in PNG or JPG format.',
                'image_file_path.max' => 'Image size must not exceed 5MB.',

                'reason_image_file_path.image' => 'Case proof must be an image type.',
                'reason_image_file_path.mimes' => 'It must be in PNG or JPG format.',
                'reason_image_file_path.max' => 'Image size must not exceed 5MB.',
            ],
        );
        # Re-Check for Duplicates
        $this->nameCheck();

        # Filter the necessitites
        $this->avg_monthly_income = $this->avg_monthly_income ? MoneyFormat::unmask($this->avg_monthly_income) : null;
        $this->birthdate = Carbon::createFromFormat('m-d-Y', $this->birthdate)->format('Y-m-d');
        $this->contact_num = '+63' . substr($this->contact_num, 1);
        $batch = Batch::find($this->beneficiary->batches_id);
        $implementation = Implementation::find($batch->implementations_id);

        # And then use DB::Transaction to ensure that only 1 record can be saved
        DB::transaction(function () use ($batch, $implementation) {
            $isChanged = false;
            $beneficiary = Beneficiary::where('id', decrypt($this->passedBeneficiaryId))
                ->where('updated_at', '<', Carbon::now())
                ->first();

            $identity = Credential::where('beneficiaries_id', decrypt($this->passedBeneficiaryId))
                ->where('for_duplicates', 'no')
                ->where('updated_at', '<', Carbon::now())
                ->first();

            $special_case = Credential::where('beneficiaries_id', decrypt($this->passedBeneficiaryId))
                ->where('for_duplicates', 'yes')
                ->where('updated_at', '<', Carbon::now())
                ->first();

            # If the system detects that it has already been updated/deleted before this request,
            # then send an optimistic lock notification and close the modal to refresh the records.
            if (!$beneficiary) {
                $this->dispatch('optimistic-lock', message: 'This record has been updated by someone else. Refreshing...');
                $this->resetViewBeneficiary();
                return;
            } else {
                $beneficiary->fill([
                    'batches_id' => $batch->id,
                    'first_name' => $this->first_name,
                    'middle_name' => $this->middle_name ?? null,
                    'last_name' => $this->last_name,
                    'extension_name' => $this->extension_name ?? null,
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
                    'dependent' => $this->dependent ?? null,
                    'self_employment' => strtolower($this->self_employment),
                    'skills_training' => $this->skills_training ?? null,
                    'is_pwd' => strtolower($this->is_pwd),
                    'is_senior_citizen' => intval($this->beneficiaryAge($this->birthdate)) > intval(config('settings.senior_age_threshold') ?? 60) ? 'yes' : 'no',
                    'spouse_first_name' => $this->spouse_first_name ?? null,
                    'spouse_middle_name' => $this->spouse_middle_name ?? null,
                    'spouse_last_name' => $this->spouse_last_name ?? null,
                    'spouse_extension_name' => $this->spouse_extension_name ?? null,
                ]);

                if ($beneficiary->isDirty()) {
                    $beneficiary->save();
                    $isChanged = true;
                }
            }

            $file = null;

            if ($identity) {
                if ($this->image_file_path) {
                    $file = $this->image_file_path->store(path: 'credentials');

                    $identity->fill([
                        'image_file_path' => $file,
                    ]);
                }

                if ($identity->isDirty()) {
                    $identity->save();
                    $isChanged = true;
                }
            }

            if ($special_case) {
                if ($this->reason_image_file_path) {
                    $file = $this->reason_image_file_path->store(path: 'credentials');
                    $special_case->fill([
                        'image_description' => $this->image_description,
                        'image_file_path' => $file,
                    ]);
                } elseif (!isset($this->reason_saved_image_path) || empty($this->reason_saved_image_path)) {
                    $file = null;
                    $special_case->fill([
                        'image_description' => $this->image_description,
                        'image_file_path' => $file,
                    ]);
                }

                if ($special_case->isDirty()) {
                    $special_case->save();
                    $isChanged = true;
                }
            }

            if ($isChanged) {
                $this->dispatch('edit-beneficiary');
            }

            $this->resetViewBeneficiary();
        });

    }

    public function deleteBeneficiary()
    {
        $beneficiary = Beneficiary::find(decrypt($this->passedBeneficiaryId));
        $this->authorize('delete-beneficiary-focal', $beneficiary);

        # if the batch where this beneficiary belongs to is approved,
        # then we should archive it
        DB::transaction(function () use ($beneficiary) {

            $credentials = Credential::where('beneficiaries_id', decrypt($this->passedBeneficiaryId))
                ->get();

            if ($this->projectInformation->approval_status === 'approved') {

                // # Archive their credentials first
                // foreach ($credentials as $credential) {
                //     Archive::create([
                //         'last_id' => $credential->id,
                //         'source_table' => 'credentials',
                //         'data' => $credential->toJson(),
                //     ]);
                //     $credential->delete();
                // }

                // # then archive the Beneficiary record
                // Archive::create([
                //     'last_id' => $beneficiary->id,
                //     'source_table' => 'beneficiaries',
                //     'data' => $beneficiary->toJson(),
                // ]);
                // $beneficiary->delete();

                // $this->resetViewBeneficiary();
                // $this->js('viewBeneficiaryModal = false;');
                // $this->dispatch('archive-beneficiary');
            }

            # otherwise, we could just delete it.
            else {
                foreach ($credentials as $credential) {
                    if (isset($credential->image_file_path)) {
                        Storage::delete($credential->image_file_path);
                    }
                    $credential->delete();
                }
                $old = $beneficiary;
                $beneficiary->delete();

                GenerateActivityLogs::set_delete_beneficiary_log(Auth::id(), $old, $this->projectInformation->project_num, $this->projectInformation->batch_num);

                $this->resetViewBeneficiary();
                $this->js('viewBeneficiaryModal = false;');
                $this->dispatch('delete-beneficiary');
            }
        });
    }

    #[Computed]
    public function projectInformation()
    {
        if ($this->passedBeneficiaryId) {
            $info = Implementation::join('batches', 'batches.implementations_id', '=', 'implementations.id')
                ->join('beneficiaries', 'beneficiaries.batches_id', '=', 'batches.id')
                ->where('beneficiaries.id', decrypt($this->passedBeneficiaryId))
                ->select(
                    [
                        'implementations.project_num',
                        'batches.batch_num',
                        'batches.submission_status',
                        'batches.approval_status',
                    ]
                )
                ->first();
            return $info;
        }
    }

    #[Computed]
    public function basicInformation()
    {
        # get each value first
        $values = [
            'First Name' => $this->beneficiary->first_name,
            'Middle Name' => $this->beneficiary->middle_name ?? '-',
            'Last Name' => $this->beneficiary->last_name,
            'Extension Name' => $this->beneficiary->extension_name ?? '-',
            'Birthdate' => Carbon::parse($this->beneficiary->birthdate)->format('M d, Y'),
            'Contact Number' => $this->beneficiary->contact_num,
            'Sex' => ucwords($this->beneficiary->sex),
            'Age' => $this->beneficiary->age,
            'Civil Status' => ucwords($this->beneficiary->civil_status),
        ];

        return $values;
    }

    #[Computed]
    public function addressInformation()
    {
        # get each value first
        $values = [
            'Province' => $this->beneficiary->province,
            'City/Municipality' => $this->beneficiary->city_municipality,
            'District' => $this->beneficiary->district,
            'Barangay' => $this->beneficiary->barangay_name,
        ];

        return $values;
    }

    #[Computed]
    public function additionalInformation()
    {
        # get each value first
        $values = [
            'Occupation' => $this->beneficiary->occupation ?? '-',
            'Avg. Monthly Income' => $this->beneficiary->avg_monthly_income ? '₱' . MoneyFormat::mask($this->beneficiary->avg_monthly_income) : '-',
            'Type of Beneficiary' => ucwords($this->beneficiary->beneficiary_type),
            'e-Payment Account Number' => $this->beneficiary->e_payment_acc_num ?? '-',
            'Interested in Self-Employment' => ucwords($this->beneficiary->self_employment),
            'Type of ID' => $this->beneficiary->type_of_id,
            'ID Number' => $this->beneficiary->id_number,
            'Dependent' => $this->beneficiary->dependent ?? '-',
            'Skills Training' => $this->beneficiary->skills_training ?? '-',
            'is PWD' => ucwords($this->beneficiary->is_pwd),
            'is Senior Citizen' => ucwords($this->beneficiary->is_senior_citizen),
        ];

        return $values;
    }

    #[Computed]
    public function spouseInformation()
    {
        # get each value first
        $values = [
            'Spouse First Name' => $this->beneficiary->spouse_first_name ?? '-',
            'Spouse Middle Name' => $this->beneficiary->spouse_middle_name ?? '-',
            'Spouse Last Name' => $this->beneficiary->spouse_last_name ?? '-',
            'Spouse Extension Name' => $this->beneficiary->spouse_extension_name ?? '-',
        ];

        return $values;
    }

    public function viewCredential($type)
    {
        if ($type === 'identity') {

            foreach ($this->credentials as $credential) {
                if ($credential->for_duplicates === 'no') {
                    $this->passedCredentialId = encrypt($credential->id);
                    $this->viewCredentialsModal = true;
                }
            }

        } elseif ($type === 'special') {

            foreach ($this->credentials as $credential) {
                if ($credential->for_duplicates === 'yes') {
                    $this->passedCredentialId = encrypt($credential->id);
                    $this->viewCredentialsModal = true;
                }
            }
        }
    }

    #[Computed]
    public function credentials()
    {
        $credentials = Credential::where('beneficiaries_id', decrypt($this->passedBeneficiaryId))
            ->get();

        return $credentials;
    }

    #[Computed]
    public function batch()
    {
        if ($this->passedBeneficiaryId) {
            $batch = Batch::find($this->beneficiary->batches_id);
            return $batch;
        }
    }

    #[Computed]
    public function beneficiary()
    {
        if ($this->passedBeneficiaryId) {
            $beneficiary = Beneficiary::find(decrypt($this->passedBeneficiaryId));
            return $beneficiary;
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

    public function setFieldName($field)
    {
        if ($field === 'middle_name') {
            $this->middle_name = $this->beneficiary->middle_name;
        } elseif ($field === 'extension_name') {
            $this->extension_name = $this->beneficiary->extension_name;
        }
    }

    public function updated($property)
    {
        if ($property === 'first_name') {
            if ($this->isSpecialCase && $this->first_name !== $this->oldValues['first_name']) {
                $this->confirmTypeChangeModal = true;
                $this->confirmChangeType = 'first_name';
            }
        }
        if ($property === 'middle_name') {
            if ($this->isSpecialCase && $this->middle_name !== $this->oldValues['middle_name']) {
                $this->confirmTypeChangeModal = true;
                $this->confirmChangeType = 'middle_name';
            }
        }
        if ($property === 'last_name') {
            if ($this->isSpecialCase && $this->last_name !== $this->oldValues['last_name']) {
                $this->confirmTypeChangeModal = true;
                $this->confirmChangeType = 'last_name';
            }
        }
        if ($property === 'extension_name') {
            if ($this->isSpecialCase && $this->extension_name !== $this->oldValues['extension_name']) {
                $this->confirmTypeChangeModal = true;
                $this->confirmChangeType = 'extension_name';
            }
        }
        if ($property === 'birthdate') {
            if ($this->birthdate) {
                $choosenDate = Carbon::createFromFormat('m-d-Y', $this->birthdate)->format('Y-m-d');

                if ($this->type_of_id === 'Senior Citizen ID' && strtotime($choosenDate) > strtotime(Carbon::now()->subYears(60))) {
                    $this->type_of_id = 'Barangay ID';
                }
                if ($this->isSpecialCase && $this->birthdate !== $this->oldValues['birthdate']) {
                    $this->confirmTypeChangeModal = true;
                    $this->confirmChangeType = 'birthdate';
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
            if ($this->isSpecialCase && $this->beneficiary_type !== $this->oldValues['beneficiary_type']) {
                $this->confirmTypeChangeModal = true;
                $this->confirmChangeType = 'beneficiary_type';
            }
        }

        if ($property === 'contact_num') {
            if ($this->contact_num === '') {
                $this->contact_num = null;
                $this->resetValidation('contact_num');
            }
        }
        if ($property === 'avg_monthly_income') {
            if (!$this->occupation && !$this->avg_monthly_income) {
                $this->resetValidation('occupation');
                $this->resetValidation('avg_monthly_income');
                $this->reset('occupation');
            }
        }
        if ($property === 'occupation') {
            if (!$this->avg_monthly_income && !$this->occupation) {
                $this->resetValidation('avg_monthly_income');
                $this->resetValidation('occupation');
                $this->reset('avg_monthly_income');
            }
        }
        if ($property === 'is_pwd') {
            if ($this->is_pwd === 'No' && $this->type_of_id === "Person's With Disability (PWD) ID") {
                $this->type_of_id = 'Barangay ID';
            }
        }
    }

    public function revokeSpecialCase()
    {
        $this->reset(
            'reason_saved_image_path',
            'image_description',
        );
        $this->resetValidation([
            'reason_saved_image_path',
            'image_description'
        ]);
        $this->isSpecialCase = false;
        $this->isResolved = false;
    }

    public function resetViewBeneficiary()
    {
        $this->resetExcept('passedBeneficiaryId', 'duplicationThreshold');
    }

    public function mount()
    {
        $settings = UserSetting::where('users_id', Auth::id())
            ->pluck('value', 'key');
        $this->duplicationThreshold = intval($settings->get('duplication_threshold', config('settings.duplication_threshold'))) / 100;
    }

    public function render()
    {
        $this->maxDate = date('m-d-Y', strtotime(Carbon::now()->subYears(18)));
        $this->minDate = date('m-d-Y', strtotime(Carbon::now()->subYears(100)));

        return view('livewire.focal.implementations.view-beneficiary');
    }
}
