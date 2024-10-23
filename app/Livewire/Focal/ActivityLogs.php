<?php

namespace App\Livewire\Focal;

use App\Livewire\Coordinator\Assignments;
use App\Models\SystemsLog;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Activity Logs | TU-Efficient')]
class ActivityLogs extends Component
{
    #[Locked]
    public $logPage = 100;
    protected $defaultLogPage = 100;

    public $selectedRow = -1;

    #[Computed]
    public function logs()
    {
        $logs = SystemsLog::join('users', 'users.id', '=', 'system_logs.users_id')
            ->where('users.regional_office', Auth::user()->regional_office)
            ->where('users.field_office', Auth::user()->field_office)
            ->latest('system_logs.log_timestamp')
            ->take(value: $this->logPage)
            ->get();

        return $logs;
    }

    public function loadMoreLogs()
    {
        $this->logPage += $this->defaultLogPage;
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

    public function mount()
    {
        $user = Auth::user();
        if ($user->user_type !== 'focal' || $user->isOngoingVerification()) {
            $this->redirectIntended();
        }
    }
    public function render()
    {
        return view('livewire.focal.activity-logs');
    }
}
