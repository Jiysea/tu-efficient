<?php

namespace App\Livewire\Focal;

use App\Livewire\Coordinator\Assignments;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class UserManagement extends Component
{
    #[Locked]
    public $users;
    public $selectedRows = [];
    public $selectedAllRows = false;
    public $showAlert = false;
    public $alertMessage = '';
    public $searchUsers;

    public function loadCoordinators()
    {
        $this->users = User::leftJoin('assignments', 'assignments.users_id', '=', 'users.id')
            ->leftJoin('batches', 'batches.id', '=', 'assignments.batches_id')
            ->where('users.user_type', 'coordinator')
            ->where('regional_office', Auth::user()->regional_office)
            ->where('field_office', Auth::user()->field_office)
            ->where(function ($q) {
                $q->orWhere('users.first_name', 'LIKE', '%' . $this->searchUsers . '%');
                $q->orWhere('users.middle_name', 'LIKE', '%' . $this->searchUsers . '%');
                $q->orWhere('users.last_name', 'LIKE', '%' . $this->searchUsers . '%');
                $q->orWhere('users.email', 'LIKE', '%' . $this->searchUsers . '%');
                $q->orWhere('users.contact_num', 'LIKE', '%' . $this->searchUsers . '%');
            })
            ->select([
                'users.id',
                'users.first_name',
                'users.middle_name',
                'users.last_name',
                'users.extension_name',
                'users.email',
                'users.contact_num',
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
                'users.last_login',
                'users.created_at',
                'users.updated_at',
            ])
            ->get()
            ->toArray();

        foreach ($this->users as $key => $user) {
            $this->selectedRows[] = false;

            $last_login = Carbon::parse($this->users[$key]['last_login'])->setTimezone('Asia/Singapore')->format('Y-m-d H:i:s');
            $created_at = Carbon::parse($this->users[$key]['created_at'])->setTimezone('Asia/Singapore')->format('Y-m-d H:i:s');

            if ($created_at === $last_login) {
                $last_login = 'Never';
            }

            $this->users[$key]['last_login'] = $last_login;
        }
    }

    public function selectAllRows()
    {
        if ($this->selectedAllRows === true) {
            foreach ($this->selectedRows as $key => $row) {
                $this->selectedRows[$key] = true;
            }
        } else {
            foreach ($this->selectedRows as $key => $row) {
                $this->selectedRows[$key] = false;
            }
        }
    }

    public function setFullName($key)
    {
        $fullName = null;
        $first = $this->users[$key]['first_name'];
        $middle = $this->users[$key]['middle_name'];
        $last = $this->users[$key]['last_name'];
        $ext = $this->users[$key]['extension_name'];

        $fullName = $first;

        if ($middle) {
            $fullName .= ' ' . $middle;
        }

        $fullName .= ' ' . $last;

        if ($ext) {
            $fullName .= ' ' . $ext;
        }

        return $fullName;
    }

    #[On('add-new-coordinator')]
    public function updateCoordinators()
    {
        $this->loadCoordinators();

        $this->showAlert = true;
        $this->alertMessage = 'Successfully created a new coordinator!';
        $this->dispatch('show-alert');
        $this->dispatch('init-reload')->self();
    }

    public function searchForUsers()
    {
        $this->loadCoordinators();
    }

    public function mount()
    {
        if (Auth::user()->user_type === 'coordinator') {
            $this->redirect(Assignments::class);
        }
        $this->loadCoordinators();
    }

    public function render()
    {
        return view('livewire.focal.user-management');
    }
}
