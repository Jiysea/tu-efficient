<?php

namespace App\Livewire\Focal;

use App\Livewire\Coordinator\Assignments;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Activity Logs | TU-Efficient')]
class ActivityLogs extends Component
{

    public function mount()
    {
        if (Auth::user()->user_type !== 'focal') {
            $this->redirectIntended();
        }
    }
    public function render()
    {
        return view('livewire.focal.activity-logs');
    }
}
