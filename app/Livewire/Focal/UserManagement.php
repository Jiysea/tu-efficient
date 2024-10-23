<?php

namespace App\Livewire\Focal;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('User Management | TU-Efficient')]
class UserManagement extends Component
{
    public array $selectedRows = [];
    public bool $selectedAllRows;
    public $showAlert = false;
    public $alertMessage = '';
    public $searchUsers;
    public $addCoordinatorsModal = false;

    #[Computed]
    public function users()
    {
        $users = User::leftJoin('assignments', 'assignments.users_id', '=', 'users.id')
            ->leftJoin('batches', 'batches.id', '=', 'assignments.batches_id')
            ->where('users.user_type', 'coordinator')
            ->where('regional_office', Auth::user()->regional_office)
            ->where('field_office', Auth::user()->field_office)
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
                'users.id',
                'users.first_name',
                'users.middle_name',
                'users.last_name',
                'users.extension_name',
                'users.email',
                'users.contact_num',
                'users.regional_office',
                'users.field_office',
                'users.user_type',
                'users.email_verified_at',
                'users.last_login',
                'users.created_at',
                'users.updated_at',
                DB::raw('SUM(CASE WHEN batches.approval_status = "approved" THEN 1 ELSE 0 END) AS approved_assignments'),
                DB::raw('SUM(CASE WHEN batches.approval_status = "pending" THEN 1 ELSE 0 END) AS pending_assignments'),
            ])
            ->groupBy([
                'users.id',
                'users.first_name',
                'users.middle_name',
                'users.last_name',
                'users.extension_name',
                'users.email',
                'users.contact_num',
                'users.regional_office',
                'users.field_office',
                'users.user_type',
                'users.email_verified_at',
                'users.last_login',
                'users.created_at',
                'users.updated_at',
            ])
            ->get();

        return $users;
    }


    public function updatedSelectedAllRows($value)
    {
        if ($value) {
            $this->selectedRows = range(0, count($this->users) - 1);
        } else {
            $this->selectedRows = [];
        }
    }

    public function full_name($person)
    {
        $full_name = $person->first_name;

        if ($person->middle_name) {
            $full_name .= ' ' . $person->middle_name;
        }

        $full_name .= ' ' . $person->last_name;

        if ($person->extension_name) {
            $full_name .= ' ' . $person->extension_name;
        }

        return $full_name;
    }

    #[On('add-new-coordinator')]
    public function createCoordinators()
    {
        $this->addCoordinatorsModal = false;
        $this->showAlert = true;
        $this->alertMessage = 'Successfully created a new coordinator!';
        $this->dispatch('show-alert');
        $this->dispatch('init-reload')->self();
    }

    public function mount()
    {
        $user = Auth::user();
        if ($user->user_type !== 'focal' || $user->isOngoingVerification()) {
            $this->redirectIntended();
        }

    }

    public function render()
    {
        return view('livewire.focal.user-management');
    }
}
