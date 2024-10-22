<?php

namespace App\Livewire\Focal\Implementations;

use App\Models\Batch;
use App\Models\Implementation;
use App\Models\UserSetting;
use App\Services\CitiesMunicipalities;
use App\Services\Districts;
use App\Services\MoneyFormat;
use App\Services\Provinces;
use Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Reactive;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ViewProject extends Component
{
    #[Reactive]
    #[Locked]
    public $passedProjectId;

    # ---------------------------------
    public $editMode = false;
    public $isEmpty;
    public $isApproved;
    public $deleteProjectModal = false;
    public $isAutoComputeEnabled = false;
    public $defaultMinimumWage;

    # ---------------------------------

    public $projectNumPrefix;
    #[Validate]
    public $project_num;
    #[Validate]
    public $project_title;
    #[Validate]
    public $purpose;
    #[Validate]
    public $province;
    #[Validate]
    public $city_municipality;
    #[Validate]
    public $district;
    #[Validate]
    public string $budget_amount;
    #[Validate]
    public string $minimum_wage;
    #[Validate]
    public $total_slots;
    #[Validate]
    public $days_of_work;

    # Runs real-time depending on wire:model suffix
    public function rules()
    {
        return [
            'project_num' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    // Check for uniqueness of the prefixed value in the database
                    $exists = DB::table('implementations')
                        ->where('project_num', $this->projectNumPrefix . $value)
                        ->whereNotIn('id', [decrypt($this->passedProjectId)])
                        ->exists();

                    if ($exists) {
                        // Fail the validation if the project number with the prefix already exists
                        $fail('This :attribute already exists.');
                    }
                },
            ],
            'project_title' => 'nullable',
            'purpose' => 'required',
            'district' => 'required',
            'province' => 'required',
            'city_municipality' => 'required',
            'budget_amount' => [
                'required',

                function ($attribute, $value, $fail) {

                    # Checks if the number is a valid number
                    if (!MoneyFormat::isMaskInt($value)) {

                        $fail('The should be a valid amount.');
                    }

                    # Checks if the number is a negative
                    elseif (MoneyFormat::isNegative($value)) {
                        $fail('The value should be nonnegative.');
                    }
                    # Checks if the number is less than the minimum wage
                    elseif ($this->minimum_wage) {
                        if (MoneyFormat::unmask($value ?? 0) < MoneyFormat::unmask($this->minimum_wage)) {
                            $fail('The value should be > ₱' . MoneyFormat::mask($this->minimum_wage) . '.');
                        }
                    }
                },
            ],
            'minimum_wage' => [
                'required',
                function ($attribute, $value, $fail) {

                    # Checks if the number is a valid number
                    if (!MoneyFormat::isMaskInt($value)) {

                        $fail('The value should be a valid amount.');
                    }

                    # Checks if the number is a negative
                    elseif (MoneyFormat::isNegative($value)) {
                        $fail('The value should be nonnegative.');
                    }
                },
            ],
            'total_slots' => 'required|integer|min:1',
            'days_of_work' => 'required|integer|min:1',
        ];
    }

    # The validation error messages
    public function messages()
    {
        return [
            'project_num.required' => 'This field is required.',
            'purpose.required' => 'Please select a purpose.',
            'district.required' => 'This field is required.',
            'province.required' => 'This field is required.',
            'city_municipality.required' => 'This field is required.',
            'budget_amount.required' => 'This field is required.',
            'minimum_wage.required' => 'This field is required.',
            'total_slots.required' => 'This field is required.',
            'days_of_work.required' => 'This field is required.',
            'project_num.integer' => 'Project Number should be a number.',
            'total_slots.integer' => 'Total Slots should be a number.',
            'total_slots.min' => 'Total Slots should be > 0.',
            'days_of_work.integer' => 'Days should be a number.',
            'days_of_work.min' => 'Days should be > 0.',
        ];
    }

    #[Computed]
    public function implementation()
    {
        $implementation = Implementation::find($this->passedProjectId ? decrypt($this->passedProjectId) : null);
        return $implementation;

    }

    # Gets all the provinces according to the authenticated user's (focal) regional office
    #[Computed]
    public function provinces()
    {
        $p = new Provinces();
        return $p->getProvinces(Auth::user()->regional_office);
    }

    # Gets all the cities/municipalities according to the choosen province by the user
    #[Computed]
    public function cities_municipalities()
    {
        $c = new CitiesMunicipalities();
        return $c->getCitiesMunicipalities($this->province);
    }

    # Gets all the districts (unless it's a lone district) according to the choosen city/municipality by the user
    #[Computed]
    public function districts()
    {
        $d = new Districts();
        return $d->getDistricts($this->city_municipality, $this->province);
    }

    # The `updated` hook by Livewire for `reactively` changing the elements according to the choosen province
    public function updatedProvince()
    {
        if ($this->editMode) {
            $this->city_municipality = $this->cities_municipalities[0];
            $this->district = $this->districts[0];
        }
    }

    # The `updated` hook by Livewire for `reactively` changing the elements according to the choosen city/municipality
    public function updatedCityMunicipality()
    {
        if ($this->editMode) {
            $this->district = $this->districts[0];
        }
    }

    # It updates the project / saves the changes after the editing
    # Also disallows edits when there are any batches associated with this implementation project
    public function editProject()
    {
        $this->validate(
            [
                'project_num' => [
                    'required',
                    'integer',
                    function ($attribute, $value, $fail) {
                        // Check for uniqueness of the prefixed value in the database
                        $exists = DB::table('implementations')
                            ->where('project_num', $this->projectNumPrefix . $value)
                            ->whereNotIn('id', [decrypt($this->passedProjectId)])
                            ->exists();

                        if ($exists) {
                            // Fail the validation if the project number with the prefix already exists
                            $fail('This :attribute already exists.');
                        }
                    },
                ],
                'project_title' => 'nullable',
                'purpose' => 'required',
                'district' => 'required',
                'province' => 'required',
                'city_municipality' => 'required',
                'budget_amount' => [
                    'required',

                    function ($attribute, $value, $fail) {

                        # Checks if the number is a valid number
                        if (!MoneyFormat::isMaskInt($value)) {

                            $fail('The should be a valid amount.');
                        }

                        # Checks if the number is a negative
                        elseif (MoneyFormat::isNegative($value)) {
                            $fail('The value should be nonnegative.');
                        }
                        # Checks if the number is less than the minimum wage
                        elseif ($this->minimum_wage) {
                            if (MoneyFormat::unmask($value ?? 0) < MoneyFormat::unmask($this->minimum_wage)) {
                                $fail('The value should be > ₱' . MoneyFormat::mask($this->minimum_wage) . '.');
                            }
                        }
                    },
                ],
                'minimum_wage' => [
                    'required',
                    function ($attribute, $value, $fail) {

                        # Checks if the number is a valid number
                        if (!MoneyFormat::isMaskInt($value)) {

                            $fail('The value should be a valid amount.');
                        }

                        # Checks if the number is a negative
                        elseif (MoneyFormat::isNegative($value)) {
                            $fail('The value should be nonnegative.');
                        }
                    },
                ],
                'total_slots' => 'required|integer|min:1',
                'days_of_work' => 'required|integer|min:1',
            ],
            [
                'project_num.required' => 'This field is required.',
                'purpose.required' => 'Please select a purpose.',
                'district.required' => 'This field is required.',
                'province.required' => 'This field is required.',
                'city_municipality.required' => 'This field is required.',
                'budget_amount.required' => 'This field is required.',
                'minimum_wage.required' => 'This field is required.',
                'total_slots.required' => 'This field is required.',
                'days_of_work.required' => 'This field is required.',
                'project_num.integer' => 'Project Number should be a number.',
                'total_slots.integer' => 'Total Slots should be a number.',
                'total_slots.min' => 'Total Slots should be > 0.',
                'days_of_work.integer' => 'Days should be a number.',
                'days_of_work.min' => 'Days should be > 0.',
            ],
        );
        $this->project_num = $this->projectNumPrefix . $this->project_num;

        $this->budget_amount = MoneyFormat::unmask($this->budget_amount);
        $this->minimum_wage = MoneyFormat::unmask($this->minimum_wage);

        Implementation::where('id', decrypt($this->passedProjectId))
            ->where('users_id', Auth::id())
            ->update([
                'project_num' => $this->project_num,
                'project_title' => $this->project_title,
                'budget_amount' => $this->budget_amount,
                'minimum_wage' => $this->minimum_wage,
                'total_slots' => $this->total_slots,
                'days_of_work' => $this->days_of_work,
                'province' => $this->province,
                'city_municipality' => $this->city_municipality,
                'district' => $this->district,
                'purpose' => $this->purpose
            ]);

        $this->toggleEdit();
        $this->dispatch('edit-project');
    }

    public function deleteProject()
    {
        $project = Implementation::find(decrypt($this->passedProjectId));
        $this->authorize('delete-implementation-focal', $project);
        $project->delete();

        $this->editMode = false;
        $this->dispatch('delete-project');
        $this->resetViewProject();
    }

    # a livewire action for toggling the auto computation for total slots
    public function autoCompute()
    {
        # checks if the toggle (checkbox) is on/true OR off/false
        if ($this->isAutoComputeEnabled) {

            # The minimum wage value is a global variable located in the .env file
            # So if you want to change it, change it there manually
            # Also logically, real-life money has only 2 digits below a ph peso
            # So it doesn't matter how many decimal digits it has, it will
            # always be formatted to 2 simple digits (rounded off)

            $tempBudget = MoneyFormat::unmask($this->budget_amount ?? '0.00');
            $tempWage = MoneyFormat::unmask($this->minimum_wage ?? $this->defaultMinimumWage);

            ($this->days_of_work === null || intval($this->days_of_work) === 0) ? $this->days_of_work = 10 : $this->days_of_work;
            $this->total_slots = intval($tempBudget / ($tempWage * $this->days_of_work));

            $this->validateOnly('days_of_work');
            $this->validateOnly('total_slots');
        }
    }

    public function toggleEdit()
    {
        $this->editMode = !$this->editMode;

        if ($this->editMode) {
            $this->project_num = intval(substr($this->implementation->project_num, strlen($this->projectNumPrefix)));
            $this->province = $this->implementation->province;
            $this->city_municipality = $this->implementation->city_municipality;
            $this->district = $this->implementation->district;
            $this->budget_amount = MoneyFormat::mask($this->implementation->budget_amount);
            $this->minimum_wage = MoneyFormat::mask($this->implementation->minimum_wage);
            $this->total_slots = $this->implementation->total_slots;
            $this->days_of_work = $this->implementation->days_of_work;

            # then initialize on fields that are independent regardless if there's batches or not
            $this->project_title = $this->implementation->project_title;
            $this->purpose = $this->implementation->purpose;

        } else {
            $this->reset(
                'project_num',
                'project_title',
                'purpose',
                'province',
                'city_municipality',
                'district',
                'budget_amount',
                'total_slots',
                'days_of_work',
                'isAutoComputeEnabled',
            );
        }
    }

    public function resetViewProject()
    {
        $this->reset(
            'project_num',
            'project_title',
            'purpose',
            'province',
            'city_municipality',
            'district',
            'budget_amount',
            'total_slots',
            'days_of_work',
            'isAutoComputeEnabled',
            'editMode',
            'deleteProjectModal'
        );

        $this->resetValidation();

    }

    public function checkApproved()
    {
        if ($this->passedProjectId) {
            $query = Batch::where('implementations_id', decrypt($this->passedProjectId))
                ->where('approval_status', 'approved')
                ->exists();

            # If there's any rows that exists...
            if ($query) {
                # then it's not empty
                $this->isApproved = true;
            } else {
                # otherwise, it is empty.
                $this->isApproved = false;
            }
        }
    }

    # Checks if there are any existing batches row created that associates with this project
    public function checkEmpty()
    {
        if ($this->passedProjectId) {
            $query = Batch::where('implementations_id', decrypt($this->passedProjectId))
                ->exists();

            # If there's any rows that exists...
            if ($query) {
                # then it's not empty
                $this->isEmpty = false;
            } else {
                # otherwise, it is empty.
                $this->isEmpty = true;
            }
        }
    }

    public function mount()
    {
        $settings = UserSetting::where('users_id', Auth::id())
            ->pluck('value', 'key');

        $this->defaultMinimumWage = $settings->get('minimum_wage', config('settings.minimum_wage'));
        $this->projectNumPrefix = $settings->get('project_number_prefix', config('settings.project_number_prefix'));
    }

    public function render()
    {
        # Check if there's no batches made with this project yet
        $this->checkEmpty();
        $this->checkApproved();


        return view('livewire.focal.implementations.view-project');
    }
}
