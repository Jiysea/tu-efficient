<?php

namespace App\Livewire\Coordinator;

use App\Livewire\Focal\Dashboard;
use App\Models\Assignment;
use App\Models\Batch;
use App\Models\Beneficiary;
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
    public $batchesCount;
    public $defaultBatches_on_page = 15;
    public $defaultBeneficiaries_on_page = 30;
    public $batches_on_page = 15;
    public $beneficiaries_on_page = 30;
    public $selectedBatchRow = -1;
    public $searchBatches;

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

        $this->dispatch('init-reload')->self();
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
            $this->beneficiaries_on_page = $this->defaultBeneficiaries_on_page;
        }

        $this->dispatch('init-reload')->self();
    }

    #[Computed]
    public function batches()
    {
        $coordinatorUserId = Auth::user()->id;
        $batchNumPrefix = config('settings.batch_number_prefix', 'DCFO-BN-');

        $batches = Batch::join('assignments', 'batches.id', '=', 'assignments.batches_id')
            ->where('assignments.users_id', $coordinatorUserId)
            ->whereBetween('batches.created_at', [$this->start, $this->end])
            ->where('batches.batch_num', 'LIKE', $batchNumPrefix . '%' . $this->searchBatches . '%')
            ->select(
                [
                    'batches.id',
                    'batches.batch_num',
                    'batches.barangay_name',
                    'batches.slots_allocated',
                    'batches.approval_status',
                    'batches.submission_status',
                ]
            )
            ->orderBy('batches.id')
            ->take($this->batches_on_page)
            ->get();

        return $batches;
    }

    #[Computed]
    public function beneficiarySlots()
    {
        $beneficiarySlots = collect();

        foreach ($this->batches as $batch) {

            $totalCount = Beneficiary::join('batches', 'batches.id', '=', 'beneficiaries.batches_id')
                ->where('batches.id', $batch->id)
                ->count();

            $beneficiarySlots->push(
                $totalCount,
            );
        }
        return $beneficiarySlots;
    }

    public function setBatchesCount()
    {
        $coordinatorUserId = Auth::user()->id;

        $this->batchesCount = User::where('users.id', $coordinatorUserId)
            ->join('assignments', 'users.id', '=', 'assignments.users_id')
            ->join('batches', 'batches.id', '=', 'assignments.batches_id')
            ->whereBetween('batches.created_at', [$this->start, $this->end])
            ->count();
    }

    #[Computed]
    public function beneficiaries()
    {
        $coordinatorUserId = Auth::user()->id;

        if ($this->batchId) {
            $beneficiaries = Batch::join('beneficiaries', 'batches.id', '=', 'beneficiaries.batches_id')
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
                ->orderBy('beneficiaries.id')
                ->take($this->beneficiaries_on_page)
                ->get();

            return $beneficiaries;

        } else {
            $this->beneficiaries = null;
        }
    }

    #[Computed]
    public function location()
    {
        if ($this->batchId) {

            $location = Batch::join('implementations', 'implementations.id', '=', 'batches.implementations_id')
                ->where('batches.id', $this->batchId)
                ->select([
                    'implementations.district',
                    'implementations.city_municipality',
                    'implementations.province',
                    'batches.barangay_name',
                ])
                ->first();

            return $location;

        } else {
            return null;
        }
    }

    #[Computed]
    public function accessCode()
    {
        if ($this->batchId) {
            $accessCode = Batch::join('codes', 'batches.id', '=', 'codes.batches_id')
                ->where('batches.id', $this->batchId)
                ->where('codes.accessible', 'yes')
                ->select(['codes.access_code'])
                ->groupBy([
                    'codes.access_code',
                ])
                ->first();

            return $accessCode;

        } else {
            $this->accessCode = null;
        }
    }

    #[Computed]
    public function submissions()
    {
        if ($this->batchId) {
            $submissions = Batch::join('codes', 'batches.id', '=', 'codes.batches_id')
                ->where('batches.id', $this->batchId)
                ->where('codes.accessible', 'no')
                ->select(
                    [
                        DB::raw('COUNT(DISTINCT codes.id) AS total_count')
                    ],
                )
                ->groupBy('codes.access_code')
                ->first();

            return $submissions;

        } else {
            return null;
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
        $this->batches_on_page += $this->defaultBatches_on_page;
        $this->dispatch('init-reload')->self();
    }

    public function loadMoreBeneficiaries()
    {
        $this->beneficiaries_on_page += $this->defaultBeneficiaries_on_page;
        $this->dispatch('init-reload')->self();
    }

    public function viewList()
    {
        if ($this->batchId) {
            $this->redirectRoute(
                'coordinator.submissions',
                [
                    'batchId' => encrypt($this->batchId),
                    'coordinatorId' => encrypt(Auth::user()->id)
                ]
            );
        }
    }

    public function mount()
    {
        if (Auth::user()->user_type !== 'coordinator') {
            $this->redirectIntended();
        }

        /*
         *  Setting default dates in the datepicker
         */
        $this->start = date('Y-m-d H:i:s', strtotime(now()->startOfYear()));
        $this->end = date('Y-m-d H:i:s', strtotime(now()));

        $this->defaultStart = date('m/d/Y', strtotime($this->start));
        $this->defaultEnd = date('m/d/Y', strtotime($this->end));
    }

    public function render()
    {
        $this->setBatchesCount();
        return view('livewire.coordinator.assignments');
    }
}
