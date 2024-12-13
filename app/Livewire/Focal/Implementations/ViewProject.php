<?php

namespace App\Livewire\Focal\Implementations;

use App\Models\Batch;
use App\Models\Implementation;
use App\Models\UserSetting;
use App\Services\CitiesMunicipalities;
use App\Services\LogIt;
use App\Services\MoneyFormat;
use App\Services\Provinces;
use Carbon\Carbon;
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
    public $deleteProjectModal = false;
    public $isAutoComputeEnabled = false;
    public $projectNumPrefix;
    public $defaultMinimumWage;

    # ---------------------------------

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
                function ($attribute, $value, $fail) {
                    # Check for uniqueness of the prefixed value in the database
                    $exists = DB::table('implementations')
                        ->where('project_num', $this->projectNumPrefix . $value)
                        ->exists();

                    if ($exists) {
                        # Fail the validation if the project number with the prefix already exists
                        $fail('This project number already exists. Refresh to regenerate.');
                    }
                },
            ],
            'project_title' => 'nullable',
            'purpose' => 'required',
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
            'total_slots' => [
                'required',
                'integer',
                function ($a, $value, $fail) {
                    if (!isset($this->budget_amount) || empty($this->budget_amount)) {
                        $fail('Need to add a budget amount.');
                    }
                },
                'min:1',
            ],
            'days_of_work' => 'required|integer|min:1',
        ];
    }

    # The validation error messages
    public function messages()
    {
        return [
            'project_num.required' => 'This field is required.',
            'purpose.required' => 'Please select a purpose.',
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

    # The `updated` hook by Livewire for `reactively` changing the elements according to the choosen province
    public function updatedProvince()
    {
        if ($this->editMode) {
            $this->city_municipality = $this->cities_municipalities[0];
        }
    }

    # It updates the project / saves the changes after the editing
    # Also disallows edits when there are any batches associated with this implementation project
    public function editProject()
    {
        $this->validate();

        DB::transaction(function () {

            $implementation = Implementation::lockForUpdate()->find(decrypt($this->passedProjectId));

            $this->authorize('edit-implementation-focal', $implementation);

            $this->project_num = $this->projectNumPrefix . Carbon::parse($this->implementation->created_at)->format('Y-') . $this->project_num;
            $this->budget_amount = MoneyFormat::unmask($this->budget_amount);
            $this->minimum_wage = MoneyFormat::unmask($this->minimum_wage);

            $implementation->project_num = $this->project_num;
            $implementation->project_title = $this->project_title;
            $implementation->budget_amount = $this->budget_amount;
            $implementation->minimum_wage = $this->minimum_wage;
            $implementation->total_slots = $this->total_slots;
            $implementation->days_of_work = $this->days_of_work;
            $implementation->province = $this->province;
            $implementation->city_municipality = $this->city_municipality;
            $implementation->purpose = $this->purpose;

            $this->toggleEdit();

            if ($implementation->isDirty()) {
                $implementation->save();
                LogIt::set_edit_project($implementation, auth()->user());
                $this->dispatch('edit-project');
            }
        });
    }

    public function deleteProject()
    {
        $project = Implementation::find(decrypt($this->passedProjectId));
        $this->authorize('delete-implementation-focal', $project);
        $project->delete();

        $this->resetViewProject();
        LogIt::set_delete_project($project, auth()->user());
        $this->js('viewProjectModal = false;');
        $this->dispatch('delete-project');
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
            $this->project_num = intval(substr($this->implementation->project_num, -6));
            $this->province = $this->implementation->province;
            $this->city_municipality = $this->implementation->city_municipality;
            $this->budget_amount = MoneyFormat::mask($this->implementation->budget_amount);
            $this->minimum_wage = MoneyFormat::mask($this->implementation->minimum_wage);
            $this->total_slots = $this->implementation->total_slots;
            $this->days_of_work = $this->implementation->days_of_work;

            # then initialize on fields that are independent regardless if there's batches or not
            $this->project_title = $this->implementation->project_title;
            $this->purpose = $this->implementation->purpose;

        } else {
            $this->resetViewProject();
        }
    }

    public function resetViewProject()
    {
        $this->resetExcept('passedProjectId', 'projectNumPrefix', 'defaultMinimumWage', );
        $this->resetValidation();
    }

    #[Computed]
    public function isConcluded()
    {
        $implementation = Implementation::find($this->passedProjectId ? decrypt($this->passedProjectId) : null);

        # If the implementation project has already concluded...
        if ($implementation?->status === 'concluded') {
            # then it is concluded
            return true;
        }
        # otherwise, it is not yet concluded.
        return false;
    }

    # Checks if there are any existing batches row created that associates with this project
    #[Computed]
    public function isEmpty()
    {
        $query = Batch::where('implementations_id', $this->passedProjectId ? decrypt($this->passedProjectId) : null)
            ->exists();

        # If there's any rows that exists...
        if ($query) {
            # then it's not empty
            return false;
        }

        # otherwise, it is empty.
        return true;
    }

    #[Computed]
    public function settings()
    {
        return UserSetting::where('users_id', Auth::id())
            ->pluck('value', 'key');
    }

    public function mount()
    {

    }

    public function render()
    {
        $this->defaultMinimumWage = $this->settings->get('minimum_wage', config('settings.minimum_wage'));
        $this->projectNumPrefix = $this->settings->get('project_number_prefix', config('settings.project_number_prefix'));

        return view('livewire.focal.implementations.view-project');
    }
}
