<?php

namespace App\Livewire\Barangay\ListingPage;

use App\Models\UserSetting;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class PromptMultiArchiveModal extends Component
{
    #[Reactive]
    #[Locked]
    public array $beneficiaryIds;

    #[Computed]
    public function count()
    {
        return $this->beneficiaryIds ? count($this->beneficiaryIds) : 0;
    }

    public function render()
    {
        return view('livewire.barangay.listing-page.prompt-multi-archive-modal');
    }
}
