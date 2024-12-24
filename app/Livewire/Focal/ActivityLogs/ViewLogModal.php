<?php

namespace App\Livewire\Focal\ActivityLogs;

use App\Models\SystemsLog;
use App\Models\User;
use App\Models\UserSetting;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class ViewLogModal extends Component
{
    #[Reactive]
    #[Locked]
    public $logId;

    #[Computed]
    public function log()
    {
        return SystemsLog::find($this->logId ? decrypt($this->logId) : null);
    }

    #[Computed]
    public function user()
    {
        return $this->log ? json_decode($this->log?->old_data)?->users : null;
    }

    #[Computed]
    public function implementation()
    {
        return $this->log ? json_decode($this->log?->old_data)?->implementations : null;
    }

    #[Computed]
    public function batch()
    {
        return $this->log ? json_decode($this->log?->old_data)?->batches : null;
    }

    #[Computed]
    public function beneficiary()
    {
        return $this->log ? json_decode($this->log?->old_data)?->beneficiaries : null;
    }

    #[Computed]
    public function credential()
    {
        $decodedData = $this->log ? json_decode($this->log?->old_data) : null;
        if (!$decodedData) {
            return null;
        }

        return $decodedData?->credentials ?? null;
    }

    #[Computed]
    public function sender()
    {
        return $this->log?->users_id ? self::full_name($this->log?->users_id) : $this->log?->alternative_sender;
    }

    #[Computed]
    public function office()
    {
        return $this->log ? ($this->log?->regional_office . ' / ' . $this->log?->field_office) : null;
    }

    protected static function full_name($person)
    {
        $full_name = null;
        if (gettype($person) === 'integer') {
            $person = User::find($person);
        }

        $full_name = $person['first_name'];

        if ($person['middle_name']) {
            $full_name .= ' ' . $person['middle_name'];
        }

        $full_name .= ' ' . $person['last_name'];

        if ($person['extension_name']) {
            $full_name .= ' ' . $person['extension_name'];
        }

        return $full_name;
    }

    public function render()
    {
        return view('livewire.focal.activity-logs.view-log-modal');
    }
}
