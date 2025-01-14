<?php

namespace App\Livewire\Coordinator;

use App\Models\Batch;
use App\Models\Implementation;
use App\Models\User;
use App\Models\UserSetting;
use App\Services\Essential;
use App\Services\LogIt;
use App\Services\MoneyFormat;
use Carbon\Carbon;
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
    public $editFullnameModal = false;

    # --------------------------------------------------------------------------

    #[Validate]
    public $first_name;
    #[Validate]
    public $middle_name;
    #[Validate]
    public $last_name;
    #[Validate]
    public $extension_name;

    # --------------------------------------------------------------------------

    #[Validate]
    public $duplication_threshold;
    #[Validate]
    public $password;
    #[Validate]
    public $password_confirmation;
    public $default_archive;
    public $default_show_duplicates;

    public function rules()
    {

        return [
            'first_name' => [
                'required',
                # Check if the name has illegal characters
                function ($attribute, $value, $fail) {

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
                function ($attribute, $value, $fail) {
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
            'last_name' => [
                'required',
                # Check if the name has illegal characters
                function ($attribute, $value, $fail) {
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
            'extension_name' => [
                # Check if the name has illegal characters
                function ($attribute, $value, $fail) {
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
            'first_name.required' => 'This field is required.',
            'last_name.required' => 'This field is required.',

            'duplication_threshold.required' => 'This field is required.',

            'duplication_threshold.integer' => 'The value should be an integer.',

            'password.required' => 'This field is required.',
            'password.min' => 'Needs at least 8 characters.',
            'password.uncompromised' => 'Please try a different :attribute.',
            'password.mixed' => 'Needs at least 1 uppercase letter.',
            'password.numbers' => 'Needs at least 1 number.',
            'password.symbols' => 'Needs at least 1 symbol.',

            'password_confirmation.required' => 'This field is required.',
            'password_confirmation.same' => 'Passwords does not match.',
        ];
    }

    public function setFullNameValues()
    {
        $this->first_name = auth()->user()->first_name;
        $this->middle_name = auth()->user()->middle_name;
        $this->last_name = auth()->user()->last_name;
        $this->extension_name = auth()->user()->extension_name;
        $this->editFullnameModal = true;
    }

    public function editFullName()
    {
        $user = User::find(auth()->id());
        $old = $this->full_name($user);
        $user->fill([
            'first_name' => mb_strtoupper(Essential::trimmer($this->first_name), "UTF-8"),
            'middle_name' => $this->middle_name ? mb_strtoupper(Essential::trimmer($this->middle_name), "UTF-8") : null,
            'last_name' => mb_strtoupper(Essential::trimmer($this->last_name), "UTF-8"),
            'extension_name' => $this->extension_name ? mb_strtoupper(Essential::trimmer($this->extension_name), "UTF-8") : null,
        ]);

        if ($user->isDirty()) {
            $user->save();
            LogIt::set_change_fullname($user, $old, $this->full_name($user));
            $this->dispatch('fullname-change-save');
        }

        $this->js('editFullnameModal = false;');
        $this->js('$wire.$refresh();');
        $this->dispatch('init-reload')->self();
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
                'password.min' => 'Needs at least 8 characters.',
                'password.uncompromised' => 'Please try a different :attribute.',
                'password.mixed' => 'Needs at least 1 uppercase letter.',
                'password.numbers' => 'Needs at least 1 number.',
                'password.symbols' => 'Needs at least 1 symbol.',

                'password_confirmation.required' => 'This field is required.',
                'password_confirmation.same' => 'Passwords does not match.',
            ],
        );

        User::where('id', auth()->id())
            ->update([
                'password' => bcrypt($this->password)
            ]);

        LogIt::set_settings_password_change(auth()->user());
        $this->js('change_password = false;');
    }

    public function resetPassword()
    {
        $this->reset('password', 'password_confirmation');
        $this->resetValidation(['password', 'password_confirmation']);
        $this->js('change_password = false;');
    }

    public function toggleDefaultArchive()
    {
        if (!isset($this->default_archive)) {
            $this->resetValidation('default_archive');
            $this->default_archive = intval($this->settings->get('default_archive', config('settings.duplication_threshold')));
            return;
        }

        $this->default_archive = !$this->default_archive;

        $old = $this->settings->get('default_archive', config('settings.default_archive'));
        UserSetting::upsert(
            ['users_id' => auth()->id(), 'key' => 'default_archive', 'value' => $this->default_archive],
            ['users_id' => auth()->id()],
            ['value']
        );

        LogIt::set_default_archive_settings(auth()->user(), $old, $this->default_archive);

        $this->dispatch('def-archive-save');
    }

    public function toggleShowDuplicates()
    {
        if (!isset($this->default_show_duplicates)) {
            $this->resetValidation('default_show_duplicates');
            $this->default_show_duplicates = intval($this->settings->get('default_show_duplicates', config('settings.duplication_threshold')));
            return;
        }

        $this->default_show_duplicates = !$this->default_show_duplicates;

        $old = $this->settings->get('default_show_duplicates', config('settings.default_show_duplicates'));
        UserSetting::upsert(
            ['users_id' => auth()->id(), 'key' => 'default_show_duplicates', 'value' => $this->default_show_duplicates],
            ['users_id' => auth()->id()],
            ['value']
        );

        LogIt::set_default_show_duplicates_settings(auth()->user(), $old, $this->default_show_duplicates);

        $this->dispatch('show-duplicates-save');
    }

    # It's a Livewire `Hook` for properties so the system can take action
    # when a specific property has updated its state. 
    public function updated($prop)
    {

        if ($prop === 'duplication_threshold') {
            if (!isset($this->duplication_threshold) || empty($this->duplication_threshold)) {
                $this->resetValidation('duplication_threshold');
                $this->duplication_threshold = intval($this->settings->get('duplication_threshold', config('settings.duplication_threshold')));
                return;
            }

            $this->validateOnly('duplication_threshold');
            $old = $this->settings->get('duplication_threshold', config('settings.duplication_threshold'));
            UserSetting::upsert(
                ['users_id' => auth()->id(), 'key' => 'duplication_threshold', 'value' => $this->duplication_threshold],
                ['users_id' => auth()->id()],
                ['value']
            );

            LogIt::set_duplication_threshold_settings(auth()->user(), $old, $this->duplication_threshold);
            $this->dispatch('duplication-threshold-save');
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
        if (Auth::user()->user_type !== 'coordinator') {
            $this->redirectIntended();
        }

        # Technical

        $settings = UserSetting::where('users_id', Auth::id())
            ->pluck('value', 'key');

        $this->duplication_threshold = intval($settings->get('duplication_threshold', config('settings.duplication_threshold')));
        $this->default_archive = intval($settings->get('default_archive', config('settings.default_archive')));
        $this->default_show_duplicates = intval($settings->get('default_show_duplicates', config('settings.default_show_duplicates')));

    }
    public function render()
    {
        return view('livewire.coordinator.settings');
    }
}
