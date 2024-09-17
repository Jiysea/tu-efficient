<?php

namespace App\Livewire\Focal;

use App\Livewire\Coordinator\Assignments;
use App\Models\Batch;
use App\Models\Beneficiary;
use App\Models\Implementation;
use Crypt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Implementations | TU-Efficient')]
class Implementations extends Component
{
    #[Locked]
    public $implementationId;
    #[Locked]
    public $batchId;
    #[Locked]
    public $beneficiaryId;

    # ------------------------------------------

    #[Locked]
    public $passedId;
    public $openProjectModal = false;
    public $openBatchModal = false;
    public $assignBatchesModal = false;

    # ------------------------------------------

    public $temporaryCount = 0; # debugging purposes
    public $searchProjects;
    public $searchBeneficiaries;
    public $showAlert = false;
    public $alertMessage = '';
    public $totalImplementations;
    public $implementations_on_page = 15;
    public $beneficiaries_on_page = 15;
    public $selectedImplementationRow = -1;
    public $selectedBatchRow = -1;
    public $selectedBeneficiaryRow = -1;
    public $remainingBatchSlots;
    public $beneficiarySlots = [];

    # ------------------------------------------

    public $start;
    public $end;
    public $defaultStart;
    public $defaultEnd;

    # ------------------------------------------

    #[On('start-change')]
    public function setStartDate($value)
    {
        $choosenDate = date('Y-m-d', strtotime($value));
        $currentTime = date('H:i:s', strtotime(now()));

        $this->start = $choosenDate . ' ' . $currentTime;
        $this->implementations_on_page = 15;
        $this->beneficiaries_on_page = 15;

        $this->passedId = null;
        $this->implementationId = null;
        $this->batchId = null;

        $this->selectedImplementationRow = -1;
        $this->selectedBatchRow = -1;
        $this->selectedBeneficiaryRow = -1;

        $this->dispatch('init-reload')->self();
    }

    #[On('end-change')]
    public function setEndDate($value)
    {
        $choosenDate = date('Y-m-d', strtotime($value));
        $currentTime = date('H:i:s', strtotime(now()));

        $this->end = $choosenDate . ' ' . $currentTime;
        $this->implementations_on_page = 15;
        $this->beneficiaries_on_page = 15;

        $this->passedId = null;
        $this->implementationId = null;
        $this->batchId = null;

        $this->selectedImplementationRow = -1;
        $this->selectedBatchRow = -1;
        $this->selectedBeneficiaryRow = -1;

        $this->dispatch('init-reload')->self();
    }

    public function viewProject(string $implementationId)
    {
        $this->passedId = $implementationId;
        $this->openProjectModal = true;
    }

    public function viewBatch(string $batchId)
    {
        $this->passedId = $batchId;
        $this->openBatchModal = true;
    }

    public function assignBatch()
    {
        $this->assignBatchesModal = true;
    }

    public function selectImplementationRow($key, $encryptedId)
    {

        if ($key === $this->selectedImplementationRow) {
            $this->selectedImplementationRow = -1;
            $this->implementationId = null;
        } else {
            $this->selectedImplementationRow = $key;
            $this->implementationId = Crypt::decrypt($encryptedId);
        }

        $this->passedId = null;

        $this->beneficiaries_on_page = 15;
        $this->batchId = null;
        $this->selectedBatchRow = -1;
        $this->selectedBeneficiaryRow = -1;

        $this->dispatch('init-reload')->self();
    }

    public function selectBatchRow($key, $encryptedId)
    {
        if ($key === $this->selectedBatchRow) {
            $this->selectedBatchRow = -1;
            $this->batchId = null;
        } else {
            $this->selectedBatchRow = $key;
            $this->batchId = Crypt::decrypt($encryptedId);
        }

        $this->passedId = null;

        $this->beneficiaries_on_page = 15;
        $this->beneficiaryId = null;
        $this->selectedBeneficiaryRow = -1;

        $this->dispatch('init-reload')->self();
    }

    public function selectBeneficiaryRow($key, $encryptedId)
    {
        $this->passedId = null;

        $this->selectedBeneficiaryRow = $key;
        $this->beneficiaryId = Crypt::decrypt($encryptedId);
    }

    # setListOfImplementations

