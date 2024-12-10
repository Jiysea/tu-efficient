<?php

namespace App\Livewire\Coordinator;

use App\Models\Assignment;
use App\Models\Batch;
use App\Models\Beneficiary;
use App\Models\Code;
use App\Models\UserSetting;
use App\Services\Essential;
use Arr;
use Auth;
use Carbon\Carbon;
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
    public $calendarStart;
    public $calendarEnd;
    public $defaultStart;
    public $defaultEnd;

    public $approvalStatuses = [
        'approved' => false,
        'pending' => true,
    ];

    public $submissionStatuses = [
        'submitted' => true,
        'encoding' => true,
        'unopened' => true,
        'revalidate' => true,
    ];

    public $filter = [];
    public $oldFilter = [];

    # --------------------------------------------------------------------------

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
        $batchIds = Assignment::where('users_id', Auth::id())
            ->distinct()
            ->pluck('batches_id');

        $batches = Batch::join('implementations', 'implementations.id', '=', 'batches.implementations_id')
            ->whereIn('batches.id', $batchIds)
            ->when(isset($approvalStatuses) && !empty($approvalStatuses), function ($q) use ($approvalStatuses) {
                $q->whereIn('batches.approval_status', $approvalStatuses);
            })
            ->when(isset($submissionStatuses) && !empty($submissionStatuses), function ($q) use ($submissionStatuses) {
                $q->whereIn('batches.submission_status', $submissionStatuses);
            })
            ->whereBetween('batches.created_at', [$this->start, $this->end])
            ->when($this->searchBatches, function ($q) {
                $q->where(function ($query) {
                    $query->where('batches.batch_num', 'LIKE', $this->batchNumPrefix . '%' . $this->searchBatches . '%')
                        ->orWhere('batches.sector_title', 'LIKE', '%' . $this->searchBatches . '%')
                        ->orWhere('batches.barangay_name', 'LIKE', '%' . $this->searchBatches . '%');
                });
            })
            ->select(
                [
                    'batches.id',
                    'batches.batch_num',
                    'batches.is_sectoral',
                    'batches.sector_title',
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

    #[Computed]
    public function batchesCount()
    {
        $approvalStatuses = array_keys(array_filter($this->filter['approval_status']));
        $submissionStatuses = array_keys(array_filter($this->filter['submission_status']));

        $batchIds = Assignment::where('users_id', Auth::id())
            ->distinct()
            ->pluck('batches_id');

        $batchesCount = Batch::join('implementations', 'implementations.id', '=', 'batches.implementations_id')
            ->whereIn('batches.id', $batchIds)
            ->whereBetween('batches.created_at', [$this->start, $this->end])
            ->when(!empty($approvalStatuses), function ($q) use ($approvalStatuses) {
                $q->whereIn('batches.approval_status', $approvalStatuses);
            })
            ->when(!empty($submissionStatuses), function ($q) use ($submissionStatuses) {
                $q->whereIn('batches.submission_status', $submissionStatuses);
            })
            ->when($this->searchBatches, function ($q) {
                $q->where(function ($query) {
                    $query->where('batches.batch_num', 'LIKE', $this->batchNumPrefix . '%' . $this->searchBatches . '%')
                        ->orWhere('batches.sector_title', 'LIKE', '%' . $this->searchBatches . '%')
                        ->orWhere('batches.barangay_name', 'LIKE', '%' . $this->searchBatches . '%');
                });
            })
            ->count();
        return $batchesCount;
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
                'beneficiaries.contact_num',
                'beneficiaries.beneficiary_type',
            ])
            ->orderBy('beneficiaries.last_name', 'asc')
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
                    'implementations.city_municipality',
                    'implementations.province',
                    'batches.is_sectoral',
                    'batches.sector_title',
                    'batches.barangay_name',
                    'batches.district',
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
    public function full_last_first($person)
    {

        $full_name = $person->last_name;
        $full_name .= ', ' . $person->first_name;

        if ($person->middle_name) {
            $full_name .= ' ' . $person->middle_name;
        }

        if ($person->extension_name) {
            $full_name .= ' ' . $person->extension_name;
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

    #[On('view-batch')]
    public function viewBatchEvents($message)
    {
        unset($this->batches);
        unset($this->accessCode);

        $this->showAlert = true;
        $this->alertMessage = $message;
        $this->dispatch('show-alert');
        $this->dispatch('init-reload')->self();
    }

    #[On('error-handling')]
    public function errorHandling($message)
    {
        unset($this->batches, $this->accessCode);

        $this->showAlert = true;
        $this->alertMessage = $message;
        $this->dispatch('show-alert');
        $this->dispatch('init-reload')->self();
    }

    public function updated($prop)
    {
        if ($prop === 'calendarStart') {
            $format = Essential::extract_date($this->calendarStart, false);
            if ($format !== 'm/d/Y') {
                $this->calendarStart = $this->defaultStart;
                return;
            }

            $choosenDate = Carbon::createFromFormat('m/d/Y', $this->calendarStart)->format('Y-m-d');
            $currentTime = now()->startOfDay()->format('H:i:s');

            $this->start = $choosenDate . ' ' . $currentTime;

            if (strtotime($this->start) > strtotime($this->end)) {
                $end = Carbon::parse($this->start)->addMonth()->endOfDay()->format('Y-m-d H:i:s');
                $this->end = $end;
                $this->calendarEnd = Carbon::parse($this->end)->format('m/d/Y');
            }

            $this->batches_on_page = $this->defaultBatches_on_page;
            $this->beneficiaries_on_page = $this->defaultBeneficiaries_on_page;

            $this->batchId = null;

            $this->selectedBatchRow = -1;

            $this->dispatch('init-reload')->self();
            $this->dispatch('scroll-top-batches')->self();
            $this->dispatch('scroll-top-beneficiaries')->self();

        } elseif ($prop === 'calendarEnd') {
            $format = Essential::extract_date($this->calendarEnd, false);
            if ($format !== 'm/d/Y') {
                $this->calendarEnd = $this->defaultEnd;
                return;
            }

            $choosenDate = Carbon::createFromFormat('m/d/Y', $this->calendarEnd)->format('Y-m-d');
            $currentTime = now()->endOfDay()->format('H:i:s');

            $this->end = $choosenDate . ' ' . $currentTime;

            if (strtotime($this->start) > strtotime($this->end)) {
                $start = Carbon::parse($this->end)->subMonth()->startOfDay()->format('Y-m-d H:i:s');
                $this->start = $start;
                $this->calendarStart = Carbon::parse($this->start)->format('m/d/Y');
            }

            $this->batches_on_page = $this->defaultBatches_on_page;
            $this->beneficiaries_on_page = $this->defaultBeneficiaries_on_page;

            $this->batchId = null;

            $this->selectedBatchRow = -1;

            $this->dispatch('init-reload')->self();
            $this->dispatch('scroll-top-batches')->self();
            $this->dispatch('scroll-top-beneficiaries')->self();
        }
    }

    #[Computed]
    public function globalSettings()
    {
        return UserSetting::join('users', 'users.id', '=', 'user_settings.users_id')
            ->where('users.user_type', 'focal')
            ->pluck('user_settings.value', 'user_settings.key');
    }

    public function mount()
    {
        $user = Auth::user();
        if ($user->user_type !== 'coordinator') {
            $this->redirectIntended();
        }

        $this->filter = [
            'approval_status' => $this->approvalStatuses,
            'submission_status' => $this->submissionStatuses,
        ];

        $this->oldFilter = [
            'approval_status' => $this->approvalStatuses,
            'submission_status' => $this->submissionStatuses,
        ];

        /*
         *  Setting default dates in the datepicker
         */
        $this->start = now()->startOfYear()->format('Y-m-d H:i:s');
        $this->end = now()->endOfDay()->format('Y-m-d H:i:s');

        $this->calendarStart = Carbon::parse($this->start)->format('m/d/Y');
        $this->calendarEnd = Carbon::parse($this->end)->format('m/d/Y');

        $this->defaultStart = $this->calendarStart;
        $this->defaultEnd = $this->calendarEnd;


    }

    public function render()
    {
        $this->batchNumPrefix = $this->globalSettings->get('batch_number_prefix', config('settings.batch_number_prefix', 'DCFO-BN-'));
        return view('livewire.coordinator.assignments');
    }
}
