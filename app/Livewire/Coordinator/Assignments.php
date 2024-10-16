<?php

namespace App\Livewire\Coordinator;

use App\Livewire\Focal\Dashboard;
use App\Models\Assignment;
use App\Models\Batch;
use App\Models\Beneficiary;
use App\Models\Code;
use App\Models\User;
use App\Models\UserSetting;
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
    public $passedBatchId;
    public $batchNumPrefix;
    public $viewBatchModal = false;
    public $batchesCount;
    public $defaultBatches_on_page = 15;
    public $defaultBeneficiaries_on_page = 30;
    public $batches_on_page = 15;
    public $beneficiaries_on_page = 30;
    public $selectedBatchRow = -1;
    public $searchBatches;
    public $showAlert = false;
    public $alertMessage = '';

    # --------------------------------------------------------------------------

    public $start;
    public $end;
    public $defaultStart;
    public $defaultEnd;

    public $approvalStatuses = [
        'approved' => true,
        'pending' => true,
    ];

    public $submissionStatuses = [
        'submitted' => true,
        'encoding' => true,
        'unopened' => true,
        'revalidate' => true,
    ];

    public $filter = [];

    # --------------------------------------------------------------------------

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

    public function viewAssignment($encryptedId)
    {
        $this->passedBatchId = $encryptedId;
        $this->viewBatchModal = true;
    }

    public function applyFilter()
    {
        $this->filter = [
            'approval_status' => $this->approvalStatuses,
            'submission_status' => $this->submissionStatuses,
        ];

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
        $approvalStatuses = array_keys(array_filter($this->filter['approval_status']));
        $submissionStatuses = array_keys(array_filter($this->filter['submission_status']));

        $batches = Batch::join('assignments', 'batches.id', '=', 'assignments.batches_id')
            ->where('assignments.users_id', Auth::id())
            ->when(!empty($approvalStatuses), function ($q) use ($approvalStatuses) {
                $q->whereIn('batches.approval_status', $approvalStatuses);
            })
            ->when(!empty($submissionStatuses), function ($q) use ($submissionStatuses) {
                $q->whereIn('batches.submission_status', $submissionStatuses);
            })
            ->whereBetween('batches.created_at', [$this->start, $this->end])
            ->where('batches.batch_num', 'LIKE', $this->batchNumPrefix . '%' . $this->searchBatches . '%')
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
            ->take($this->batches_on_page)
            ->latest('batches.updated_at')
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
        $approvalStatuses = array_keys(array_filter($this->filter['approval_status']));
        $submissionStatuses = array_keys(array_filter($this->filter['submission_status']));

        $this->batchesCount = Batch::join('assignments', 'batches.id', '=', 'assignments.batches_id')
            ->where('assignments.users_id', Auth::id())
            ->whereBetween('batches.created_at', [$this->start, $this->end])
            ->when(!empty($approvalStatuses), function ($q) use ($approvalStatuses) {
                $q->whereIn('batches.approval_status', $approvalStatuses);
            })
            ->when(!empty($submissionStatuses), function ($q) use ($submissionStatuses) {
                $q->whereIn('batches.submission_status', $submissionStatuses);
            })
            ->where('batches.batch_num', 'LIKE', $this->batchNumPrefix . '%' . $this->searchBatches . '%')
            ->count();
    }

    #[Computed]
    public function beneficiaries()
    {
        $beneficiaries = Batch::join('beneficiaries', 'batches.id', '=', 'beneficiaries.batches_id')
            ->where('batches.id', $this->batchId)
            ->select([
                'beneficiaries.id',
                'beneficiaries.first_name',
                'beneficiaries.middle_name',
                'beneficiaries.last_name',
                'beneficiaries.extension_name',
                'beneficiaries.birthdate',
                'beneficiaries.contact_num'
            ])
            ->orderBy('beneficiaries.id')
            ->take($this->beneficiaries_on_page)
            ->get();

        return $beneficiaries;


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
                ->where('codes.is_accessible', 'yes')
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
        $submissions = Code::where('batches_id', $this->batchId)
            ->where('is_accessible', 'no')
            ->count();

        return $submissions;
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

    #[On('refreshAfterOpening')]
    public function refreshAfterOpening($message)
    {
        unset($this->batches);
        unset($this->accessCode);

        $this->showAlert = true;
        $this->alertMessage = $message;
        $this->dispatch('show-alert');
        $this->dispatch('init-reload')->self();
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

        $settings = UserSetting::where('users_id', Auth::id())
            ->pluck('value', 'key');
        $this->batchNumPrefix = $settings->get('batch_number_prefix', config('settings.batch_number_prefix', 'DCFO-BN-'));

        $this->filter = [
            'approval_status' => $this->approvalStatuses,
            'submission_status' => $this->submissionStatuses,
        ];
    }

    public function render()
    {
        $this->setBatchesCount();
        return view('livewire.coordinator.assignments');
    }
}
