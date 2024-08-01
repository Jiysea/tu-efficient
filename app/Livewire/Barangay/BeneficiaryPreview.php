<?php

namespace App\Livewire\Barangay;

use App\Models\Beneficiary;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;
use Carbon\Carbon;

class BeneficiaryPreview extends Component
{
    #[Locked]
    public $accessCode;
    #[Locked]
    public $beneficiaryId;
    #[Locked]
    public $preview;
    #[Locked]
    public $full_name;

    #[On('change-beneficiary')]
    public function setCurrentProjectId($beneficiaryId)
    {
        $this->beneficiaryId = $beneficiaryId;
        $this->updateBeneficiaryPreview();
    }

    public function updateBeneficiaryPreview()
    {
        $decryptedId = Crypt::decrypt($this->beneficiaryId);

        $this->preview = Beneficiary::where('id', $decryptedId)
            ->first()
            ->toArray();

        $this->doSomethingAboutTheFullName();
    }

    public function doSomethingAboutTheFullName()
    {
        $first = $this->preview['first_name'];
        $middle = $this->preview['middle_name'];
        $last = $this->preview['last_name'];
        $ext = $this->preview['extension_name'];

        if ($ext === '-' && $middle === '-') {
            $this->full_name = $first . " " . $last;
        } else if ($middle === '-' && $ext !== '-') {
            $this->full_name = $first . " " . $last . " " . $ext;
        } else if ($middle !== '-' && $ext === '-') {
            $this->full_name = $first . " " . $middle . " " . $last;
        } else {
            $this->full_name = $first . " " . $middle . " " . $last . " " . $ext;
        }
    }

    public function mount()
    {
        // Retrieve the access code from the session
        $this->accessCode = session('access_code');

        $this->preview = Beneficiary::join('batches', 'batches.id', '=', 'beneficiaries.batches_id')
            ->join('codes', 'batches.id', '=', 'codes.batches_id')
            ->where('codes.access_code', $this->accessCode)
            ->select(
                DB::raw('beneficiaries.*'),
            )
            ->first()
            ->toArray();

        $this->doSomethingAboutTheFullName();
    }
    public function render()
    {
        return view('livewire.barangay.beneficiary-preview');
    }
}
