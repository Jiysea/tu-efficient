<?php

namespace App\Livewire\Focal\Archives;

use App\Models\Archive;
use App\Models\Batch;
use App\Models\Beneficiary;
use App\Models\Implementation;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class RecordPreview extends Component
{
    #[Reactive]
    #[Locked]
    public $archiveId;

    #[Computed]
    public function archive()
    {
        $archive = Archive::find($this->archiveId ? decrypt($this->archiveId) : null);
        return $archive;
    }

    #[Computed]
    public function identity()
    {
        $identity = null;
        foreach ($this->credentials as $credential) {
            if ($credential['for_duplicates'] === 'no') {
                $identity = $credential;
            }
        }
        return $identity;
    }

    #[Computed]
    public function special_case()
    {
        $special_case = null;
        foreach ($this->credentials as $credential) {
            if ($credential['for_duplicates'] === 'yes') {
                $special_case = $credential;
            }
        }
        return $special_case;
    }

    #[Computed]
    public function getIdType()
    {
        $type_of_id = null;

        if ($this->archiveId) {

            if (str_contains($this->archive->data['type_of_id'], 'PWD')) {
                $type_of_id = 'PWD ID';
            } else if (str_contains($this->archive->data['type_of_id'], 'COMELEC')) {
                $type_of_id = 'Voter\'s ID';
            } else if (str_contains($this->archive->data['type_of_id'], 'PhilID')) {
                $type_of_id = 'PhilID';
            } else if (str_contains($this->archive->data['type_of_id'], '4Ps')) {
                $type_of_id = '4Ps ID';
            } else if (str_contains($this->archive->data['type_of_id'], 'IBP')) {
                $type_of_id = 'IBP ID';
            } else {
                $type_of_id = $this->archive->data['type_of_id'];
            }

        }

        return $type_of_id;
    }

    #[Computed]
    public function credentials()
    {
        $archives = Archive::where('source_table', 'credentials')
            ->where('data->beneficiaries_id', $this->archive['data']['id'])
            ->get();
        $credentials = collect();
        foreach ($archives as $archive) {
            $credentials->push([
                'id' => $archive->data['id'],
                'beneficiaries_id' => $archive->data['beneficiaries_id'],
                'image_description' => $archive->data['image_description'],
                'image_file_path' => $archive->data['image_file_path'],
                'for_duplicates' => $archive->data['for_duplicates'],
                'created_at' => $archive->data['created_at'],
                'updated_at' => $archive->data['updated_at'],
            ]);
        }
        return $credentials;
    }

    public function render()
    {
        return view('livewire.focal.archives.record-preview');
    }
}
