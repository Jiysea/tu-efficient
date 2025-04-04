<?php

namespace App\Livewire\Focal\UserManagement;

use App\Jobs\CheckCoordinatorVerificationStatus;
use App\Models\Implementation;
use App\Models\User;
use App\Services\Essential;
use App\Services\LogIt;
use DB;
use Illuminate\Auth\Events\Registered;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Validate;
use Livewire\Component;

class AddCoordinatorsModal extends Component
{
    #[Validate]
    public $first_name;
    #[Validate]
    public $middle_name;
    #[Validate]
    public $last_name;
    #[Validate]
    public $extension_name;
    #[Validate]
    public $contact_num;
    #[Validate]
    public $email;
    #[Validate]
    public $password;
    #[Validate]
    public $password_confirmation;

    # Runs real-time depending on wire:model suffix
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
            'contact_num' => [
                'required',
                function ($attr, $value, $fail) {
                    if (User::where('contact_num', '+63' . substr($this->contact_num, 1))->exists()) {
                        $fail('Contact number already exists.');
                    }
                    if (!preg_match('~[0-9]+~', $value)) {
                        $fail('Value only accepts numbers.');
                    }
                },
                'starts_with:09',
                'digits:11',
            ],
            'email' => 'required|email|unique:users',
            'password' => ['required', Password::min(8)->uncompromised()->mixedCase()->numbers()->symbols()],
            'password_confirmation' => 'required|same:password',
        ];
    }

    # The validation error messages
    public function messages()
    {
        return [
            'first_name.required' => 'This field is required.',
            'last_name.required' => 'This field is required.',

            'contact_num.required' => 'This field is required.',
            'contact_num.digits' => 'Valid number should be 11 digits.',
            'contact_num.starts_with' => 'Valid number should start with \'09\'',

            'email.required' => 'This field is required.',
            'email.email' => 'Invalid email.',
            'email.unique' => 'Email already exists.',

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

    # Validation attribute names for human readability purpose
    # for example: The project_num should not be empty.
    # instead of that: The project number should not be empty.
    public function validationAttributes()
    {
        return [
            'contact_num' => 'contact number',
            'password' => 'password',
        ];
    }

    # a livewire action executes after clicking the `Create Project` button
    public function saveUser()
    {
        $this->validate();

        $this->contact_num = '+63' . substr($this->contact_num, 1);

        $user = User::create([
            'first_name' => mb_strtoupper(Essential::trimmer($this->first_name), "UTF-8"),
            'middle_name' => $this->middle_name ? mb_strtoupper(Essential::trimmer($this->middle_name), "UTF-8") : null,
            'last_name' => mb_strtoupper(Essential::trimmer($this->last_name), "UTF-8"),
            'extension_name' => $this->extension_name ? mb_strtoupper(Essential::trimmer($this->extension_name), "UTF-8") : null,
            'email' => mb_strtolower(Essential::trimmer($this->email), "UTF-8"),
            'password' => bcrypt($this->password),
            'contact_num' => $this->contact_num,
            'regional_office' => Auth::user()->regional_office,
            'field_office' => Auth::user()->field_office,
            'user_type' => 'coordinator',
        ]);

        LogIt::set_register_user($user, auth()->id());

        $this->resetCoordinators();
        $this->dispatch('alertNotification', type: null, message: 'Successfully created a coordinator', color: 'indigo');
        $this->js('addCoordinatorsModal = false;');
    }

    public function resetCoordinators()
    {
        $this->reset();
    }

    public function render()
    {
        return view('livewire.focal.user-management.add-coordinators-modal');
    }
}
