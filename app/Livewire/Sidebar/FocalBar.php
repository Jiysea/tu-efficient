<?php

namespace App\Livewire\Sidebar;

use Livewire\Attributes\Js;
use Livewire\Component;

class FocalBar extends Component
{

    #[Js]
    public function logout()
    {
        return <<<JS
            const logoutBtn = document.getElementById('logout-btn');
            if(logoutBtn.disabled === true) {
                logoutBtn.disabled = false;
            } else {
                logoutBtn.disabled = true;
            }
            document.getElementById('logout-form').submit();
        JS;
    }

    public function render()
    {
        return view('livewire.sidebar.focal-bar');
    }
}
