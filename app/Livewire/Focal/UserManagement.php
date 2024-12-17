<?php

namespace App\Livewire\Focal;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('User Management | TU-Efficient')]
class UserManagement extends Component
{
    #[Locked]
    public $coordinatorId;
    public $searchUsers;
    public $alerts = [];
    public $addCoordinatorsModal = false;
    public $viewCoordinatorModal = false;

    #[Computed]
    public function users()
    {
        $users = User::where('users.user_type', 'coordinator')
            ->where('users.regional_office', Auth::user()->regional_office)
            ->where('users.field_office', Auth::user()->field_office)
            ->when($this->searchUsers, function ($q) {
                # Check if the search field starts with '#' and filter by contact number
                if (str_contains($this->searchUsers, '#')) {
                    $searchValue = trim(str_replace('#', '', $this->searchUsers));

                    if (strpos($searchValue, '0') === 0) {
                        $searchValue = substr($searchValue, 1);
                    }
                    $q->where('users.contact_num', 'LIKE', '%' . $searchValue . '%');
                } else {
                    # Otherwise, search by first, middle, last or extension name
                    $q->where(function ($query) {
                        $query->where('users.first_name', 'LIKE', '%' . $this->searchUsers . '%')
                            ->orWhere('users.middle_name', 'LIKE', '%' . $this->searchUsers . '%')
                            ->orWhere('users.last_name', 'LIKE', '%' . $this->searchUsers . '%')
                            ->orWhere('users.extension_name', 'LIKE', '%' . $this->searchUsers . '%');
                    });
                }
            })
            ->select([
                'users.*'
            ])
            ->get();
        return $users;
    }

    #[Computed]
    public function approvedCount($user)
    {
        $approved = User::join('assignments', 'assignments.users_id', '=', 'users.id')
            ->join('batches', 'batches.id', '=', 'assignments.batches_id')
            ->where('users.id', $user->id)
            ->where('batches.approval_status', 'approved')
            ->distinct()
            ->count();

        return $approved;
    }

    #[Computed]
    public function pendingCount($user)
    {
        $pending = User::join('assignments', 'assignments.users_id', '=', 'users.id')
            ->join('batches', 'batches.id', '=', 'assignments.batches_id')
            ->where('users.id', $user->id)
            ->where('batches.approval_status', 'pending')
            ->distinct()
            ->count();

        return $pending;
    }

    #[Computed]
    public function checkIfOnline(User $user)
    {
        $status = $user->isOnline();

        if ($status)
            return true;
        else
            return $user->last_login;
    }

    public function viewCoordinator($encryptedId)
    {
        $this->coordinatorId = $encryptedId;
        $this->viewCoordinatorModal = true;
    }

    public function full_name($person)
    {
        $full_name = null;
        if ($person) {

            $full_name = $person->first_name;

            if ($person->middle_name) {
                $full_name .= ' ' . $person->middle_name;
            }

            $full_name .= ' ' . $person->last_name;

            if ($person->extension_name) {
                $full_name .= ' ' . $person->extension_name;
            }

        }
        return $full_name;
    }


    #[On('add-new-coordinator')]
    public function createCoordinators()
    {
        $this->addCoordinatorsModal = false;
        $this->showAlert = true;
        $this->alertMessage = 'Successfully created a new coordinator';
        $this->dispatch('show-alert');
        $this->dispatch('init-reload')->self();
    }

    #[On('edit-coordinator')]
    public function editCoordinators()
    {
        $this->showAlert = true;
        $this->alertMessage = 'Modified a coordinator successfully';
        $this->dispatch('show-alert');
        $this->dispatch('init-reload')->self();
    }

    #[On('delete-coordinator')]
    public function deleteCoordinators()
    {
        $this->coordinatorId = null;
        $this->showAlert = true;
        $this->alertMessage = 'A coordinator has been deleted';
        $this->dispatch('show-alert');
        $this->dispatch('init-reload')->self();
    }

    #[On('alertNotification')]
    public function alertNotification($type = null, $message, $color)
    {
        if ($type === 'delete') {
            $this->coordinatorId = null;
        }

        $this->alerts[] = [
            'message' => $message,
            'id' => uniqid(),
            'color' => $color
        ];

        unset($this->users);

        $this->dispatch('init-reload')->self();
    }

    public function removeAlert($id)
    {
        $this->alerts = array_filter($this->alerts, function ($alert) use ($id) {
            return $alert['id'] !== $id;
        });
    }

    public function mount()
    {
        $user = Auth::user();
        if ($user->user_type !== 'focal') {
            $this->redirectIntended();
        }

    }

    public function render()
    {
        return view('livewire.focal.user-management');
    }
}
