<?php

namespace App\Livewire\Coordinator;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Forms | TU-Efficient')]
class Forms extends Component
{

    public function mount()
    {
        if (Auth::user()->user_type !== 'coordinator') {
            $this->redirectIntended();
        }
    }
    public function render()
    {
        return view('livewire.coordinator.forms');
    }
}
