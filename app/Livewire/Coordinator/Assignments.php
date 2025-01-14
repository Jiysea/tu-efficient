<?php

namespace App\Livewire\Coordinator;

use App\Models\Assignment;
use App\Models\Batch;
use App\Models\Beneficiary;
use App\Models\Code;
use App\Models\Implementation;
use App\Models\UserSetting;
use App\Services\Essential;
use App\Services\JaccardSimilarity;
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
    #[Locked]
    public $batchNumPrefix;
    #[Locked]
    public $duplicationThreshold;
    #[Locked]
    public $defaultShowDuplicates;
    public $viewBatchModal = false;
    public $defaultBatches_on_page = 15;
    public $defaultBeneficiaries_on_page = 30;
    public $batches_on_page = 15;
    public $beneficiaries_on_page = 30;
    public $selectedBatchRow = -1;
    public $searchBatches;
    public $alerts = [];

    # --------------------------------------------------------------------------

    public $start;
    public $end;
    public $calendarStart;
    public $calendarEnd;
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
    public $oldFilter = [];

    # --------------------------------------------------------------------------

    public function viewAssignment($key, $encryptedId)
    {
        $this->selectedBatchRow = $key;
        $this->batchId = $encryptedId;

        $this->beneficiaries_on_page = $this->defaultBeneficiaries_on_page;

        $this->dispatch('init-reload')->self();
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
            $this->batchId = $encryptedId;
            $this->beneficiaries_on_page = $this->defaultBeneficiaries_on_page;
        }

        $this->dispatch('scroll-top-beneficiaries')->self();
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
                    'implementations.status as implementation_status'
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
            ->where('batches.id', $this->batchId ? decrypt($this->batchId) : null)
            ->select([
                'beneficiaries.*'
            ])
            ->orderBy('beneficiaries.last_name', 'asc')
            ->take($this->beneficiaries_on_page)
            ->get();

        return $beneficiaries;
    }

    #[Computed]
    public function rowColorIndicator($beneficiary)
    {
        # This will be the returned value if the other "if" statements are false
        # "default" means it has neither a possible nor perfect duplicate
        $indicator = 'default';

        # Turning on show duplicates setting requires extensive memory usage
        if ($this->defaultShowDuplicates) {

            $thresholdResult = $this->isOverThreshold($beneficiary);

            # If the $thresholdResult returns an array, this will basically satisfy the condition
            if ($thresholdResult) {
                foreach ($thresholdResult as $result) {
                    $databaseBeneficiary = Beneficiary::find(decrypt($result['id']));

                    # If all the results are only possible duplicates...
                    if (!$result['is_perfect'] && $beneficiary->created_at > $databaseBeneficiary->created_at) {
                        $indicator = 'possible';
                    }

                    # If one of the results is a perfect duplicate...
                    if ($result['is_perfect'] && $beneficiary->beneficiary_type === 'special case') {
                        $indicator = 'perfect';
                        break; # break the loop since having a perfect duplicate has more priority than a possible one
                    }
                }
            }

        }

        # If show duplicates setting is off and the beneficiary is a special case...
        if ($beneficiary->beneficiary_type === 'special case') {
            $indicator = 'perfect';
        }

        return $indicator;
    }

    #[Computed]
    public function isOverThreshold($person)
    {
        $results = null;

        if ($this->beneficiaries?->isNotEmpty()) {
            $results = JaccardSimilarity::isOverThreshold($person, $this->duplicationThreshold);
        }

        return $results;
    }


    #[Computed]
    public function location()
    {
        $location = Batch::join('implementations', 'implementations.id', '=', 'batches.implementations_id')
            ->where('batches.id', $this->batchId ? decrypt($this->batchId) : null)
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
    }

    #[Computed]
    public function accessCode()
    {
        $accessCode = Batch::join('codes', 'batches.id', '=', 'codes.batches_id')
            ->where('batches.id', $this->batchId ? decrypt($this->batchId) : null)
            ->where('codes.is_accessible', 'yes')
            ->select(['codes.access_code'])
            ->groupBy([
                'codes.access_code',
            ])
            ->first();

        return $accessCode;

    }

    #[Computed]
    public function submissions()
    {
        $submissions = Code::where('batches_id', $this->batchId ? decrypt($this->batchId) : null)
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
        $this->redirectRoute(
            'coordinator.submissions',
            [
                'batchId' => $this->batchId,
                'coordinatorId' => encrypt(Auth::user()->id)
            ]
        );
    }

    #[On('alertNotification')]
    public function alertNotification($type = null, $message, $color)
    {
        $dateTimeFromEnd = $this->end;
        $value = substr($dateTimeFromEnd, 0, 10);

        $choosenDate = date('Y-m-d', strtotime($value));
        $currentTime = date('H:i:s', strtotime(now()));
        $this->end = $choosenDate . ' ' . $currentTime;

        unset($this->batches, $this->accessCode);

        $this->alerts[] = [
            'message' => $message,
            'id' => uniqid(),
            'color' => $color
        ];

        $this->dispatch('init-reload')->self();
    }

    public function removeAlert($id)
    {
        $this->alerts = array_filter($this->alerts, function ($alert) use ($id) {
            return $alert['id'] !== $id;
        });
    }

    # It's a Livewire `Hook` for properties so the system can take action
    # when a specific property has updated its state. 
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

    #[Computed]
    public function personalSettings()
    {
        return UserSetting::where('users_id', auth()->id())
            ->pluck('value', 'key');
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
        $this->start = now()->subYear()->startOfYear()->format('Y-m-d H:i:s');
        $this->end = now()->endOfDay()->format('Y-m-d H:i:s');

        $this->calendarStart = Carbon::parse($this->start)->format('m/d/Y');
        $this->calendarEnd = Carbon::parse($this->end)->format('m/d/Y');

        $this->defaultStart = $this->calendarStart;
        $this->defaultEnd = $this->calendarEnd;
    }

    public function render()
    {
        $this->batchNumPrefix = $this->globalSettings->get('batch_number_prefix', config('settings.batch_number_prefix', 'DCFO-BN-'));
        $this->duplicationThreshold = intval($this->globalSettings->get('duplication_threshold', config('settings.duplication_threshold')));
        $this->defaultShowDuplicates = intval($this->personalSettings->get('default_show_duplicates', config('settings.default_show_duplicates')));
        return view('livewire.coordinator.assignments');
    }
}
