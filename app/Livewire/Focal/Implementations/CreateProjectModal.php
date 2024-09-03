<?php

namespace App\Livewire\Focal\Implementations;

use App\Models\Implementation;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Js;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreateProjectModal extends Component
{
    public $isAutoComputeEnabled = false;
    public $minimumWage;
    public $previousTotalSlots;
    public $previousDaysOfWork;
    public $budgetToFloat;
    public $budgetToInt;
    #[Validate]
    public $project_num;
    #[Validate]
    public $project_title;
    #[Validate]
    public $purpose;
    #[Validate]
    public $district;
    #[Validate]
    public $province;
    #[Validate]
    public $city_municipality;
    #[Validate]
    public $budget_amount;
    #[Validate]
    public $total_slots;
    #[Validate]
    public $days_of_work;

    # Runs real-time depending on wire:model suffix
    public function rules()
    {
        // if (!empty($value)) {
        //     $this->budgetToFloat = str_replace([','], '', $value);

        //     # Doesn't matter if the budget amount has a decimal or not
        //     # it will always be an integer
        //     # so that it can adapt dynamically based on the minimum wage if it has decimal too or not
        //     if (strpos($this->budgetToFloat, '.') !== false) {
        //         $this->budgetToInt = intval(str_replace(['.'], '', number_format(floatval($this->budgetToFloat), 2)));
        //     } else {
        //         $this->budgetToInt = intval(strval($this->budgetToFloat) . '00');
        //     }

        // } else {
        //     $this->budgetToInt = null;
        // }
        // $this->toggleTry();

        return [
            'project_num' => 'required|unique:implementations',
            'project_title' => 'required',
            'purpose' => 'required',
            'district' => 'required',
            'province' => 'required',
            'city_municipality' => 'required',
            'budget_amount' => 'required|integer|min:1',
            'total_slots' => 'required|integer|min:1',
            'days_of_work' => 'required|integer|min:1',
        ];
    }

    # The validation error messages
    public function messages()
    {
        return [
            'project_num.required' => 'The :attribute should not be empty.',
            'project_title.required' => 'The :attribute should not be empty.',
            'purpose.required' => 'Please select a :attribute .',
            'district.required' => 'The :attribute should not be empty.',
            'province.required' => 'The :attribute should not be empty.',
            'city_municipality.required' => 'The :attribute should not be empty.',
            'budget_amount.required' => 'The :attribute should not be empty.',
            'total_slots.required' => 'The :attribute should not be empty.',
            'days_of_work.required' => 'Invalid :attribute.',

            'project_num.unique' => 'This :attribute already exists.',

            'budget_amount.integer' => 'The :attribute should be a valid amount.',
            'budget_amount.min' => 'The :attribute value should be more than 1.',

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
            'project_title' => 'project title',
            'purpose' => 'purpose',
            'district' => 'district',
            'province' => 'province',
            'city_municipality' => 'city or municipality',
            'budget_amount' => 'budget',
            'total_slots' => 'slots',
            'days_of_work' => 'days of work',
        ];
    }

    # a livewire action executes after clicking the `Create Project` button
    public function saveProject()
    {
        $this->validate();

        Implementation::create([
            'users_id' => Auth()->id(),
            'project_num' => $this->project_num,
            'project_title' => $this->project_title,
            'purpose' => $this->purpose,
            'district' => $this->district,
            'province' => $this->province,
            'city_municipality' => $this->city_municipality,
            'budget_amount' => $this->budget_amount,
            'total_slots' => $this->total_slots,
            'days_of_work' => $this->days_of_work
        ]);

        $this->reset();
        $this->dispatch('update-implementations');

    }

    # a livewire action for toggling the auto computation for total slots
    public function toggleTry()
    {
        # checks if the toggle (checkbox) is on/true OR off/false
        if ($this->isAutoComputeEnabled) {

            # The minimum wage value is a global variable located in the .env file
            # So if you want to change it, change it there manually
            # Also logically, real-life money has only 2 digits below a ph peso
            # So it doesn't matter how many decimal digits it has, it will
            # always be formatted to 2 simple digits (rounded off)
            $this->minimumWage = intval(str_replace([',', '.'], '', number_format(floatval(config('settings.minimum_wage')), 2)));

            ($this->days_of_work === null || intval($this->days_of_work) === 0) ? $this->days_of_work = 1 : $this->days_of_work;
            // dd($this->days_of_work);
            $this->total_slots = intval($this->budget_amount / ($this->minimumWage * $this->days_of_work));

            $this->validateOnly('total_slots');
            $this->validateOnly('days_of_work');
        } else {
            //
        }
    }

    public function render()
    {
        return view('livewire.focal.implementations.create-project-modal');
    }
}
