<?php

namespace App\Livewire\Coordinator;

use App\Livewire\Focal\Dashboard;
use App\Models\Batch;
use App\Models\User;
use Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Assignments | TU-Efficient')]
class Assignments extends Component
{
    #[Locked]
    public $batchId;
    #[Locked]
    public $projectId;
    #[Locked]
    public $batches;
    public $default_on_page = 30;
    public $batches_on_page = 30;
    public $selectedBatchRow = 0;

    #[Locked]
    public $beneficiaries;
    #[Locked]
    public $full_name;
    #[Locked]
    public $location;
    #[Locked]
    public $accessCode;

    # ------------------------------------------
    public $start;
    public $end;
    public $defaultStart;
    public $defaultEnd;


    #[On('start-change')]
    public function setStartDate($value)
    {
        $choosenDate = date('Y-m-d', strtotime($value));
        $currentTime = date('H:i:s', strtotime(now()));

        $this->start = $choosenDate . ' ' . $currentTime;
        $this->batches_on_page = $this->default_on_page;

        $this->setBatchAssignments();

        $this->selectedBatchRow = 0;

        $this->dispatch('init-reload')->self();
    }

    #[On('end-change')]
    public function setEndDate($value)
    {
        $choosenDate = date('Y-m-d', strtotime($value));
        $currentTime = date('H:i:s', strtotime(now()));

        $this->end = $choosenDate . ' ' . $currentTime;
        $this->batches_on_page = $this->default_on_page;

        $this->setBatchAssignments();

        $this->selectedBatchRow = 0;

        $this->dispatch('init-reload')->self();
    }

    public function selectBatchRow($key, $encryptedId)
    {
        $this->selectedBatchRow = $key;
        $this->batchId = decrypt($encryptedId);
        $this->batches_on_page = $this->default_on_page;

        $this->setBeneficiaryList();
        $this->selectedBeneficiaryRow = 0;

        $this->dispatch('init-reload')->self();
    }

    public function setBatchAssignments()
    {
        $coordinatorUserId = Auth::user()->id;

        $this->batches = User::where('users_id', $coordinatorUserId)
            ->join('assignments', 'users.id', '=', 'assignments.users_id')
            ->join('batches', 'batches.id', '=', 'assignments.batches_id')
            ->join('beneficiaries', 'batches.id', '=', 'beneficiaries.batches_id')
            ->select(
                DB::raw('batches.id'),
                DB::raw('batches.batch_num'),
                DB::raw('batches.barangay_name'),
                DB::raw('batches.slots_allocated'),
                DB::raw('COUNT(DISTINCT beneficiaries.id) AS current_slots'),
                DB::raw('batches.approval_status'),
                DB::raw('batches.submission_status'),
                DB::raw('batches.created_at'),
                DB::raw('batches.updated_at'),
            )
            ->groupBy(
                'batches.id',
                'batches.batch_num',
                'batches.barangay_name',
                'batches.slots_allocated',
                'batches.approval_status',
                'batches.submission_status',
                'batches.created_at',
                'batches.updated_at'
            )
            ->latest()
            ->take($this->batches_on_page)
            ->get()
            ->toArray();

        $this->batchId = $this->batches[0]['id'] ?? null;
    }

    public function setBeneficiaryList()
    {
        $coordinatorUserId = Auth::user()->id;

        $this->beneficiaries = User::where('users_id', $coordinatorUserId)
            ->join('assignments', 'users.id', '=', 'assignments.users_id')
            ->join('batches', 'batches.id', '=', 'assignments.batches_id')
            ->join('beneficiaries', 'batches.id', '=', 'beneficiaries.batches_id')
            ->where('batches.id', $this->batchId)
            ->select(
                DB::raw('beneficiaries.id AS id'),
                DB::raw('beneficiaries.first_name AS first_name'),
                DB::raw('beneficiaries.middle_name AS middle_name'),
                DB::raw('beneficiaries.last_name AS last_name'),
                DB::raw('beneficiaries.extension_name AS extension_name'),
                DB::raw('beneficiaries.birthdate AS birthdate'),
                DB::raw('beneficiaries.contact_num AS contact_num')
            )
            ->get()
            ->toArray();

        $this->location = Batch::join('implementations', 'implementations.id', '=', 'batches.implementations_id')
            ->where('batches.id', $this->batchId)
            ->select(
                DB::raw('implementations.district'),
                DB::raw('implementations.city_municipality'),
                DB::raw('implementations.province'),
                DB::raw('batches.barangay_name'),
            )
            ->first()
            ->toArray();

        $this->accessCode = Batch::join('codes', 'batches.id', '=', 'codes.batches_id')
            ->where('batches.id', $this->batchId)
            ->select(
                DB::raw('codes.access_code'),
            )
            ->first()
            ->toArray();
    }

    public function loadMoreBatches()
    {
        $this->batches_on_page += $this->default_on_page;

        $this->setBatchAssignments();
        $this->dispatch('init-reload')->self();
    }

    public function mount()
    {
        if (Auth::user()->user_type === 'focal') {
            $this->redirect(Dashboard::class);
        }

        $this->setBatchAssignments();
        $this->setBeneficiaryList();
    }

    public function render()
    {
        return view('livewire.coordinator.assignments');
    }
}
