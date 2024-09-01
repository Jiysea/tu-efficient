<?php

namespace App\Livewire\Focal;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Activity Logs | TU-Efficient')]
class ActivityLogs extends Component
{
    public function render()
    {

        if (Auth::user()->user_type === 'focal')
            return view('livewire.focal.activity-logs');
        else if (Auth::user()->user_type === 'coordinator')
            return redirect()->route('coordinator.home');
    }
}
