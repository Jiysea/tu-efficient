<?php

namespace App\Livewire\Barangay;

use App\Models\Beneficiary;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Carbon\Carbon;

class ListOfBeneficiaries extends Component
{
    public $selectedRow = 0;
    #[Locked]
    public $accessCode;
    #[Locked]
    public $beneficiaryId;
    #[Locked]
    public $beneficiaries;

    public function selectRow($key, $encryptedId)
    {
        $this->selectedRow = $key;
        $this->beneficiaryId = $encryptedId;

        $this->dispatch('change-beneficiary', beneficiaryId: $encryptedId);
    }

    public function mount($accessCode)
    {
        // Retrieve the access code from the session
        $this->accessCode = $accessCode;

        $this->beneficiaries = Beneficiary::join('batches', 'beneficiaries.batches_id', '=', 'batches.id')
            ->join('codes', 'batches.id', '=', 'codes.batches_id')
            ->where('codes.access_code', $this->accessCode)
            ->select(
                DB::raw('beneficiaries.*'),
            )
            ->get()
            ->toArray();
    }

    public function render()
    {
        return view('livewire.barangay.list-of-beneficiaries');
    }
}
