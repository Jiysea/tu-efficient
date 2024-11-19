<?php

namespace App\Livewire\Focal\Archives;

use Livewire\Attributes\Locked;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class PromptRestoreModal extends Component
{
    #[Reactive]
    #[Locked]
    public $actionId;

    public function render()
    {
        return view('livewire.focal.archives.prompt-restore-modal');
    }
}
