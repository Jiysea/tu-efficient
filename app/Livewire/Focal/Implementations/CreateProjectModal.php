<?php

namespace App\Livewire\Focal\Implementations;

use App\Models\Implementation;
use App\Models\UserSetting;
use App\Services\CitiesMunicipalities;
use App\Services\Districts;
use App\Services\MoneyFormat;
use App\Services\Provinces;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreateProjectModal extends Component
{
    protected Provinces $p;
    protected CitiesMunicipalities $c;
    protected Districts $d;

    # ----------------------------------------

    public $isAutoComputeEnabled = false;
    public $minimumWage;
    public $provinces;
    public $cities_municipalities;
    public $districts;

    # ----------------------------------------

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
    public $budget_amount;
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
                    # Check for uniqueness of the prefixed value in the database
                    $exists = DB::table('implementations')
                        ->where('project_num', $this->projectNumPrefix . $value)
                        ->exists();

                    if ($exists) {
                        # Fail the validation if the project number with the prefix already exists
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

    # a livewire action executes after clicking the `Create Project` button
    public function saveProject()
    {
        $this->validate();

        $this->project_num = $this->projectNumPrefix . $this->project_num;
        $money = new MoneyFormat();
        $this->budget_amount = $money->unmask($this->budget_amount);

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

        $this->reset(
            'project_num',
            'project_title',
            'purpose',
            'budget_amount',
            'total_slots',
            'days_of_work',
            'isAutoComputeEnabled',
        );
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

            $money = new MoneyFormat();
            $tempBudget = $money->unmask($this->budget_amount ?? '0.00');
            ($this->days_of_work === null || intval($this->days_of_work) === 0) ? $this->days_of_work = 1 : $this->days_of_work;
            // dd($this->days_of_work);
            $this->total_slots = intval($tempBudget / ($this->minimumWage * $this->days_of_work));

            $this->validateOnly('total_slots');
            $this->validateOnly('days_of_work');
        }
    }

    public function getProvinces()
    {
        $this->p = new Provinces();
        $provinces = $this->p->getProvinces(Auth::user()->regional_office);
        $this->provinces = $provinces;
    }

    public function getCitiesMunicipalities()
    {
        $this->c = new CitiesMunicipalities();
        $cities_municipalities = $this->c->getCitiesMunicipalities($this->province);
        $this->cities_municipalities = $cities_municipalities;
    }

    public function getDistricts()
    {
        $this->d = new Districts();
        $districts = $this->d->getDistricts($this->city_municipality, $this->province);
        $this->districts = $districts;
    }

    public function updatedProvince()
    {
        $this->getCitiesMunicipalities();
        $this->city_municipality = $this->cities_municipalities[0];
        $this->getDistricts();
        $this->district = $this->districts[0];

    }

    public function updatedCityMunicipality()
    {
        $this->getDistricts();
        $this->district = $this->districts[0];
    }

    public function mount()
    {
        $settings = UserSetting::where('users_id', Auth::id())
            ->pluck('value', 'key');

        $minimumWage = $settings->get('minimum_wage', config('settings.minimum_wage'));
        $this->minimumWage = intval(str_replace([',', '.'], '', number_format(floatval($minimumWage), 2)));
        $this->projectNumPrefix = $settings->get('project_number_prefix', config('settings.project_number_prefix'));

        $this->getProvinces();
        $this->province = $this->provinces[0];
        $this->getCitiesMunicipalities();
        $this->city_municipality = $this->cities_municipalities[0];
        $this->getDistricts();
        $this->district = $this->districts[0];
    }

    public function render()
    {
        return view('livewire.focal.implementations.create-project-modal');
    }
}
