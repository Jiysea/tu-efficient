<?php

namespace App\Livewire\Focal\UserManagement;

use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class ViewCoordinator extends Component
{
    #[Locked]
    #[Reactive]
    public $coordinatorId;
    public $editMode = false;
    public $deleteCoordinatorModal = false;

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

    }

    public function deleteCoordinator()
    {
        $this->authorize('delete-coordinator-focal', [$this->user]);

        $this->user->delete();

        $this->js('viewCoordinatorModal = false;');
        $this->dispatch('delete-coordinator');
    }

    public function render()
    {
        return view('livewire.focal.user-management.view-coordinator');
    }
}
