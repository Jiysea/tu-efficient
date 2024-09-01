<?php

namespace App\Livewire\Focal;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('User Management | TU-Efficient')]
class UserManagement extends Component
{
    public function render()
    {
        if (Auth::user()->user_type === 'focal')
            return view('livewire.focal.user-management');
        else if (Auth::user()->user_type === 'coordinator')
            return redirect()->route('coordinator.home');
    }
}
