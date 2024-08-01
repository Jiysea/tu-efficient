<?php

namespace App\Livewire\Sidebar;

use Livewire\Component;

class CoordinatorBar extends Component
{
    public function setCurrentPage($pageName)
    {
        $this->dispatch('set-current-page', pageName: $pageName);
    }
    public function render()
    {
        return view('livewire.sidebar.coordinator-bar');
    }
}
