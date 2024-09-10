<?php

namespace App\Livewire\Focal\Implementations;

use App\Models\Implementation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
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
        // Pre-set the project number to its set prefix.
        // $this->project_num = config('settings.project_number_prefix') . $this->project_num;

        return [
            'project_num' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    // Fetch the project number with the prefix
                    $prefixedProjectNum = config('settings.project_number_prefix') . $value;

                    // Check for uniqueness of the prefixed value in the database
                    $exists = DB::table('implementations')
                        ->where('project_num', $prefixedProjectNum)
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
            'purpose.required' => 'Please select a :attribute .',
            'district.required' => 'The :attribute should not be empty.',
            'province.required' => 'The :attribute should not be empty.',
            'city_municipality.required' => 'The :attribute should not be empty.',
            'budget_amount.required' => 'The :attribute should not be empty.',
            'total_slots.required' => 'The :attribute should not be empty.',
            'days_of_work.required' => 'Invalid :attribute.',

            'project_num.unique' => 'This :attribute already exists.',
            'project_num.integer' => 'The :attribute should be a valid number.',

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

        // $this->project_num = config('settings.project_number_prefix') . $this->project_num;
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

    #[Computed]
    public function getProvince()
    {
        $province = null;
        if (Auth::user()->regional_office === 'Region XI') {
            $province = [
                'Davao del Sur',
                'Davao de Oro',
                'Davao del Norte',
                'Davao Oriental',
                'Davao Occidental',
            ];
        }
        $this->province = $province[0];
        return $province;
    }

    #[Computed]
    public function getCityMunicipality()
    {
        $city_municipality = null;
        if ($this->province === 'Davao del Sur') {
            $city_municipality = [
                'Davao City',
                'Bansalan',
                'Digos City',
                'Hagonoy',
                'Kiblawan',
                'Magsaysay',
                'Malalag',
                'Matanao',
                'Padada',
                'Santa Cruz',
            ];
        } else if ($this->province === 'Davao del Norte') {
            $city_municipality = [
                'Asuncion',
                'Braulio E. Dujali',
                'Carmen',
                'Kapalong',
                'New Corella',
                'Panabo',
                'Samal',
                'San Isidro',
                'Santo Tomas',
                'Tagum',
                'Talaingod'
            ];
        } else if ($this->province === 'Davao de Oro') {
            $city_municipality = [
                'Compostela',
                'Laak',
                'Mabini',
                'Maco',
                'Maragusan',
                'Mawab',
                'Monkayo',
                'Montevista',
                'Nabunturan',
                'New Bataan',
                'Pantukan'
            ];
        } else if ($this->province === 'Davao Occidental') {
            $city_municipality = [
                'Don Marcelino',
                'Jose Abad Santos',
                'Malita',
                'Santa Maria',
                'Sarangani'
            ];
        } else if ($this->province === 'Davao Oriental') {
            $city_municipality = [
                'Baganga',
                'Banaybanay',
                'Boston',
                'Caraga',
                'Cateel',
                'Governor Generoso',
                'Lupon',
                'Manay',
                'Mati',
                'San Isidro',
                'Tarragona'
            ];
        }

        $this->city_municipality = $city_municipality[0];
        return $city_municipality;
    }

    #[Computed]
    public function getDistrict()
    {
        $district = null;
        if ($this->city_municipality === 'Davao City') {
            $district = [
                '1st District',
                '2nd District',
                '3rd District',
            ];
        } else {
            $district = [
                'To be continued...',
            ];
        }

        $this->district = $district[0];
        return $district;
    }


    public function render()
    {

        return view('livewire.focal.implementations.create-project-modal');
    }
}
