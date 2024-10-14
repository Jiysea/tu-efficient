<?php

namespace App\Livewire\Focal\UserManagement;

use App\Models\Implementation;
use App\Models\User;
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
                    $illegal = ".!@#$%^&*()+=-[]';,/{}|:<>?~\"`\\";

                    # throws validation errors whenever it detects illegal characters on names
                    if (strpbrk($value, $illegal)) {
                        $fail('Illegal characters are not allowed.');
                    }
                    # throws validation error whenever the name has a number
                    elseif (preg_match('~[0-9]+~', $value)) {
                        $fail('Numbers on names are not allowed.');
                    }

                },
            ],
            'middle_name' => [
                # Check if the name has illegal characters
                function ($attribute, $value, $fail) {
                    $illegal = ".!@#$%^&*()+=-[]';,/{}|:<>?~\"`\\";

                    # throws validation errors whenever it detects illegal characters on names
                    if (strpbrk($value, $illegal)) {
                        $fail('Illegal characters are not allowed.');
                    }
                    # throws validation error whenever the name has a number
                    else if (preg_match('~[0-9]+~', $value)) {
                        $fail('Numbers on names are not allowed.');
                    }
                },
            ],
            'last_name' => [
                'required',
                # Check if the name has illegal characters
                function ($attribute, $value, $fail) {
                    $illegal = ".!@#$%^&*()+=-[]';,/{}|:<>?~\"`\\";

                    # throws validation errors whenever it detects illegal characters on names
                    if (strpbrk($value, $illegal)) {
                        $fail('Illegal characters are not allowed.');
                    }
                    # throws validation error whenever the name has a number
                    else if (preg_match('~[0-9]+~', $value)) {
                        $fail('Numbers on names are not allowed.');
                    }
                },
            ],
            'extension_name' => [
                # Check if the name has illegal characters
                function ($attribute, $value, $fail) {
                    $illegal = "!@#$%^&*()+=-[]';,/{}|:<>?~\"`\\";

                    # throws validation errors whenever it detects illegal characters on names
                    if (strpbrk($value, $illegal)) {
                        $fail('No illegal characters.');
                    }
                    # throws validation error whenever the name has a number
                    else if (preg_match('~[0-9]+~', $value)) {
                        $fail('No numbers.');
                    }
                },
            ],
            'contact_num' => [
                'required',
                function ($attr, $value, $fail) {
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
            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.',

            'contact_num.required' => 'Contact number is required.',
            'contact_num.digits' => 'Valid number should be 11 digits.',
            'contact_num.starts_with' => 'Valid number should start with \'09\'',

            'email.required' => 'Email is required.',
            'email.email' => 'Invalid email.',
            'email.unique' => 'Email already exists.',

            'password.required' => 'Password is required.',
            'password.min' => 'Must have at least 8 characters.',
            'password.uncompromised' => 'Please try a different :attribute.',
            'password.mixed' => 'Must have at least 1 uppercase letter.',
            'password.numbers' => 'Must have at least 1 number.',
            'password.symbols' => 'Must have at least 1 symbol.',

            'password_confirmation.required' => 'Passwords do not match.',
            'password_confirmation.same' => 'Passwords do not match.',
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

        User::create([
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'extension_name' => $this->extension_name,
            'email' => $this->email,
            'password' => bcrypt($this->password),
            'contact_num' => $this->contact_num,
            'regional_office' => Auth::user()->regional_office,
            'field_office' => Auth::user()->field_office,
            'user_type' => 'coordinator',
        ]);

        $this->reset();
        $this->dispatch('add-new-coordinator');
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
