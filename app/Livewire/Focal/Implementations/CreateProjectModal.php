<?php

namespace App\Livewire\Focal\Implementations;

use App\Models\Implementation;
use App\Models\UserSetting;
use App\Services\CitiesMunicipalities;
use App\Services\LogIt;
use App\Services\MoneyFormat;
use App\Services\Provinces;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Js;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreateProjectModal extends Component
{
    # ----------------------------------------
    public $defaultMinimumWage;
    public $projectNumPrefix;
    public $isAutoComputeEnabled = false;
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
    public $budget_amount;
    #[Validate]
    public $minimum_wage;
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

    protected function generateProjectNum()
    {
        $code = null;
        do {
            $code = '';
            for ($a = 0; $a < 6; $a++) {
                $code .= fake()->randomElement(['#']);
            }

            $this->project_num = fake()->bothify($code);

        } while (Implementation::where('project_num', $this->projectNumPrefix . $this->project_num)->exists());

    }

    public function regenerateProjectNum()
    {
        $this->generateProjectNum();
    }

    public function setLocationFields()
    {
        if (strtolower(Auth::user()->field_office) === 'City of Davao') {
            $this->province = 'Davao del Sur';
            $this->city_municipality = 'City of Davao';
        }
    }

    # a livewire action executes after clicking the `Create Project` button
    public function saveProject()
    {
        $this->validate();

        DB::transaction(function () {
            try {
                $this->project_num = $this->projectNumPrefix . now()->format('Y-') . $this->project_num;
                $this->budget_amount = MoneyFormat::unmask($this->budget_amount);
                $this->minimum_wage = MoneyFormat::unmask($this->minimum_wage);

                $implementation = Implementation::create([
                    'users_id' => Auth()->id(),
                    'project_num' => $this->project_num,
                    'project_title' => $this->project_title,
                    'purpose' => $this->purpose,
                    'province' => $this->province,
                    'city_municipality' => $this->city_municipality,
                    'budget_amount' => $this->budget_amount,
                    'minimum_wage' => $this->minimum_wage,
                    'total_slots' => $this->total_slots,
                    'days_of_work' => $this->days_of_work,
                    'status' => 'pending',
                ]);

                LogIt::set_create_project($implementation, auth()->user());
                $this->dispatch('alertNotification', type: 'implementation', message: 'Successfully created a project', color: 'indigo');

            } catch (\Throwable $e) {
                DB::rollBack();
                LogIt::set_log_exception('An error has occured during execution. Error ' . $e->getCode(), auth()->user(), $e->getTrace());
                $this->dispatch('alertNotification', type: 'implementation', message: 'An error has occured during execution. Error ' . $e->getCode(), color: 'red');
            } finally {
                $this->resetProject();
                $this->js('createProjectModal = false;');
            }
        }, 5);
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
            $tempWage = MoneyFormat::unmask(isset($this->minimum_wage) && !empty($this->minimum_wage) ? $this->minimum_wage : $this->defaultMinimumWage);

            ($this->days_of_work === null || intval($this->days_of_work) === 0) ? $this->days_of_work = 10 : $this->days_of_work;
            $this->total_slots = intval($tempBudget / ($tempWage * $this->days_of_work));

            $this->validate();
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

    public function updatedProvince()
    {
        $this->city_municipality = $this->cities_municipalities[0];
    }

    public function resetProject()
    {
        $this->reset();
        $this->js('$wire.resetBudget();');
        $this->resetValidation();
        $this->generateProjectNum();
        $this->province = $this->provinces[0];
        $this->city_municipality = $this->cities_municipalities[0];
    }

    #[Computed]
    public function settings()
    {
        return UserSetting::where('users_id', Auth::id())
            ->pluck('value', 'key');
    }

    #[Js]
    public function resetBudget()
    {
        return <<<JS
            const budget = document.getElementById('budget_amount');
            budget.value = null;
        JS;
    }

    public function mount()
    {
        $this->province = $this->provinces[0];
        $this->city_municipality = $this->cities_municipalities[0];

        $this->generateProjectNum();
        $this->setLocationFields();
    }

    public function render()
    {
        $this->defaultMinimumWage = $this->settings->get('minimum_wage', config('settings.minimum_wage'));
        $this->projectNumPrefix = $this->settings->get('project_number_prefix', config('settings.project_number_prefix'));

        if (is_null($this->minimum_wage)) {
            $this->minimum_wage = $this->defaultMinimumWage;
            $this->resetValidation('minimum_wage');
        }

        return view('livewire.focal.implementations.create-project-modal');
    }
}
