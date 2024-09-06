<?php

namespace App\Livewire\Coordinator;

use App\Livewire\Focal\Dashboard;
use App\Models\Batch;
use App\Models\Implementation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class Submissions extends Component
{
    #[Locked]
    public $beneficiaryId;
    #[Locked]
    public $beneficiaries;
    public $default_on_page = 30;
    public $beneficiaries_on_page = 30;
    public $selectedBeneficiaryRow = -1;
    public $searchBeneficiaries;

    # -------------------------------

    #[Locked]
    public $batchId;
    #[Locked]
    public $batches;
    public $currentBatch;
    public $selectedBatchRow = -1;
    public $searchBatches;

    # ------------------------------------------

    #[Locked]
    public $full_name;
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
        $this->beneficiaries_on_page = $this->default_on_page;

        $this->setBatchAssignments();
        $this->setBeneficiaryList();

        $this->selectedBeneficiaryRow = -1;

        $this->dispatch('init-reload')->self();
        $this->dispatch('scroll-to-top')->self();
    }

    #[On('end-change')]
    public function setEndDate($value)
    {
        $choosenDate = date('Y-m-d', strtotime($value));
        $currentTime = date('H:i:s', strtotime(now()));

        $this->end = $choosenDate . ' ' . $currentTime;
        $this->beneficiaries_on_page = $this->default_on_page;

        $this->setBatchAssignments();
        $this->setBeneficiaryList();

        $this->selectedBeneficiaryRow = -1;

        $this->dispatch('init-reload')->self();
        $this->dispatch('scroll-to-top')->self();
    }

    public function selectBatchRow($key, $encryptedId)
    {
        $this->selectedBatchRow = $key;
        $this->batchId = decrypt($encryptedId);
        $this->beneficiaries_on_page = $this->default_on_page;
        $this->currentBatch = $this->batches[$key]['batch_num'];

        $this->setBeneficiaryList();

        $this->dispatch('init-reload')->self();
        $this->dispatch('scroll-to-top')->self();
    }

    public function selectBeneficiaryRow($key, $encryptedId)
    {
        if ($this->selectedBeneficiaryRow === $key) {
            $this->selectedBeneficiaryRow = -1;
            $this->beneficiaryId = null;
        } else {
            $this->selectedBeneficiaryRow = $key;
            $this->beneficiaryId = decrypt($encryptedId);
        }

        $this->dispatch('init-reload')->self();
    }

    public function setBatchAssignments($batchId = null)
    {
        $coordinatorUserId = Auth::user()->id;
        $batchNumPrefix = config('settings.batch_number_prefix', 'DCFO-BN-');

        $this->batches = User::where('users.id', $coordinatorUserId)
            ->join('assignments', 'users.id', '=', 'assignments.users_id')
            ->join('batches', 'batches.id', '=', 'assignments.batches_id')
            ->whereBetween('batches.created_at', [$this->start, $this->end])
            ->where('batches.batch_num', 'LIKE', $batchNumPrefix . '%' . $this->searchBatches . '%')
            ->select(
                [
                    'batches.id',
                    'batches.batch_num',
                    'batches.barangay_name',
                ]
            )
            ->groupBy([
                'batches.id',
                'batches.batch_num',
                'batches.barangay_name',
            ])
            ->get()
            ->toArray();

        if ($batchId) {
            $currentBatch = Batch::where('id', $this->batchId)
                ->select('batch_num')
                ->first()
                ->toArray();

            $this->currentBatch = $currentBatch['batch_num'];
        } else {
            $this->batchId = $this->batches[0]['id'] ?? null;
            $this->currentBatch = $this->batches[0]['batch_num'] ?? 'None';
        }
    }

    public function setBeneficiaryList()
    {
        $coordinatorUserId = Auth::user()->id;

        $this->beneficiaries = User::where('users.id', $coordinatorUserId)
            ->join('assignments', 'users.id', '=', 'assignments.users_id')
            ->join('batches', 'batches.id', '=', 'assignments.batches_id')
            ->join('beneficiaries', 'batches.id', '=', 'beneficiaries.batches_id')
            ->where('batches.id', $this->batchId)
            ->select([
                'beneficiaries.*',
            ])
            ->take($this->beneficiaries_on_page)
            ->get()
            ->toArray();

    }

    public function loadMoreBatches()
    {
        $this->beneficiaries_on_page += $this->default_on_page;

        $this->setBeneficiaryList();
        $this->dispatch('init-reload')->self();
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

    public function mount($batchId = null)
    {
        if (Auth::user()->user_type === 'focal') {
            $this->redirect(Dashboard::class);
        }

        # sets the batchID, regardless if it has value or not
        $this->batchId = $batchId;

        $this->start = date('Y-m-d H:i:s', strtotime(now()->startOfYear()));
        $this->end = date('Y-m-d H:i:s', strtotime(now()));

        $this->defaultStart = date('m/d/Y', strtotime($this->start));
        $this->defaultEnd = date('m/d/Y', strtotime($this->end));

        $this->setBatchAssignments($batchId);
        $this->setBeneficiaryList();
    }
    public function render()
    {
        return view('livewire.coordinator.submissions');
    }
}
