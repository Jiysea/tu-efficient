<?php

namespace App\Livewire\Focal\Archives;

use App\Models\Archive;
use App\Models\Beneficiary;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class ViewArchivesCredentialModal extends Component
{
    #[Reactive]
    #[Locked]
    public $passedCredentialArchiveId;
    public $type = null;
    public $viewImageModal = false;

    #[Computed]
    public function archiveCredential()
    {
        $archiveCredential = Archive::find($this->passedCredentialArchiveId ? decrypt($this->passedCredentialArchiveId) : null);

        return $archiveCredential;
    }

    #[Computed]
    public function idInformation()
    {
        $info = collect();

        $beneficiary = Archive::where('source_table', 'beneficiaries')
            ->where('data->id', $this->archiveCredential?->data['beneficiaries_id'])
            ->first();

        $info->push(
            [
                'type_of_id' => $beneficiary->data['type_of_id'],
                'id_number' => $beneficiary->data['id_number']
            ]
        );

        return $info->collapse();
    }

    public function resetViewCredentials()
    {
        $this->resetExcept('passedCredentialArchiveId');
    }

    public function render()
    {
        if ($this->passedCredentialArchiveId) {

            if ($this->archiveCredential?->data['for_duplicates'] === 'yes') {
                $this->type = 'special';
            } elseif ($this->archiveCredential?->data['for_duplicates'] === 'no') {
                $this->type = 'identity';
            } else {
                $this->type = null;
            }
        }
        return view('livewire.focal.archives.view-archives-credential-modal');
    }
}
