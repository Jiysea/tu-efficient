<?php

namespace App\Livewire\Focal;

use Auth;
use Livewire\Component;

class Archives extends Component
{

    public function mount()
    {
        $user = Auth::user();
        if ($user->user_type !== 'focal' || $user->isOngoingVerification()) {
            $this->redirectIntended();
        }
    }
    public function render()
    {
        return view('livewire.focal.archives');
    }
}
