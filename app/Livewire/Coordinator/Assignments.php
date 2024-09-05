<?php

namespace App\Livewire\Coordinator;

use App\Livewire\Focal\Dashboard;
use App\Models\Batch;
use App\Models\User;
use Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
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
    public $default_on_page = 15;
    public $batches_on_page = 15;
    public $selectedBatchRow = -1;
    public $searchBatches;

    # -------------------------------

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

        $this->selectedBatchRow = -1;

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
        if ($this->selectedBatchRow === $key) {
            $this->selectedBatchRow = -1;
            $this->batchId = null;
        } else {
            $this->selectedBatchRow = $key;
            $this->batchId = decrypt($encryptedId);
        }

        $this->setBeneficiaryList();
        $this->dispatch('init-reload')->self();
    }

    public function setBatchAssignments()
    {
        $coordinatorUserId = Auth::user()->id;
        $batchNumPrefix = config('settings.batch_number_prefix', 'DCFO-BN-');

        $this->batches = User::where('users_id', $coordinatorUserId)
            ->join('assignments', 'users.id', '=', 'assignments.users_id')
            ->join('batches', 'batches.id', '=', 'assignments.batches_id')
            ->join('beneficiaries', 'batches.id', '=', 'beneficiaries.batches_id')
            ->where('batches.batch_num', 'LIKE', $batchNumPrefix . '%' . $this->searchBatches . '%')
            ->select(
                [
                    'batches.id',
                    'batches.batch_num',
                    'batches.barangay_name',
                    'batches.slots_allocated',
                    'batches.approval_status',
                    'batches.submission_status',
                    'batches.created_at',
                    'batches.updated_at',
                    DB::raw('COUNT(DISTINCT beneficiaries.id) AS current_slots')
                ]
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

        // $this->batchId = $this->batches[0]['id'] ?? null;
    }

    public function setBeneficiaryList()
    {
        $coordinatorUserId = Auth::user()->id;

        if ($this->batchId) {
            $this->beneficiaries = User::where('users_id', $coordinatorUserId)
                ->join('assignments', 'users.id', '=', 'assignments.users_id')
                ->join('batches', 'batches.id', '=', 'assignments.batches_id')
                ->join('beneficiaries', 'batches.id', '=', 'beneficiaries.batches_id')
                ->where('batches.id', $this->batchId)
                ->select([
                    'beneficiaries.id',
                    'beneficiaries.first_name',
                    'beneficiaries.middle_name',
                    'beneficiaries.last_name',
                    'beneficiaries.extension_name AS extension_name',
                    'beneficiaries.birthdate AS birthdate',
                    'beneficiaries.contact_num AS contact_num'
                ])
                ->get()
                ->toArray();

            $this->location = Batch::join('implementations', 'implementations.id', '=', 'batches.implementations_id')
                ->where('batches.id', $this->batchId)
                ->select([
                    'implementations.district',
                    'implementations.city_municipality',
                    'implementations.province',
                    'batches.barangay_name',
                ])
                ->first()
                ->toArray();

            $this->accessCode = Batch::join('codes', 'batches.id', '=', 'codes.batches_id')
                ->where('batches.id', $this->batchId)
                ->select(
                    ['codes.access_code'],
                )
                ->first()
                ->toArray();
        } else {
            $this->beneficiaries = null;
            $this->location = null;
            $this->accessCode = null;
        }
    }

    #[Computed]
    public function getFullName($key)
    {
        $full_name = null;

        $first = $this->beneficiaries[$key]['first_name'];
        $middle = $this->beneficiaries[$key]['middle_name'];
        $last = $this->beneficiaries[$key]['last_name'];
        $ext = $this->beneficiaries[$key]['extension_name'];

        $full_name = $first;
        if ($middle) {
            $full_name .= ' ' . $middle;
        }

        $full_name .= ' ' . $last;

        if ($ext) {
            $full_name .= ' ' . $ext;
        }

        return $full_name;
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
