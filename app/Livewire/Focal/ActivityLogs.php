<?php

namespace App\Livewire\Focal;

use App\Livewire\Coordinator\Assignments;
use App\Models\SystemsLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Js;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Activity Logs | TU-Efficient')]
class ActivityLogs extends Component
{
    #[Locked]
    public $users_id;
    #[Locked]
    public $start;
    #[Locked]
    public $end;
    #[Locked]
    public $defaultStart;
    #[Locked]
    public $defaultEnd;
    #[Locked]
    public $logPage = 100;
    public int $resultsFrequency = 100;
    public $selectedRow = -1;
    public $searchLogs;
    public $sortTable = false;
    public $currentUser;
    public $searchUser;

    # --------------------------------------------------------------------------

    public function setStartDate($value)
    {
        $this->reset('searchLogs');
        $choosenDate = date('Y-m-d', strtotime($value));
        $currentTime = date('H:i:s', strtotime(now()));

        $this->start = $choosenDate . ' ' . $currentTime;
    }

    public function setEndDate($value)
    {
        $this->reset('searchLogs');
        $choosenDate = date('Y-m-d', strtotime($value));
        $currentTime = date('H:i:s', strtotime(now()));

        $this->end = $choosenDate . ' ' . $currentTime;

        if (strtotime($this->start) > strtotime($this->end)) {
            $start = Carbon::parse($this->end)->subDay()->format('Y-m-d H:i:s');
            $this->start = $start;
            $this->dispatch('modifyStart', newStart: Carbon::parse($this->start)->format('m/d/Y'))->self();
        }

    }

    #[Computed]
    public function logs()
    {
        $logs = SystemsLog::join('users', 'users.id', '=', 'system_logs.users_id')
            ->where('users.regional_office', Auth::user()->regional_office)
            ->where('users.field_office', Auth::user()->field_office)
            ->when($this->searchLogs, function ($q) {
                $q->where('system_logs.description', 'LIKE', '%' . $this->searchLogs . '%');
            })
            ->when($this->users_id, function ($q) {
                $q->where('system_logs.users_id', $this->users_id ? decrypt($this->users_id) : null);
            })
            ->whereBetween('system_logs.log_timestamp', [$this->start, $this->end])
            ->orderBy('system_logs.log_timestamp', $this->sortTable ? 'asc' : 'desc')
            ->take(value: $this->logPage)
            ->get();

        return $logs;
    }

    public function choose($encryptedId)
    {
        $this->users_id = $encryptedId;
    }

    public function clear()
    {
        $this->users_id = null;
        $this->currentUser = 'Choose a user...';
    }

    #[Computed]
    public function users()
    {
        $users = User::where('regional_office', auth()->user()->regional_office)
            ->where('field_office', auth()->user()->field_office)
            ->when($this->searchUser, function ($q) {
                $q->where('first_name', 'LIKE', '%' . $this->searchUser . '%')
                    ->orWhere('middle_name', 'LIKE', '%' . $this->searchUser . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $this->searchUser . '%');
            })
            ->get();
        return $users;
    }

    public function loadMoreLogs()
    {
        $this->logPage += $this->resultsFrequency;
    }

    #[Computed]
    public function getFullName($discombobulatedName)
    {
        $name = null;

        if ($discombobulatedName) {

            $name = $discombobulatedName->first_name;

            if ($discombobulatedName->middle_name) {
                $name .= ' ' . $discombobulatedName->middle_name;
            }

            $name .= ' ' . $discombobulatedName->last_name;

            if ($discombobulatedName->extension_name) {
                $name .= ' ' . $discombobulatedName->extension_name;
            }

            return $name;

        } else {

            return null;
        }
    }

    public function updated($prop)
    {
        if ($prop === 'resultsFrequency') {
            $this->logPage = $this->resultsFrequency;
        }
    }

    public function mount()
    {
        $user = Auth::user();
        if ($user->user_type !== 'focal') {
            $this->redirectIntended();
        }

        $this->start = date('Y-m-d H:i:s', strtotime(now()->startOfYear()));
        $this->end = date('Y-m-d H:i:s', strtotime(now()));

        $this->defaultStart = date('m/d/Y', strtotime($this->start));
        $this->defaultEnd = date('m/d/Y', strtotime($this->end));

        $this->currentUser = 'Choose a user...';
    }

    public function render()
    {
        return view('livewire.focal.activity-logs');
    }
}
