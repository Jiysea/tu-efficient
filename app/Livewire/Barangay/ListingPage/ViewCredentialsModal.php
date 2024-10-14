<?php

namespace App\Livewire\Barangay\ListingPage;

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
    public $credentialId;
    public $type = null;
    public $viewImageModal = false;

    #[Computed]
    public function credentials()
    {
        if ($this->credentialId) {
            $credentials = Credential::find(decrypt($this->credentialId));

            return $credentials;
        }
    }

    #[Computed]
    public function idInformation()
    {
        $info = Beneficiary::join('credentials', 'credentials.beneficiaries_id', '=', 'beneficiaries.id')
            ->where('credentials.id', decrypt($this->credentialId))
            ->select([
                'beneficiaries.type_of_id',
                'beneficiaries.id_number'
            ])
            ->first();

        return $info;
    }

    public function resetViewCredentials()
    {
        $this->resetExcept('credentialId');
    }

    public function render()
    {
        if ($this->credentialId) {
            if ($this->credentials->for_duplicates === 'yes') {
                $this->type = 'special';
            } elseif ($this->credentials->for_duplicates === 'no') {
                $this->type = 'identity';
            } else {
                $this->type = null;
            }
        }
        return view('livewire.barangay.listing-page.view-credentials-modal');
    }
}
