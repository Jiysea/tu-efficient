<?php

namespace App\Livewire\Coordinator\Submissions;

use App\Models\Archive;
use App\Models\Batch;
use App\Models\Beneficiary;
use App\Models\Credential;
use App\Models\UserSetting;
use App\Services\LogIt;
use DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Reactive;
use Livewire\Component;
use Storage;

class BeneficiaryPreview extends Component
{
    #[Reactive]
    #[Locked]
    public $beneficiaryId;
    #[Locked]
    public $passedCredentialId;
    #[Locked]
    public $defaultArchive;
    public $identity;
    public $special;

    # ---------------------------------------------------

    public function render()
    {
        return view('livewire.coordinator.submissions.beneficiary-preview');
    }
}