    #[Computed]
    public function implementations()
    {
        $focalUserId = auth()->id();
        $projectNumPrefix = config('settings.project_number_prefix', 'XII-DCFO-');

        $implementations = Implementation::where('users_id', $focalUserId)
            ->whereBetween('created_at', [$this->start, $this->end])
            ->where('project_num', 'LIKE', $projectNumPrefix . '%' . $this->searchProjects . '%')
            ->latest('updated_at')
            ->take($this->implementations_on_page)
            ->get();

        return $implementations;
    }

    #[Computed]
    public function batches()
    {
        $focalUserId = auth()->id();

        $batches = Implementation::where('implementations.users_id', $focalUserId)
            ->join('batches', 'implementations.id', '=', 'batches.implementations_id')
            ->leftJoin('beneficiaries', 'batches.id', '=', 'beneficiaries.batches_id')
            ->where('implementations.id', $this->implementationId)
            ->select([
                'batches.id',
                'batches.barangay_name',
                'batches.slots_allocated',
                DB::raw('COUNT(DISTINCT beneficiaries.id) AS current_slots'),
                DB::raw('batches.approval_status AS approval_status')
            ])
            ->groupBy('batches.id', 'barangay_name', 'slots_allocated', 'approval_status')
            ->orderBy('batches.id', 'desc')
            ->get();

        return $batches;
    }

    #[Computed]
    public function beneficiaries()
    {
        $focalUserId = auth()->id();

        $beneficiaries = Implementation::where('implementations.users_id', $focalUserId)
            ->join('batches', 'implementations.id', '=', 'batches.implementations_id')
            ->join('beneficiaries', 'batches.id', '=', 'beneficiaries.batches_id')
            ->where('batches.id', $this->batchId)
            ->when($this->searchBeneficiaries, function ($q) {
                # Check if `searchBeneficiaries` contains 'num:' and filter by contact number
                if (str_contains($this->searchBeneficiaries, '#')) {
                    $searchValue = trim(str_replace('#', '', $this->searchBeneficiaries));

                    if (strpos($searchValue, '0') === 0) {
                        $searchValue = substr($searchValue, 1);
                    }
                    // dump($searchValue);
                    $q->where('beneficiaries.contact_num', 'LIKE', '%' . $searchValue . '%');
                } else {
                    // Otherwise, search by first, middle, or last name
                    $q->where(function ($query) {
                        $query->where('beneficiaries.first_name', 'LIKE', '%' . $this->searchBeneficiaries . '%')
                            ->orWhere('beneficiaries.middle_name', 'LIKE', '%' . $this->searchBeneficiaries . '%')
                            ->orWhere('beneficiaries.last_name', 'LIKE', '%' . $this->searchBeneficiaries . '%');
                    });
                }
            })
            ->select(
                DB::raw('beneficiaries.*'),
            )
            ->take($this->beneficiaries_on_page)
            ->get();

        return $beneficiaries;
    }

    public function checkImplementationTotalSlots()
    {
        $focalUserId = auth()->id();

        $this->totalImplementations = Implementation::where('users_id', $focalUserId)
            ->whereBetween('created_at', [$this->start, $this->end])
            ->count();
    }

    public function checkBatchRemainingSlots()
    {
        $focalUserId = auth()->id();

        if ($this->implementationId) {

            $implementation = Implementation::where('users_id', $focalUserId)
                ->where('id', $this->implementationId)
                ->first();

            $this->remainingBatchSlots = $implementation->total_slots;

            $batchesCount = Batch::join('implementations', 'implementations.id', '=', 'batches.implementations_id')
                ->where('implementations.users_id', $focalUserId)
                ->where('implementations.id', $this->implementationId)
                ->select('batches.slots_allocated')
                ->orderBy('batches.id', 'desc')
                ->get();

            foreach ($batchesCount as $batch) {
                $this->remainingBatchSlots -= $batch->slots_allocated;
            }
        } else {
            $this->remainingBatchSlots = null;
        }

    }

    public function checkBeneficiarySlots()
    {
        if ($this->batchId) {

            $batch = Batch::where('id', $this->batchId)
                ->first();

            $this->beneficiarySlots = $batch->slots_allocated;

            $beneficiaryCount = Beneficiary::where('batches_id', $this->batchId)
                ->count();

            $this->beneficiarySlots = [
                'batch_slots_allocated' => $batch->slots_allocated,
                'num_of_beneficiaries' => $beneficiaryCount
            ];

        } else {
            $this->beneficiarySlots = [
                'batch_slots_allocated' => null,
                'num_of_beneficiaries' => null
            ];
        }
    }

