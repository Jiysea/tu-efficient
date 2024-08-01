<?php

namespace App\Livewire\Focal\Implementations;

use App\Models\Implementation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class BatchAssignments extends Component
{
    public $selectedRow = 0;
    #[Locked]
    public $batchId;
    #[Locked]
    public $projectId;
    #[Locked]
    public $batches;

    public function selectRow($key, $encryptedId)
    {
        $this->selectedRow = $key;
        $this->batchId = $encryptedId;

        $this->dispatch('change-batch', batchId: $encryptedId);
    }

    #[On('change-project')]
    public function setCurrentProjectId($projectId)
    {
        $this->projectId = $projectId;
        $this->updateBatchAssignments();
        $this->selectedRow = 0;
    }

    public function updateBatchAssignments()
    {
        $focalUserId = 2;

        $decryptedId = Crypt::decrypt($this->projectId);

        $this->batches = Implementation::where('users_id', $focalUserId)
            ->join('batches', 'implementations.id', '=', 'batches.implementations_id')
            ->join('beneficiaries', 'batches.id', '=', 'beneficiaries.batches_id')
            ->where('implementations.id', $decryptedId)
            ->select(
                DB::raw('batches.id AS batches_id'),
                DB::raw('batches.barangay_name AS barangay_name'),
                DB::raw('batches.slots_allocated AS slots_allocated'),
                DB::raw('COUNT(DISTINCT beneficiaries.id) AS current_slots'),
                DB::raw('batches.approval_status AS approval_status')
            )
            ->groupBy('batches.id', 'barangay_name', 'slots_allocated', 'approval_status')
            ->get()
            ->toArray();

        $encryptedId = encrypt($this->batches[0]['batches_id']);
        $this->dispatch('change-batch', batchId: $encryptedId);
    }

    public function mount()
    {
        $focalUserId = 2;
        $this->before = Carbon::now()->startOfYear();
        $this->after = Carbon::now()->endOfYear();

        $defaultProjectId = Implementation::where('users_id', $focalUserId)
            ->whereBetween('created_at', [$this->before, $this->after])
            ->value('id');

        $this->batches = Implementation::where('users_id', $focalUserId)
            ->join('batches', 'implementations.id', '=', 'batches.implementations_id')
            ->join('beneficiaries', 'batches.id', '=', 'beneficiaries.batches_id')
            ->where('implementations.id', $defaultProjectId)
            ->select(
                DB::raw('batches.id AS batches_id'),
                DB::raw('batches.barangay_name AS barangay_name'),
                DB::raw('batches.slots_allocated AS slots_allocated'),
                DB::raw('COUNT(DISTINCT beneficiaries.id) AS current_slots'),
                DB::raw('batches.approval_status AS approval_status')
            )
            ->groupBy('batches.id', 'barangay_name', 'slots_allocated', 'approval_status')
            ->get()
            ->toArray();
    }

    public function render()
    {
        return view('livewire.focal.implementations.batch-assignments');
    }
}
