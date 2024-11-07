<?php

namespace App\Livewire\Focal;

use App\Models\User;
use App\Models\UserSetting;
use App\Services\MoneyFormat;
use Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
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
    public $change_password = false;
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
    #[Validate]
    public $password;
    #[Validate]
    public $password_confirmation;

    public function rules()
    {

        return [
            'minimum_wage' => [
                'required',
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
                'required',
                'integer',
                function ($attr, $value, $fail) {
                    if (MoneyFormat::isNegative($value)) {
                        $fail('The value should be nonnegative.');
                    } elseif ($value > 100) {
                        $fail('The value should not exceed 100.');
                    } elseif ($value < 1) {
                        $fail('The value should be more than 1.');
                    }
                }
            ],
            'project_number_prefix' => [
                'required'
            ],
            'batch_number_prefix' => [
                'required'
            ],
            'maximum_income' => [
                'required',
                function ($attr, $value, $fail) {
                    if (!MoneyFormat::isMaskInt($value)) {
                        $fail('The value should be an integer.');
                    } elseif (MoneyFormat::isNegative($value)) {
                        $fail('The value should be more than 0.');
                    }
                }
            ],
            'password' => [
                'required',
                Password::min(8)->uncompromised()->mixedCase()->numbers()->symbols(),
                function ($attr, $value, $fail) {
                    if (Hash::check($value, auth()->user()->getAuthPassword())) {
                        $fail('You are using your old password.');
                    }
                }
            ],
            'password_confirmation' => 'required|same:password',
        ];
    }

    public function messages()
    {
        return [
            'minimum_wage.required' => 'This field is required.',
            'duplication_threshold.required' => 'This field is required.',
            'project_number_prefix.required' => 'This field is required.',
            'batch_number_prefix.required' => 'This field is required.',
            'maximum_income.required' => 'This field is required.',

            'duplication_threshold.integer' => 'The value should be an integer.',

            'password.required' => 'Password is required.',
            'password.min' => 'Need at least 8 characters.',
            'password.uncompromised' => 'Please try a different :attribute.',
            'password.mixed' => 'Need at least 1 uppercase letter.',
            'password.numbers' => 'Need at least 1 number.',
            'password.symbols' => 'Need at least 1 symbol.',

            'password_confirmation.required' => 'This field is required.',
            'password_confirmation.same' => 'Passwords do not match.',
        ];
    }

    public function changePassword()
    {
        $this->validate(
            [
                'password' => [
                    'required',
                    Password::min(8)->uncompromised()->mixedCase()->numbers()->symbols(),
                    function ($attr, $value, $fail) {
                        if (Hash::check($value, auth()->user()->getAuthPassword())) {
                            $fail('You are using your old password.');
                        }
                    }
                ],
                'password_confirmation' => 'required|same:password',
            ],
            [
                'password.required' => 'This field is required.',
                'password.min' => 'Need at least 8 characters.',
                'password.uncompromised' => 'Please try a different password.',
                'password.mixed' => 'Need at least 1 uppercase letter.',
                'password.numbers' => 'Need at least 1 number.',
                'password.symbols' => 'Need at least 1 symbol.',

                'password_confirmation.required' => 'This field is required.',
                'password_confirmation.same' => 'Passwords do not match.',
            ],
        );
        $this->js('change_password = false;');
    }

    public function resetPassword()
    {
        $this->reset('password', 'password_confirmation');
        $this->resetValidation(['password', 'password_confirmation']);
        $this->js('change_password = false;');
    }

    public function saveProject()
    {
        $this->dispatch('project-number-prefix-save');
    }

    public function saveBatch()
    {
        $this->dispatch('batch-number-prefix-save');
    }

    public function updated($prop)
    {
        if ($prop === 'minimum_wage') {
            if (!isset($this->minimum_wage) || empty($this->minimum_wage)) {
                $this->resetValidation('minimum_wage');
                $this->minimum_wage = $this->settings->get('minimum_wage', config('settings.minimum_wage'));
                return;
            }

            $this->validateOnly('minimum_wage');
            $tempWage = MoneyFormat::unmask($this->minimum_wage);
            $this->minimum_wage = MoneyFormat::mask($tempWage);

            $this->dispatch('minimum-wage-save');
        }

        if ($prop === 'duplication_threshold') {
            if (!isset($this->duplication_threshold) || empty($this->duplication_threshold)) {
                $this->resetValidation('duplication_threshold');
                $this->duplication_threshold = intval($this->settings->get('duplication_threshold', config('settings.duplication_threshold')));
                return;
            }

            $this->validateOnly('duplication_threshold');
            $this->dispatch('duplication-threshold-save');

        }

        if ($prop === 'project_number_prefix') {
            if (!isset($this->project_number_prefix) || empty($this->project_number_prefix)) {
                $this->resetValidation('project_number_prefix');
                $this->project_number_prefix = $this->settings->get('project_number_prefix', config('settings.project_number_prefix'));
            }
        }

        if ($prop === 'batch_number_prefix') {
            if (!isset($this->batch_number_prefix) || empty($this->batch_number_prefix)) {
                $this->resetValidation('batch_number_prefix');
                $this->batch_number_prefix = $this->settings->get('batch_number_prefix', config('settings.batch_number_prefix'));
            }
        }

        if ($prop === 'maximum_income') {
            if (!isset($this->maximum_income) || empty($this->maximum_income)) {
                $this->resetValidation('maximum_income');
                $this->maximum_income = $this->settings->get('maximum_income', config('settings.maximum_income'));
                return;
            }

            $this->validateOnly('maximum_income');
            $this->dispatch('maximum-income-save');
        }
    }

    #[Computed]
    public function settings()
    {
        $settings = UserSetting::where('users_id', Auth::id())
            ->pluck('value', 'key');

        return $settings;
    }

    #[Computed]
    public function full_name($person)
    {
        $full = $person->first_name;

        if ($person->middle_name) {
            $full .= ' ' . $person->middle_name;
        }

        $full .= ' ' . $person->last_name;

        if ($person->extension_name) {
            $full .= ' ' . $person->extension_name;
        }

        return $full;
    }

    public function mount()
    {
        if (Auth::user()->user_type !== 'focal') {
            $this->redirectIntended();
        }

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
