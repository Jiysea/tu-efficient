<?php

namespace App\Livewire\Coordinator\Assignments;

use App\Models\Implementation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class ListOfAssignments extends Component
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

    // #[On('change-project')]
    // public function setCurrentProjectId($projectId)
    // {
    //     $this->projectId = $projectId;
    //     $this->updateBatchAssignments();
    //     $this->selectedRow = 0;
    // }

    public function updateBatchAssignments()
    {
        $coordinatorUserId = Auth::user()->id;

        $this->batches = User::where('users_id', $coordinatorUserId)
            ->join('assignments', 'users.id', '=', 'assignments.users_id')
            ->join('batches', 'batches.id', '=', 'assignments.batches_id')
            ->join('beneficiaries', 'batches.id', '=', 'beneficiaries.batches_id')
            ->select(
                DB::raw('batches.id AS batches_id'),
                DB::raw('batches.batch_num AS batch_num'),
                DB::raw('batches.barangay_name AS barangay_name'),
                DB::raw('batches.slots_allocated AS slots_allocated'),
                DB::raw('COUNT(DISTINCT beneficiaries.id) AS current_slots'),
                DB::raw('batches.approval_status AS approval_status'),
                DB::raw('batches.submission_status AS submission_status')
            )
            ->groupBy('batches.id', 'batch_num', 'barangay_name', 'slots_allocated', 'approval_status', 'submission_status')
            ->get()
            ->toArray();

        $encryptedId = encrypt($this->batches[0]['batches_id']);
        $this->dispatch('change-batch', batchId: $encryptedId);
    }

    public function mount()
    {
        $coordinatorUserId = Auth::user()->id;

        $this->batches = User::where('users_id', $coordinatorUserId)
            ->join('assignments', 'users.id', '=', 'assignments.users_id')
            ->join('batches', 'batches.id', '=', 'assignments.batches_id')
            ->join('beneficiaries', 'batches.id', '=', 'beneficiaries.batches_id')
            ->select(
                DB::raw('batches.id AS batches_id'),
                DB::raw('batches.batch_num AS batch_num'),
                DB::raw('batches.barangay_name AS barangay_name'),
                DB::raw('batches.slots_allocated AS slots_allocated'),
                DB::raw('COUNT(DISTINCT beneficiaries.id) AS current_slots'),
                DB::raw('batches.approval_status AS approval_status'),
                DB::raw('batches.submission_status AS submission_status')
            )
            ->groupBy('batches.id', 'batch_num', 'barangay_name', 'slots_allocated', 'approval_status', 'submission_status')
            ->get()
            ->toArray();
    }
    public function render()
    {
        return view('livewire.coordinator.assignments.list-of-assignments');
    }
}
