<?php

namespace App\Livewire\Focal\Implementations;

use App\Models\Implementation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class ListOfBeneficiaries extends Component
{
    public $selectedRow = 0;
    #[Locked]
    public $batchId;
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

    #[On('change-batch')]
    public function setCurrentProject($batchId)
    {
        $this->batchId = $batchId;
        $this->updateBeneficiaryList();
        $this->selectedRow = 0;
    }

    public function updateBeneficiaryList()
    {
        $focalUserId = 2;

        $decryptedId = Crypt::decrypt($this->batchId);

        $this->beneficiaries = Implementation::where('users_id', $focalUserId)
            ->join('batches', 'implementations.id', '=', 'batches.implementations_id')
            ->join('beneficiaries', 'batches.id', '=', 'beneficiaries.batches_id')
            ->where('batches.id', $decryptedId)
            ->select(
                DB::raw('beneficiaries.*'),
            )
            ->get()
            ->toArray();

        $encryptedId = encrypt($this->beneficiaries[0]['id']);
        $this->dispatch('change-beneficiary', beneficiaryId: $encryptedId);
    }

    public function mount()
    {
        $focalUserId = 2;
        $this->before = Carbon::now()->startOfYear();
        $this->after = Carbon::now()->endOfYear();

        $defaultBeneficiaryId = Implementation::where('users_id', $focalUserId)
            ->join('batches', 'implementations.id', '=', 'batches.implementations_id')
            ->join('beneficiaries', 'batches.id', '=', 'beneficiaries.batches_id')
            ->whereBetween('batches.created_at', [$this->before, $this->after])
            ->value('beneficiaries.id');

        $this->beneficiaries = Implementation::where('users_id', $focalUserId)
            ->join('batches', 'implementations.id', '=', 'batches.implementations_id')
            ->join('beneficiaries', 'batches.id', '=', 'beneficiaries.batches_id')
            ->where('batches.id', $defaultBeneficiaryId)
            ->select(
                DB::raw('beneficiaries.*'),
            )
            ->get()
            ->toArray();
    }
    public function render()
    {
        return view('livewire.focal.implementations.list-of-beneficiaries');
    }
}
