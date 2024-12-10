<?php

namespace App\Livewire\Barangay\ListingPage;

use App\Models\Batch;
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
use Carbon\Carbon;
use DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Js;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Storage;

class EditBeneficiaryModal extends Component
{
    use WithFileUploads;

    #[Reactive]
    #[Locked]
    public $beneficiaryId;
    #[Locked]
    public $maxDate;
    #[Locked]
    public $minDate;
    #[Locked]
    public $duplicationThreshold;
    #[Locked]
    public $maximumIncome;
    #[Locked]
    public $is_sectoral;

    # ------------------------------------

    public $similarityResults;
    public $isResolved = false;
    public $isPerfectDuplicate = false;
    public $isSpecialCase = false;
    public $isSameImplementation = false;
    public $isSamePending = false;
    public $isOriginal = false;
    public $isIneligible = false;
    public $expanded = false;
    public $addReasonModal = false;
    public $searchBarangay;

    # ----------------------------------------------

    public $oldValues = [];
    public $confirmChangeType = '';
    public $confirmTypeChangeModal = false;

    # ----------------------------------------------

    #[Validate]
    public $district;
    #[Validate]
    public $barangay_name;
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
    public $saved_image_path;
    public $reason_saved_image_path;

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
            'birthdate' => [
                'required',
                function ($a, $value, $fail) {
                    if (is_null(Essential::extract_date($value))) {
                        $this->js('$wire.closeBirthdate();');
                        $fail('Invalid date format.');
                    }
                    if (Essential::extract_date($value, false) !== 'm-d-Y') {
                        $this->js('$wire.closeBirthdate();');
                        $fail('Please use the \'mm-dd-yyyy\' format.');
                    }
                }
            ],
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
            'district' => [
                'exclude_if:is_sectoral,0',
                'required_if:is_sectoral,1',
            ],
            'barangay_name' => [
                'exclude_if:is_sectoral,0',
                'required_if:is_sectoral,1',
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
            'image_file_path' => [
                'exclude_unless:saved_image_path,null',
                'required',
                'image',
                'mimes:png,jpg,jpeg',
                'max:5120',
            ],
            'id_number' => 'required',
            'spouse_first_name' => [
                'exclude_unless:civil_status,Married,Separated,Widowed',
                'required_if:civil_status,Married,Separated,Widowed',

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
            'spouse_middle_name' => [
                'exclude_unless:civil_status,Married,Separated,Widowed',

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
            'spouse_last_name' => [
                'exclude_unless:civil_status,Married,Separated,Widowed',
                'required_if:civil_status,Married,Separated,Widowed',

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
            'spouse_extension_name' => [
                'exclude_unless:civil_status,Married,Separated,Widowed',

                function ($attr, $value, $fail) {

                    # throws validation errors whenever it detects illegal characters on names
                    if (Essential::hasIllegal($value, true)) {
                        $fail('Illegal characters are not allowed.');
                    }
                    # throws validation error whenever the name has a number
                    elseif (Essential::hasNumber($value)) {
                        $fail('Numbers on names are not allowed.');
                    }

                },
            ],
            'reason_image_file_path' => [
                'exclude_unless:saved_image_path,null',
                'exclude_if:isPerfectDuplicate,false,isSameImplementation,false,isIneligible,false,isSamePending,false',
                'required',
                'image',
                'mimes:png,jpg,jpeg',
                'max:5120',
            ],
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
            'district.required_if' => 'This field is required.',
            'barangay_name.required_if' => 'This field is required.',
            'occupation.required' => 'This field is required.',
            'avg_monthly_income.required' => 'This field is required.',
            'dependent.required' => 'This field is required.',
            'avg_monthly_income.required_unless' => 'This field is required.',
            'id_number.required' => 'This field is required.',
            'spouse_first_name.required_if' => 'This field is required.',
            'spouse_last_name.required_if' => 'This field is required.',

            'contact_num.digits' => 'Valid number should be 11 digits.',
            'contact_num.starts_with' => 'Valid number should start with \'09\'',

            'image_file_path.required' => 'An ID proof is required.',
            'image_file_path.image' => 'It should be an image type.',
            'image_file_path.mimes' => 'Image should be in PNG or JPG format.',
            'image_file_path.max' => 'Image size must not exceed 5MB.',

            'reason_image_file_path.required' => 'Case proof is required.',
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
        $this->validate(
            [
                'reason_image_file_path' => [
                    'exclude_unless:saved_image_path,null',
                    'exclude_if:isPerfectDuplicate,false,isSameImplementation,false,isIneligible,false,isSamePending,false',
                    'required',
                    'image',
                    'mimes:png,jpg,jpeg',
                    'max:5120',
                ],
                'image_description' => [
                    'required_unless:isPerfectDuplicate,false,isSameImplementation,false,isIneligible,false,isSamePending,false',
                ],
            ],
            [
                'reason_image_file_path.required' => 'Case proof is required.',
                'reason_image_file_path.image' => 'Case proof must be an image type.',
                'reason_image_file_path.mimes' => 'Image should be in PNG or JPG format.',
                'reason_image_file_path.max' => 'Image size must not exceed 5MB.',
                'image_description.required_unless' => 'Description must not be left blank.'
            ]
        );

        $this->oldValues['reason_image_file_path'] = $this->reason_image_file_path;

        if ($this->reason_image_file_path) {
            $this->reason_saved_image_path = null;
        }

        if (is_null($this->reason_saved_image_path)) {
            $this->oldValues['reason_saved_image_path'] = null;
        }

        $this->isResolved = true;
        $this->addReasonModal = false;
    }

    # triggers when a user clicks the `CANCEL` button on Add Reason Modal
    public function resetReason()
    {
        if (!$this->isResolved) {
            $this->reset('reason_image_file_path', 'image_description');
            $this->resetValidation(['reason_image_file_path', 'image_description']);
        } else {
            $this->reason_saved_image_path = $this->oldValues['reason_saved_image_path'];
            $this->image_description = $this->oldValues['image_description'];
            if (is_null($this->oldValues['reason_image_file_path'])) {
                $this->reset('reason_image_file_path');
            } else {
                $this->reason_image_file_path = $this->oldValues['reason_image_file_path'];
            }
            $this->resetValidation(['reason_image_file_path', 'image_description']);
        }

        $this->addReasonModal = false;
    }

    # END OF ADD REASON MODAL AREA
    # ----------------------------------------------------------------------------------------------

    # a livewire action executes after clicking the `Save` button
    public function editBeneficiary()
    {
        $this->validate();
        $this->js('$parent.authorizeBeforeExecuting();');

        # And then use DB::Transaction to ensure that only 1 record can be saved
        DB::transaction(function () {
            $batch = Batch::find($this->beneficiary->batches_id);
            $implementation = Implementation::find($batch->implementations_id);
            $isChanged = false;

            $this->normalizeStrings();

            # Re-Check for Duplicates
            $this->nameCheck();

            # Filter the necessitites
            $this->avg_monthly_income = $this->avg_monthly_income ? MoneyFormat::unmask($this->avg_monthly_income) : null;
            $this->birthdate = Carbon::createFromFormat('m-d-Y', $this->birthdate)->format('Y-m-d');
            $this->contact_num = '+63' . substr($this->contact_num, 1);

            if (!$this->is_sectoral) {
                $this->district = $batch->district;
                $this->barangay_name = $batch->barangay_name;
            } else {
                $this->district = null;
                $this->barangay_name = null;
            }

            $beneficiary = Beneficiary::where('id', decrypt($this->beneficiaryId))
                ->where('updated_at', '<', Carbon::now())
                ->first();

            $identity = Credential::where('beneficiaries_id', decrypt($this->beneficiaryId))
                ->where('for_duplicates', 'no')
                ->where('updated_at', '<', Carbon::now())
                ->first();

            $special_case = Credential::where('beneficiaries_id', decrypt($this->beneficiaryId))
                ->where('for_duplicates', 'yes')
                ->where('updated_at', '<', Carbon::now())
                ->first();

            # If the system detects that it has already been updated/deleted before this request,
            # then send an optimistic lock notification and close the modal to refresh the records.
            if (!$beneficiary) {
                $this->dispatch('optimistic-lock', message: 'This record has been updated by someone else. Refreshing...');
                $this->resetEditBeneficiary();
                return;
            } else {
                $beneficiary->fill([
                    'batches_id' => $batch->id,
                    'first_name' => mb_strtoupper($this->first_name, "UTF-8"),
                    'middle_name' => $this->middle_name ? mb_strtoupper($this->middle_name, "UTF-8") : null,
                    'last_name' => mb_strtoupper($this->last_name, "UTF-8"),
                    'extension_name' => $this->extension_name ? mb_strtoupper($this->extension_name, "UTF-8") : null,
                    'birthdate' => $this->birthdate,
                    'barangay_name' => $this->barangay_name,
                    'contact_num' => $this->contact_num,
                    'occupation' => $this->occupation ?? null,
                    'avg_monthly_income' => $this->avg_monthly_income ?? null,
                    'city_municipality' => $implementation->city_municipality,
                    'province' => $implementation->province,
                    'district' => $this->district,
                    'type_of_id' => $this->type_of_id,
                    'id_number' => $this->id_number,
                    'e_payment_acc_num' => $this->e_payment_acc_num ?? null,
                    'beneficiary_type' => strtolower($this->beneficiary_type),
                    'sex' => strtolower($this->sex),
                    'civil_status' => strtolower($this->civil_status),
                    'age' => $this->beneficiaryAge($this->birthdate),
                    'dependent' => mb_strtoupper($this->dependent, "UTF-8"),
                    'self_employment' => strtolower($this->self_employment),
                    'skills_training' => $this->skills_training ?? null,
                    'is_pwd' => strtolower($this->is_pwd),
                    'is_senior_citizen' => intval($this->beneficiaryAge($this->birthdate)) > intval(config('settings.senior_age_threshold') ?? 60) ? 'yes' : 'no',
                    'spouse_first_name' => $this->spouse_first_name ? mb_strtoupper($this->spouse_first_name, "UTF-8") : null,
                    'spouse_middle_name' => $this->spouse_middle_name ? mb_strtoupper($this->spouse_middle_name, "UTF-8") : null,
                    'spouse_last_name' => $this->spouse_last_name ? mb_strtoupper($this->spouse_last_name, "UTF-8") : null,
                    'spouse_extension_name' => $this->spouse_extension_name ? mb_strtoupper($this->spouse_extension_name, "UTF-8") : null,
                ]);

                if ($beneficiary->isDirty()) {
                    $beneficiary->save();
                    $isChanged = true;
                }
            }

            $file = null;

            if ($identity) {

                if ($this->image_file_path || is_null($this->saved_image_path)) {
                    if ($this->image_file_path)
                        $file = $this->image_file_path->store(path: 'credentials');

                    if ($identity->image_file_path) {
                        if (Storage::exists($identity->image_file_path)) {
                            Storage::delete($identity->image_file_path);
                        }
                    }
                    $identity->fill([
                        'image_file_path' => $file,
                    ]);
                }

                if ($identity->isDirty()) {
                    $identity->save();

                    if ($file) {
                        LogIt::set_edit_beneficiary_identity($beneficiary, $batch, auth()->user());
                    } else {
                        LogIt::set_remove_beneficiary_identity($beneficiary, $batch, auth()->user());
                    }
                    $isChanged = true;
                }
            } else {
                unset($this->beneficiary, $this->credentials);
            }

            if ($this->isPerfectDuplicate) {

                if ($special_case) {

                    if ($this->reason_image_file_path || $this->reason_saved_image_path) {
                        if ($this->reason_image_file_path)
                            $file = $this->reason_image_file_path->store(path: 'credentials');
                        else
                            $file = null;

                        if ($special_case->image_file_path) {
                            if (Storage::exists($special_case->image_file_path)) {
                                Storage::delete($special_case->image_file_path);
                            }
                        }
                        $special_case->fill([
                            'image_description' => $this->image_description,
                            'image_file_path' => $file,
                        ]);
                    } elseif (is_null($this->reason_image_file_path) && is_null($this->reason_saved_image_path)) {
                        if ($special_case->image_file_path) {
                            if (Storage::exists($special_case->image_file_path)) {
                                Storage::delete($special_case->image_file_path);
                            }
                        }

                        $special_case->fill([
                            'image_description' => $this->image_description,
                            'image_file_path' => null,
                        ]);
                    }

                    if ($special_case->isDirty()) {
                        $special_case->save();

                        if ($file) {
                            LogIt::set_edit_beneficiary_special_case($beneficiary, $special_case, $batch, auth()->user());
                        } else {
                            LogIt::set_remove_beneficiary_special_case($beneficiary, $special_case, $batch, auth()->user());
                        }
                        $isChanged = true;
                    }

                } else {
                    unset($this->beneficiary, $this->credentials);
                }

            } else {
                if ($special_case) {
                    if ($special_case->image_file_path) {
                        if (Storage::exists($special_case->image_file_path)) {
                            Storage::delete($special_case->image_file_path);
                        }
                    }

                    $special_case->delete();
                    LogIt::set_remove_beneficiary_special_case($beneficiary, $special_case, $batch, auth()->user());
                    $isChanged = true;
                } else {
                    unset($this->beneficiary, $this->credentials);
                }
            }

            if ($isChanged) {
                $beneficiary->updated_at = now();
                $beneficiary->save();
                LogIt::set_edit_beneficiary($beneficiary, $batch, auth()->user());
                $this->dispatch('edit-beneficiary');
            }

            $this->js('editBeneficiaryModal = false;');
            $this->resetEditBeneficiary();
        });

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

            $this->similarityResults = JaccardSimilarity::getResults($this->first_name, $this->middle_name, $this->last_name, $this->extension_name, $this->duplicationThreshold, $this->beneficiaryId);
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

                if (($result['is_perfect'] && $batch_pending && !$this->isOriginal && !$this->isSpecialCase)) {
                    $this->isSamePending = true;
                }

                # checks if this edit is an original
                if (
                    strtolower($this->beneficiary_type) === 'underemployed' &&
                    ($result['is_perfect'] || $result['coEfficient'] > $this->duplicationThreshold) &&
                    $result['beneficiary_type'] === 'special case' &&
                    strtotime($result['created_at']) > strtotime($this->beneficiary?->created_at)
                ) {
                    $this->isOriginal = true;
                    $this->isPerfectDuplicate = false;
                }
            }

            # checks if there are already more than 2 perfect duplicates and mark this editted beneficiary as `ineligible`
            if ($perfectCounter >= 2) {
                $this->isIneligible = true;
            }
        }
    }

    protected function normalizeStrings()
    {
        if (strtolower($this->middle_name) === 'n/a' || strtolower($this->middle_name) == '-' || empty($this->middle_name)) {
            $this->middle_name = null;
        }

        if (strtolower($this->extension_name) === 'n/a' || strtolower($this->extension_name) == '-' || empty($this->extension_name)) {
            $this->extension_name = null;
        }

        if (strtolower($this->e_payment_acc_num) === 'n/a' || strtolower($this->e_payment_acc_num) == '-' || empty($this->e_payment_acc_num)) {
            $this->e_payment_acc_num = null;
        }

        if (strtolower($this->skills_training) === 'n/a' || strtolower($this->skills_training) == '-' || empty($this->skills_training)) {
            $this->skills_training = null;
        }

        if (strtolower($this->spouse_first_name) === 'n/a' || strtolower($this->spouse_first_name) == '-' || empty($this->spouse_first_name)) {
            $this->spouse_first_name = null;
        }

        if (strtolower($this->spouse_middle_name) === 'n/a' || strtolower($this->spouse_middle_name) == '-' || empty($this->spouse_middle_name)) {
            $this->spouse_middle_name = null;
        }

        if (strtolower($this->spouse_last_name) === 'n/a' || strtolower($this->spouse_last_name) == '-' || empty($this->spouse_last_name)) {
            $this->spouse_last_name = null;
        }

        if (strtolower($this->spouse_extension_name) === 'n/a' || strtolower($this->spouse_extension_name) == '-' || empty($this->spouse_extension_name)) {
            $this->spouse_extension_name = null;
        }
    }

    #[On('openEdit')]
    public function setEdit()
    {
        $this->first_name = $this->beneficiary->first_name;
        $this->middle_name = $this->beneficiary->middle_name ?? null;
        $this->last_name = $this->beneficiary->last_name;
        $this->extension_name = $this->beneficiary->extension_name ?? null;
        $this->birthdate = Carbon::parse($this->beneficiary->birthdate)->format('m-d-Y');
        $this->sex = ucwords($this->beneficiary->sex);
        $this->contact_num = "0" . substr($this->beneficiary->contact_num, 3);
        $this->district = $this->beneficiary->district ?? null;
        $this->barangay_name = $this->beneficiary->barangay_name ?? null;
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

        $this->js('$wire.setAvgIncome();');

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
            'reason_image_file_path' => $this->reason_image_file_path,
            'reason_saved_image_path' => $this->reason_saved_image_path,
            'image_description' => $this->image_description
        ];

        $this->resetValidation();
        $this->nameCheck();

        $this->dispatch('init-reload')->self();
        $this->dispatch('load-id-dropzone')->self();
        $this->js('$nextTick(() => { $dispatch("load-reason") })');
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

    public function setFieldName($field_name)
    {
        if ($field_name === 'first_name') {
            $this->first_name = $this->oldValues['first_name'];
        }

        if ($field_name === 'middle_name') {
            $this->middle_name = $this->oldValues['middle_name'];
        }

        if ($field_name === 'last_name') {
            $this->last_name = $this->oldValues['last_name'];
        }

        if ($field_name === 'extension_name') {
            $this->extension_name = $this->oldValues['extension_name'];
        }

        if ($field_name === 'first_name') {
            $this->first_name = $this->oldValues['first_name'];
        }

        if ($field_name === 'beneficiary_type') {
            $this->beneficiary_type = $this->oldValues['beneficiary_type'];
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
                $this->validateOnly('birthdate');
                $choosenDate = Carbon::createFromFormat('m-d-Y', $this->birthdate)->format('Y-m-d');

                if ($this->type_of_id === 'Senior Citizen ID' && strtotime($choosenDate) > strtotime(Carbon::now()->subYears(60))) {
                    $this->type_of_id = 'Barangay ID';
                }

            } else {
                $this->birthdate = null;
            }

            $this->js('$wire.closeBirthdate();');
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

    #[Computed]
    public function credentials()
    {
        return Credential::where('beneficiaries_id', $this->beneficiaryId ? decrypt($this->beneficiaryId) : null)
            ->get();
    }

    #[Computed]
    public function implementation()
    {
        return Implementation::find($this->batch?->implementations_id);
    }

    #[Computed]
    public function beneficiary()
    {
        if ($this->beneficiaryId) {
            $beneficiary = Beneficiary::find(decrypt($this->beneficiaryId));
            return $beneficiary;
        }
    }

    #[Computed]
    public function batch()
    {
        return Batch::find($this->beneficiary?->batches_id);
    }

    # Gets all the districts (unless it's a lone district) according to the choosen city/municipality by the user
    #[Computed]
    public function districts()
    {
        return Districts::getDistricts($this->implementation?->city_municipality, $this->implementation?->province);
    }

    # this function returns all of the barangays based on the project's location
    #[Computed]
    public function barangays()
    {
        # this returns an array
        $barangays = Barangays::getBarangays($this->implementation?->city_municipality, $this->district);

        # If searchBarangay is set, filter the barangays array
        if ($this->searchBarangay) {
            $barangays = array_values(array_filter($barangays, function ($barangay) {
                return stripos($barangay, $this->searchBarangay) !== false; # Case-insensitive search
            }));
        }

        return $barangays ?? [];
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

    #[Computed]
    public function full_name($person)
    {
        $full_name = $person->first_name;

        if ($person->middle_name) {
            $full_name .= ' ' . $person->middle_name;
        }

        $full_name .= ' ' . $person->last_name;

        if ($person->extension_name) {
            $full_name .= ' ' . $person->extension_name;
        }

        return $full_name;
    }

    public function resetBarangays()
    {
        $this->reset('barangay_name');
        $this->resetValidation('barangay_name');
    }

    public function resetEditBeneficiary()
    {
        $this->resetExcept(
            'beneficiaryId',
            'duplicationThreshold',
            'maximumIncome',
        );
        $this->resetValidation();
    }

    #[Computed]
    public function settings()
    {
        $userFocal = User::find($this->implementation?->users_id);
        $settings = UserSetting::where('users_id', $userFocal?->id)
            ->pluck('value', 'key');
        return $settings;
    }

    #[Js]
    public function setAvgIncome()
    {
        return <<<'JS'
            const avgMonthyIncome = document.getElementById('edit_avg_monthly_income');
            avgMonthyIncome.value = $wire.avg_monthly_income;
        JS;
    }

    #[Js]
    public function clearAvgIncome()
    {
        return <<<'JS'
            const avgMonthyIncome = document.getElementById('edit_avg_monthly_income');
            avgMonthyIncome.value = null;
        JS;
    }

    #[Js]
    public function closeBirthdate()
    {
        return <<<'JS'
            const datepicker = FlowbiteInstances.getInstance('Datepicker', 'edit_birthdate');
            datepicker.hide();
        JS;
    }

    public function mount()
    {

    }

    public function render()
    {
        $this->dispatch('init-reload')->self();
        $this->is_sectoral = $this->batch?->is_sectoral;
        # gets the settings of the focal
        $this->duplicationThreshold = floatval($this->settings->get('duplication_threshold', config('settings.duplication_threshold'))) / 100;
        $this->maximumIncome = $this->settings->get('maximum_income', config('settings.maximum_income'));
        $this->maxDate = date('m-d-Y', strtotime(Carbon::now()->subYears(18)));
        $this->minDate = date('m-d-Y', strtotime(Carbon::now()->subYears(100)));

        return view('livewire.barangay.listing-page.edit-beneficiary-modal');
    }
}
