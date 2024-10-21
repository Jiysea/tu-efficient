<?php

namespace App\Livewire\Coordinator\Submissions;

use Livewire\Attributes\Locked;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class BeneficiaryPreview extends Component
{
    #[Reactive]
    #[Locked]
    public $beneficiaryId;
    public $identity;



    public function render()
    {
        return view('livewire.coordinator.submissions.beneficiary-preview');
    }
}
