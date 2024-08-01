<?php

namespace App\Livewire\Barangay;

use Livewire\Attributes\Locked;
use Livewire\Component;

class Header extends Component
{
    #[Locked]
    public $accessCode;

    public function mount()
    {
        // Retrieve the access code from the session
        $this->accessCode = session('access_code');
    }
    public function render()
    {
        return view('livewire.barangay.header');
    }
}
