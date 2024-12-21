<?php

namespace App\Livewire\Focal;

use App\Livewire\Coordinator\Assignments;
use App\Models\SystemsLog;
use App\Models\User;
use App\Services\Essential;
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
    public $logId;
    #[Locked]
    public $users_id;
    public $start;
    public $end;
    public $calendarStart;
    public $calendarEnd;
    public $defaultStart;
    public $defaultEnd;
    public $logPage = 100;
    public int $resultsFrequency = 100;
    public $selectedRow = -1;
    public $searchLogs;
    public $sortTable = false;
    public $currentUser = 'Choose a user...';
    public $searchUser;
    public $viewLogModal = false;

    # --------------------------------------------------------------------------

    public function viewLog($key, $encryptedId)
    {
        $this->selectedRow = $key;
        $this->logId = $encryptedId;
        $this->viewLogModal = true;
    }

    #[Computed]
    public function logs()
    {
        return SystemsLog::where('regional_office', auth()->user()->regional_office)
            ->where('field_office', auth()->user()->field_office)
            ->when($this->searchLogs, function ($query) {
                $query->where(function ($q) {
                    $q->where('description', 'LIKE', '%' . $this->searchLogs . '%')
                        ->orWhere('log_type', 'LIKE', '%' . $this->searchLogs . '%');
                });
            })
            ->when($this->users_id, function ($q) {
                $q->where('users_id', $this->users_id ? decrypt($this->users_id) : null);
            })
            ->whereBetween('log_timestamp', [$this->start, $this->end])
            ->orderBy('log_timestamp', $this->sortTable ? 'asc' : 'desc')
            ->take(value: $this->logPage)
            ->get();
    }

    public function choose($encryptedId)
    {
        $this->users_id = $encryptedId;
    }

    public function clear()
    {
        $this->users_id = null;
        $this->reset('currentUser');
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
    public function getFullName(User|int|null $userOrId)
    {
        $name = null;

        if (is_int($userOrId)) {
            $person = User::find($userOrId);
            $name = $person->first_name;

            if ($person->middle_name) {
                $name .= ' ' . $person->middle_name;
            }

            $name .= ' ' . $person->last_name;

            if ($person->extension_name) {
                $name .= ' ' . $person->extension_name;
            }

            return $name;

        } elseif ($userOrId instanceof User) {
            $name = $userOrId->first_name;

            if ($userOrId->middle_name) {
                $name .= ' ' . $userOrId->middle_name;
            }

            $name .= ' ' . $userOrId->last_name;

            if ($userOrId->extension_name) {
                $name .= ' ' . $userOrId->extension_name;
            }

            return $name;
        }

        return $name;
    }

    # It's a Livewire `Hook` for properties so the system can take action
    # when a specific property has updated its state. 
    public function updated($prop)
    {
        if ($prop === 'resultsFrequency') {
            $this->logPage = $this->resultsFrequency;
        }

        if ($prop === 'calendarStart') {
            $format = Essential::extract_date($this->calendarStart, false);
            if ($format !== 'm/d/Y') {
                $this->calendarStart = $this->defaultStart;
                return;
            }

            $this->reset('searchLogs');
            $choosenDate = Carbon::createFromFormat('m/d/Y', $this->calendarStart)->format('Y-m-d');
            $currentTime = now()->startOfDay()->format('H:i:s');

            $this->start = $choosenDate . ' ' . $currentTime;

            if (strtotime($this->start) > strtotime($this->end)) {
                $end = Carbon::parse($this->start)->addMonth()->endOfDay()->format('Y-m-d H:i:s');
                $this->end = $end;
                $this->calendarEnd = Carbon::parse($this->end)->format('m/d/Y');
            }

            $this->dispatch('init-reload')->self();
            $this->dispatch('scroll-top-logs')->self();
        }

        if ($prop === 'calendarEnd') {
            $format = Essential::extract_date($this->calendarEnd, false);
            if ($format !== 'm/d/Y') {
                $this->calendarEnd = $this->defaultEnd;
                return;
            }

            $this->reset('searchLogs');
            $choosenDate = Carbon::createFromFormat('m/d/Y', $this->calendarEnd)->format('Y-m-d');
            $currentTime = now()->endOfDay()->format('H:i:s');

            $this->end = $choosenDate . ' ' . $currentTime;

            if (strtotime($this->start) > strtotime($this->end)) {
                $start = Carbon::parse($this->end)->subMonth()->startOfDay()->format('Y-m-d H:i:s');
                $this->start = $start;
                $this->calendarStart = Carbon::parse($this->start)->format('m/d/Y');
            }

            $this->dispatch('init-reload')->self();
            $this->dispatch('scroll-top-logs')->self();
        }
    }

    public function mount()
    {
        $user = Auth::user();
        if ($user->user_type !== 'focal') {
            $this->redirectIntended();
        }

        $this->start = now()->startOfYear()->format('Y-m-d H:i:s');
        $this->end = now()->endOfDay()->format('Y-m-d H:i:s');

        $this->calendarStart = Carbon::parse($this->start)->format('m/d/Y');
        $this->calendarEnd = Carbon::parse($this->end)->format('m/d/Y');

        $this->defaultStart = $this->calendarStart;
        $this->defaultEnd = $this->calendarEnd;
    }

    public function render()
    {
        return view('livewire.focal.activity-logs');
    }
}
