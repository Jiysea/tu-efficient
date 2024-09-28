<?php

namespace App\Livewire\Focal\Implementations;

use App\Models\Batch;
use App\Models\Implementation;
use App\Models\UserSetting;
use App\Services\CitiesMunicipalities;
use App\Services\Districts;
use App\Services\MoneyFormat;
use App\Services\Provinces;
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
    public $edit = false;
    public $isEmpty = true;
    public $deleteProjectModal = false;
    public $isAutoComputeEnabled = false;
    public $minimumWage;

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
                # Checks if the number is a valid number
                function ($attribute, $value, $fail) {
                    $money = new MoneyFormat();
                    // dump($value);
                    $number = $money->isMaskInt($value);

                    if (!$number) {

                        $fail('The :attribute should be a valid amount.');
                    }
                },
                # Checks if the number is less than 1
                function ($attribute, $value, $fail) {
                    $money = new MoneyFormat();
                    $negative = $money->isNegative($value);

                    if ($negative) {
                        $fail('The :attribute value should be more than 1.');
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
            'project_num.required' => 'The :attribute should not be empty.',
            'purpose.required' => 'Please select a :attribute .',
            'district.required' => 'The :attribute should not be empty.',
            'province.required' => 'The :attribute should not be empty.',
            'city_municipality.required' => 'The :attribute should not be empty.',
            'budget_amount.required' => 'The :attribute should not be empty.',
            'total_slots.required' => 'The :attribute should not be empty.',
            'days_of_work.required' => 'Invalid :attribute.',

            'project_num.unique' => 'This :attribute already exists.',
            'project_num.integer' => 'The :attribute should be a valid number.',

            'total_slots.integer' => 'The :attribute should be a valid number.',
            'total_slots.min' => 'The :attribute value should be more than 1.',

            'days_of_work.integer' => 'The :attribute should be a valid number.',
            'days_of_work.min' => 'The :attribute value should be more than 1.',
        ];
    }

    # Validation attribute names for human readability purpose
    # for example: The project_num should not be empty.
    # instead of that: The project number should not be empty.
    public function validationAttributes()
    {
        return [
            'project_num' => 'project number',
            'purpose' => 'purpose',
            'district' => 'district',
            'province' => 'province',
            'city_municipality' => 'city or municipality',
            'budget_amount' => 'budget',
            'total_slots' => 'slots',
            'days_of_work' => 'days of work',
        ];
    }

    #[Computed]
    public function implementation()
    {
        $implementation = Implementation::find(decrypt($this->passedProjectId));
        return $implementation;
    }

    # Checks if there are any existing batches row created that associates with this project
    public function checkEmpty()
    {
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
        if ($this->edit) {
            $this->city_municipality = $this->cities_municipalities[0];
            $this->district = $this->districts[0];
        }
    }

    # The `updated` hook by Livewire for `reactively` changing the elements according to the choosen city/municipality
    public function updatedCityMunicipality()
    {
        if ($this->edit) {
            $this->district = $this->districts[0];
        }
    }

    # It updates the project / saves the changes after the editing
    # Also disallows edits when there are any batches associated with this implementation project
    public function saveProject()
    {
        $this->validate();
        $this->project_num = $this->projectNumPrefix . $this->project_num;

        $money = new MoneyFormat();
        $this->budget_amount = $money->unmask($this->budget_amount);

        Implementation::where('id', decrypt($this->passedProjectId))
            ->where('users_id', Auth::id())
            ->update([
                'project_num' => $this->project_num,
                'project_title' => $this->project_title,
                'budget_amount' => $this->budget_amount,
                'total_slots' => $this->total_slots,
                'days_of_work' => $this->days_of_work,
                'province' => $this->province,
                'city_municipality' => $this->city_municipality,
                'district' => $this->district,
                'purpose' => $this->purpose
            ]);

        // unset($this->implementation);
        $this->toggleEdit();
        $this->dispatch('edit-implementations');
    }

    public function deleteProject()
    {
        $project = Implementation::find(decrypt($this->passedProjectId));
        $this->authorize('delete-implementation', $project);
        $project->delete();

        $this->edit = false;
        $this->resetViewProject();
        $this->dispatch('delete-implementations');
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
            $money = new MoneyFormat();
            $tempBudget = $money->unmask($this->budget_amount);
            ($this->days_of_work === null || intval($this->days_of_work) === 0) ? $this->days_of_work = 1 : $this->days_of_work;
            $this->total_slots = intval($tempBudget / ($this->minimumWage * $this->days_of_work));

            $this->validateOnly('total_slots');
            $this->validateOnly('days_of_work');
        }
    }

    public function toggleEdit()
    {
        $this->edit = !$this->edit;

        if ($this->edit) {
            $money = new MoneyFormat();
            $this->project_num = intval(substr($this->implementation->project_num, strlen($this->projectNumPrefix)));
            $this->province = $this->implementation->province;
            $this->city_municipality = $this->implementation->city_municipality;
            $this->district = $this->implementation->district;
            $this->budget_amount = $money->mask($this->implementation->budget_amount);
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
        if ($this->edit) {
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
                'edit',
            );
        }
    }

    public function render()
    {
        # Check if there's no batches made with this project yet
        $this->checkEmpty();
        $settings = UserSetting::where('users_id', Auth::id())
            ->pluck('value', 'key');

        $minimumWage = $settings->get('minimum_wage', config('settings.minimum_wage'));
        $this->minimumWage = intval(str_replace([',', '.'], '', number_format(floatval($minimumWage), 2)));
        $this->projectNumPrefix = $settings->get('project_number_prefix', config('settings.project_number_prefix'));

        return view('livewire.focal.implementations.view-project');
    }
}
