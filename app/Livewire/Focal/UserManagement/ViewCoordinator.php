<?php

namespace App\Livewire\Focal\UserManagement;

use App\Models\User;
use App\Services\Essential;
use App\Services\LogIt;
use Hash;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Reactive;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Password;

class ViewCoordinator extends Component
{
    #[Locked]
    #[Reactive]
    public $coordinatorId;
    public $editMode = false;
    public $deleteCoordinatorModal = false;
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
        ];
    }

    #[Computed]
    public function user()
    {
        $user = User::find($this->coordinatorId ? decrypt($this->coordinatorId) : null);
        return $user;
    }

    #[Computed]
    public function isEmailVerified()
    {
        return $this->user?->email_verified_at !== null;
    }

    #[Computed]
    public function isMobileVerified()
    {
        return $this->user?->mobile_verified_at !== null;
    }

    public function toggleEdit()
    {
        $this->editMode = !$this->editMode;

        if ($this->editMode) {
            $this->first_name = $this->user->first_name;
            $this->middle_name = $this->user->middle_name;
            $this->last_name = $this->user->last_name;
            $this->extension_name = $this->user->extension_name;
            $this->contact_num = "0" . substr($this->user->contact_num, 3);
            $this->email = $this->user->email;
            $this->password = $this->user->password;
        } else {
            $this->resetExcept('coordinatorId');
        }

        $this->dispatch('init-reload')->self();
    }

    public function editCoordinator()
    {
        $this->contact_num = '+63' . substr($this->contact_num, 1);

        $user = User::find($this->coordinatorId ? decrypt($this->coordinatorId) : null);

        $user->fill([
            'first_name' => mb_strtoupper(Essential::trimmer($this->first_name), "UTF-8"),
            'middle_name' => $this->middle_name ? mb_strtoupper(Essential::trimmer($this->middle_name), "UTF-8") : null,
            'last_name' => mb_strtoupper(Essential::trimmer($this->last_name), "UTF-8"),
            'extension_name' => $this->extension_name ? mb_strtoupper(Essential::trimmer($this->extension_name), "UTF-8") : null,
            'email' => mb_strtolower(Essential::trimmer($this->email), "UTF-8"),
            'contact_num' => $this->contact_num,
        ]);

        if ($user->isDirty()) {
            $user->save();
            LogIt::set_edit_user($user, auth()->user());
            unset($this->user);
            $this->dispatch('edit-coordinator');
        }

        $this->resetView();
        $this->dispatch('init-reload')->self();
    }

    public function deleteCoordinator()
    {
        $this->authorize('delete-coordinator-focal', [$this->user]);

        $user = $this->user();
        $this->user->delete();

        LogIt::set_delete_user($user, auth()->user());
        $this->js('viewCoordinatorModal = false;');
        $this->dispatch('delete-coordinator');
    }

    public function resetView()
    {
        $this->resetExcept('coordinatorId');
    }

    public function render()
    {
        return view('livewire.focal.user-management.view-coordinator');
    }
}
