<?php

namespace App\Livewire\Focal\Implementations;

use Livewire\Component;

class TimeDropdown extends Component
{
    public $items = [
        'This year',
        'This month',
        'Past 3 months',
        'Past 6 months',
        'All time',
    ];

    public $currentItem;

    public function updateCurrentItem($id)
    {
        $this->currentItem = $this->items[$id];

        $this->dispatch('implementation-time-change', time: $this->items[$id]);
    }

    public function mount()
    {
        $this->currentItem = $this->items[0];
    }

    public function render()
    {
        return view('livewire.focal.implementations.time-dropdown');
    }
}
