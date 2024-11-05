<?php

namespace App\Livewire\Focal;

use App\Models\UserSetting;
use App\Services\MoneyFormat;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.guest')]
#[Title('Settings')]
class Settings extends Component
{
    public $user_type;
    public $editMode = false;
    public $email;
    #[Validate]
    public $minimum_wage;
    #[Validate]
    public $duplication_threshold;
    #[Validate]
    public $project_number_prefix;
    #[Validate]
    public $batch_number_prefix;
    #[Validate]
    public $senior_age_threshold;
    #[Validate]
    public $maximum_income;

    public function rules()
    {

        return [
            'minimum_wage' => [
                function ($attr, $value, $fail) {
                    if (!MoneyFormat::isMaskInt($value)) {
                        $fail('The value should be an integer.');
                    } elseif (MoneyFormat::isNegative($value)) {
                        $fail('The value should be more than 0.');
                    } elseif (MoneyFormat::unmask($value) > 100000) {
                        $fail('Minimum wage shouldn\'t exceed more than ₱1k.');
                    }
                }
            ],
            'duplication_threshold' => [
                'integer',
                function ($attr, $value, $fail) {
                    if (MoneyFormat::isNegative($value)) {
                        $fail('The value should be more than 0.');
                    }
                }
            ],
        ];
    }

    public function messages()
    {
        return [
            'minimum_wage.required' => 'This field is required.',
            'duplication_threshold.required' => 'This field is required.',

            // 'minimum_wage.integer' => 'Minimum wage should be a valid number.',
            // 'duplication_threshold.integer' => 'The threshold should be a valid number.',

            // 'minimum_wage.min' => 'Value should be a valid wage.',
            // 'duplication_threshold.min' => 'Threshold should be between 1 to 100.',

            // 'duplication_threshold.max' => 'Threshold should be between 1 to 100.',
        ];
    }

    public function saveProject()
    {

    }

    public function saveBatch()
    {

    }

    public function updated($prop)
    {
        if ($prop === 'minimum_wage') {
            if (!isset($this->minimum_wage) || empty($this->minimum_wage)) {
                $this->minimum_wage = $this->settings->get('minimum_wage', config('settings.minimum_wage'));
            }
            $tempWage = MoneyFormat::unmask($this->minimum_wage);
            $this->minimum_wage = MoneyFormat::mask($tempWage);
        } elseif ($prop === 'duplication_threshold') {
            if (!isset($this->duplication_threshold) || empty($this->duplication_threshold)) {
                $this->duplication_threshold = intval($this->settings->get('duplication_threshold', config('settings.duplication_threshold')));
            }
        } elseif ($prop === 'project_number_prefix') {
            if (!isset($this->project_number_prefix) || empty($this->project_number_prefix)) {
                $this->project_number_prefix = $this->settings->get('project_number_prefix', config('settings.project_number_prefix'));
            }
        } elseif ($prop === 'batch_number_prefix') {
            if (!isset($this->batch_number_prefix) || empty($this->batch_number_prefix)) {
                $this->batch_number_prefix = $this->settings->get('batch_number_prefix', config('settings.batch_number_prefix'));
            }
        }
    }

    #[Computed]
    public function settings()
    {
        $settings = UserSetting::where('users_id', Auth::id())
            ->pluck('value', 'key');

        return $settings;
    }

    public function mount()
    {
        $user = Auth::user();
        if (Auth::user()->user_type === 'focal') {
            $this->user_type = 'focal';
        } else if ($user->user_type === 'coordinator') {
            $this->user_type = 'coordinator';
        } else {
            $this->redirectIntended();
        }

        # Profile
        $this->email = Auth::user()->email;

        # Technical

        $settings = UserSetting::where('users_id', Auth::id())
            ->pluck('value', 'key');

        $this->minimum_wage = $settings->get('minimum_wage', config('settings.minimum_wage'));
        $this->duplication_threshold = intval($settings->get('duplication_threshold', config('settings.duplication_threshold')));
        $this->project_number_prefix = $settings->get('project_number_prefix', config('settings.project_number_prefix'));
        $this->batch_number_prefix = $settings->get('batch_number_prefix', config('settings.batch_number_prefix'));
        $this->senior_age_threshold = $settings->get('senior_age_threshold', config('settings.senior_age_threshold'));
        $this->maximum_income = MoneyFormat::mask($settings->get('maximum_income', config('settings.maximum_income')));

    }
    public function render()
    {
        return view('livewire.focal.settings');
    }
}
