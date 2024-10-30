<?php

namespace App\Livewire\Focal\Implementations;

use App\Models\Beneficiary;
use App\Models\Credential;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class ViewCredentialsModal extends Component
{
    #[Reactive]
    #[Locked]
    public $passedCredentialId;
    public $type = null;
    public $viewImageModal = false;

    #[Computed]
    public function credentials()
    {
        if ($this->passedCredentialId) {
            $credentials = Credential::find(decrypt($this->passedCredentialId));

            return $credentials;
        }
    }

    #[Computed]
    public function idInformation()
    {
        $info = Beneficiary::join('credentials', 'credentials.beneficiaries_id', '=', 'beneficiaries.id')
            ->where('credentials.id', decrypt($this->passedCredentialId))
            ->select([
                'beneficiaries.type_of_id',
                'beneficiaries.id_number'
            ])
            ->first();

        return $info;
    }

    public function resetViewCredentials()
    {
        $this->resetExcept('passedCredentialId');
    }

    public function render()
    {
        if ($this->passedCredentialId) {
            if ($this->credentials?->for_duplicates === 'yes') {
                $this->type = 'special';
            } elseif ($this->credentials?->for_duplicates === 'no') {
                $this->type = 'identity';
            } else {
                $this->type = null;
            }
        }
        return view('livewire.focal.implementations.view-credentials-modal');
    }
}
