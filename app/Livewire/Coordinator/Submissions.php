<?php

namespace App\Livewire\Coordinator;

use App\Livewire\Focal\Dashboard;
use App\Models\Assignment;
use App\Models\Batch;
use App\Models\Beneficiary;
use App\Models\Implementation;
use App\Models\User;
use App\Models\UserSetting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Submissions | TU-Efficient')]
class Submissions extends Component
{
    #[Locked]
    public $beneficiaryId;
    #[Locked]
    public $batchId;
    public $defaultBatches_on_page = 15;
    public $defaultBeneficiaries_on_page = 30;
    public $batches_on_page = 15;
    public $beneficiaries_on_page = 30;
    public $selectedBatchRow = -1;
    public $selectedBeneficiaryRow = -1;
    public $searchBeneficiaries;
    public $searchBatches;
    public $batchNumPrefix;

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
        $this->batches_on_page = $this->defaultBatches_on_page;
        $this->beneficiaries_on_page = $this->defaultBeneficiaries_on_page;

        $this->batchId = null;

        $this->selectedBatchRow = -1;
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
        $this->batches_on_page = $this->defaultBatches_on_page;
        $this->beneficiaries_on_page = $this->defaultBeneficiaries_on_page;

        $this->batchId = null;

        $this->selectedBatchRow = -1;
        $this->selectedBeneficiaryRow = -1;

        $this->dispatch('init-reload')->self();
        $this->dispatch('scroll-to-top')->self();
    }

    public function selectBatchRow($key, $encryptedId)
    {
        $this->selectedBatchRow = $key;
        $this->batchId = decrypt($encryptedId);
        $this->beneficiaries_on_page = $this->defaultBeneficiaries_on_page;

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


    #[Computed]
    public function batches()
    {
        $batches = Batch::join('assignments', 'batches.id', '=', 'assignments.batches_id')
            ->where('assignments.users_id', Auth::id())
            ->whereBetween('batches.created_at', [$this->start, $this->end])
            ->where('batches.batch_num', 'LIKE', $this->batchNumPrefix . '%' . $this->searchBatches . '%')
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
            ->orderBy('batches.id', 'desc')
            ->get();

        return $batches;
    }

    #[Computed]
    public function beneficiaries()
    {
        $coordinatorUserId = Auth::user()->id;

        $beneficiaries = Batch::join('assignments', 'batches.id', '=', 'assignments.batches_id')
            ->join('beneficiaries', 'batches.id', '=', 'beneficiaries.batches_id')
            ->where('assignments.users_id', $coordinatorUserId)
            ->where('batches.id', $this->batchId)
            ->select([
                'beneficiaries.*',
            ])
            ->take($this->beneficiaries_on_page)
            ->get();

        return $beneficiaries;
    }

    #[Computed]
    public function getIdType()
    {
        $type_of_id = null;

        if ($this->beneficiaryId) {

            if (str_contains($this->beneficiaries[$this->selectedBeneficiaryRow]->type_of_id, 'PWD')) {
                $type_of_id = 'PWD ID';
            } else if (str_contains($this->beneficiaries[$this->selectedBeneficiaryRow]->type_of_id, 'COMELEC')) {
                $type_of_id = 'Voter\'s ID';
            } else if (str_contains($this->beneficiaries[$this->selectedBeneficiaryRow]->type_of_id, 'PhilID')) {
                $type_of_id = 'PhilID';
            } else if (str_contains($this->beneficiaries[$this->selectedBeneficiaryRow]->type_of_id, '4Ps')) {
                $type_of_id = '4Ps ID';
            } else if (str_contains($this->beneficiaries[$this->selectedBeneficiaryRow]->type_of_id, 'IBP')) {
                $type_of_id = 'IBP ID';
            } else {
                $type_of_id = $this->beneficiaries[$this->selectedBeneficiaryRow]->type_of_id;
            }

        }

        return $type_of_id;
    }
    #[Computed]
    public function batchesCount()
    {
        $coordinatorUserId = Auth::user()->id;

        $batchesCount = Assignment::join('batches', 'batches.id', '=', 'assignments.batches_id')
            ->where('assignments.users_id', $coordinatorUserId)
            ->whereBetween('batches.created_at', [$this->start, $this->end])
            ->count();

        return $batchesCount;
    }

    #[Computed]
    public function beneficiarySlots()
    {
        $beneficiarySlots = 0;

        if ($this->batches->isNotEmpty()) {

            $totalCount = Beneficiary::join('batches', 'batches.id', '=', 'beneficiaries.batches_id')
                ->where('batches.id', $this->batchId)
                ->count();

            $beneficiarySlots = $totalCount;

        }

        return $beneficiarySlots;
    }

    #[Computed]
    public function currentBatch()
    {
        $currentBatch = null;

        if ($this->batchId) {
            $currentBatch = Batch::where('id', $this->batchId)
                ->first()->batch_num;
        } else if ($this->batches->isNotEmpty()) {
            $currentBatch = Batch::where('id', $this->batches[0]->id)
                ->first()->batch_num;
        } else {
            $currentBatch = 'None';
        }



        return $currentBatch;
    }


    public function loadMoreBeneficiaries()
    {
        $this->beneficiaries_on_page += $this->defaultBeneficiaries_on_page;
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

    public function checkIfBatchIdExists()
    {
        if (!$this->batchId) {
            $this->batchId = $this->batches[0]->id;
        }
        // if ($this->batches->isNotEmpty())

    }

    public function mount($batchId = null, $coordinatorId = null)
    {
        if (Auth::user()->user_type !== 'coordinator') {
            $this->redirectIntended();
        }

        if ($coordinatorId !== null) {
            if ($coordinatorId && Auth::user()->id === $coordinatorId) {
                $this->redirectIntended();
            }
        }

        if ($batchId !== null) {
            $this->batchId = decrypt($batchId);
        } else {
            $this->batchId = $batchId;
        }

        $settings = UserSetting::where('users_id', Auth::id())
            ->pluck('value', 'key');
        $this->batchNumPrefix = $settings->get('batch_num_prefix', config('settings.batch_number_prefix'));

        $this->start = date('Y-m-d H:i:s', strtotime(now()->startOfYear()));
        $this->end = date('Y-m-d H:i:s', strtotime(now()));

        $this->defaultStart = date('m/d/Y', strtotime($this->start));
        $this->defaultEnd = date('m/d/Y', strtotime($this->end));

        $this->checkIfBatchIdExists();
    }
    public function render()
    {
        return view('livewire.coordinator.submissions');
    }
}