    public function loadMoreImplementations()
    {
        $this->implementations_on_page += 15;
        $this->dispatch('init-reload')->self();
    }

    public function loadMoreBeneficiaries()
    {
        $this->beneficiaries_on_page += 15;
        $this->dispatch('init-reload')->self();
    }

    public function setImplementationId()
    {
        if ($this->implementations()->isNotEmpty()) {
            $projectNumPrefix = config('settings.project_number_prefix', 'XII-DCFO-');

            $this->implementationId = Implementation::where('users_id', auth()->id())
                ->whereBetween('created_at', [$this->start, $this->end])
                ->where('project_num', 'LIKE', $projectNumPrefix . '%' . $this->searchProjects . '%')
                ->latest('created_at')
                ->take($this->implementations_on_page)
                ->value('id');
        }
    }

    public function setBatchId()
    {
        if ($this->implementationId) {
            $this->batchId = Batch::where('implementations_id', $this->implementationId)
                ->orderBy('id', 'desc')
                ->value('id');
        }
    }

    #[On('update-implementations')]
    public function updateImplementations()
    {
        $dateTimeFromEnd = $this->end;
        $value = substr($dateTimeFromEnd, 0, 10);

        $choosenDate = date('Y-m-d', strtotime($value));
        $currentTime = date('H:i:s', strtotime(now()));
        $this->end = $choosenDate . ' ' . $currentTime;

        $this->implementationId = null;
        $this->batchId = null;

        $this->selectedImplementationRow = -1;
        $this->selectedBatchRow = -1;
        $this->selectedBeneficiaryRow = -1;

        $this->showAlert = true;
        $this->alertMessage = 'Project implementation successfully created!';
        $this->dispatch('show-alert');
        $this->dispatch('init-reload')->self();
    }

    #[On('edit-implementations')]
    public function editImplementation()
    {
        $dateTimeFromEnd = $this->end;
        $value = substr($dateTimeFromEnd, 0, 10);

        $choosenDate = date('Y-m-d', strtotime($value));
        $currentTime = date('H:i:s', strtotime(now()));
        $this->end = $choosenDate . ' ' . $currentTime;

        unset($this->implementation);

        $this->showAlert = true;
        $this->alertMessage = 'Saved changes!';
        $this->dispatch('show-alert');
        $this->dispatch('init-reload')->self();
    }

    #[On('delete-implementations')]
    public function deleteImplementation()
    {
        $dateTimeFromEnd = $this->end;
        $value = substr($dateTimeFromEnd, 0, 10);

        $choosenDate = date('Y-m-d', strtotime($value));
        $currentTime = date('H:i:s', strtotime(now()));
        $this->end = $choosenDate . ' ' . $currentTime;

        $this->implementationId = null;
        $this->batchId = null;

        $this->selectedImplementationRow = -1;
        $this->selectedBatchRow = -1;
        $this->selectedBeneficiaryRow = -1;

        $this->showAlert = true;
        $this->alertMessage = 'Successfully deleted the project!';
        $this->dispatch('show-alert');
        $this->dispatch('init-reload')->self();
    }

    #[On('assign-create-batches')]
    public function updateBatches()
    {
        $dateTimeFromEnd = $this->end;
        $value = substr($dateTimeFromEnd, 0, 10);

        $choosenDate = date('Y-m-d', strtotime($value));
        $currentTime = date('H:i:s', strtotime(now()));
        $this->end = $choosenDate . ' ' . $currentTime;

        $this->batchId = null;

        $this->selectedBatchRow = -1;
        $this->selectedBeneficiaryRow = -1;

        $this->showAlert = true;
        $this->alertMessage = 'Batches successfully assigned!';
        $this->dispatch('show-alert');
        $this->dispatch('init-reload')->self();
    }

    public function mount()
    {
        if (Auth::user()->user_type !== 'focal') {
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
        /*
         *   Check Slots
         */
        $this->checkImplementationTotalSlots();
        $this->checkBatchRemainingSlots();
        $this->checkBeneficiarySlots();

        return view('livewire.focal.implementations');
    }
}
