<?php

namespace App\Livewire\Focal;

use App\Models\Batch;
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
    public $showAlert = false;
    public $beneficiaries_on_page = 15;
    public $selectedImplementationRow = 0;
    public $selectedBatchRow = 0;
    public $selectedBeneficiaryRow = 0;
    public $batchesCount;
    public $isBatchFull = false;
    public $remainingBatchSlots;
    #[Locked]
    public $implementationId;
    #[Locked]
    public $batchId;
    #[Locked]
    public $beneficiaryId;
    public $implementations;
    public $batches;
    public $beneficiaries;
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
        $this->beneficiaries_on_page = 15;

        $this->setListOfImplementations();
        $this->setListOfBatchAssignments();
        $this->setListOfBeneficiaries();
        $this->checkIfFullSlots();

        $this->selectedImplementationRow = 0;
        $this->selectedBatchRow = 0;
        $this->selectedBeneficiaryRow = 0;

        $this->dispatch('init-reload')->self();

    }

    #[On('end-change')]
    public function setEndDate($value)
    {
        $choosenDate = date('Y-m-d', strtotime($value));
        $currentTime = date('H:i:s', strtotime(now()));

        $this->end = $choosenDate . ' ' . $currentTime;
        $this->beneficiaries_on_page = 15;

        $this->setListOfImplementations();
        $this->setListOfBatchAssignments();
        $this->setListOfBeneficiaries();
        $this->checkIfFullSlots();

        $this->selectedImplementationRow = 0;
        $this->selectedBatchRow = 0;
        $this->selectedBeneficiaryRow = 0;

        $this->dispatch('init-reload')->self();
    }

    public function selectImplementationRow($key, $encryptedId)
    {
        $this->selectedImplementationRow = $key;
        $this->implementationId = Crypt::decrypt($encryptedId);
        $this->beneficiaries_on_page = 15;

        $this->setListOfBatchAssignments();
        $this->setListOfBeneficiaries();

        $this->selectedBatchRow = 0;
        $this->selectedBeneficiaryRow = 0;
        $this->checkIfFullSlots();
    }

    public function selectBatchRow($key, $encryptedId)
    {
        $this->selectedBatchRow = $key;
        $this->batchId = Crypt::decrypt($encryptedId);
        $this->beneficiaries_on_page = 15;

        $this->setListOfBeneficiaries();

        $this->selectedBeneficiaryRow = 0;
    }

    public function selectBeneficiaryRow($key, $encryptedId)
    {
        $this->selectedBeneficiaryRow = $key;
        $this->beneficiaryId = Crypt::decrypt($encryptedId);

    }

    public function setListOfImplementations()
    {
        $focalUserId = auth()->id();

        $this->implementations = Implementation::where('users_id', $focalUserId)
            ->whereBetween('created_at', [$this->start, $this->end])
            ->get()
            ->toArray();

        $this->implementationId = $this->implementations[0]['id'] ?? null;
    }

    public function setListOfBatchAssignments()
    {
        $focalUserId = auth()->id();

        $this->batches = Implementation::where('users_id', $focalUserId)
            ->join('batches', 'implementations.id', '=', 'batches.implementations_id')
            ->join('beneficiaries', 'batches.id', '=', 'beneficiaries.batches_id')
            ->where('implementations.id', $this->implementationId)
            ->select(
                DB::raw('batches.id AS batches_id'),
                DB::raw('batches.barangay_name AS barangay_name'),
                DB::raw('batches.slots_allocated AS slots_allocated'),
                DB::raw('COUNT(DISTINCT beneficiaries.id) AS current_slots'),
                DB::raw('batches.approval_status AS approval_status')
            )
            ->groupBy('batches.id', 'barangay_name', 'slots_allocated', 'approval_status')
            ->get()
            ->toArray();

        $this->batchId = $this->batches[0]['batches_id'] ?? null;

    }

    public function setListOfBeneficiaries()
    {
        $focalUserId = auth()->id();

        $this->beneficiaries = Implementation::where('users_id', $focalUserId)
            ->join('batches', 'implementations.id', '=', 'batches.implementations_id')
            ->join('beneficiaries', 'batches.id', '=', 'beneficiaries.batches_id')
            ->where('batches.id', $this->batchId)
            ->select(
                DB::raw('beneficiaries.*'),
            )
            ->latest()
            ->take($this->beneficiaries_on_page)
            ->get()
            ->toArray();

        $this->beneficiaryId = $this->beneficiaries[0]['id'] ?? null;
    }

    public function checkIfFullSlots()
    {
        $focalUserId = auth()->id();

        if ($this->implementationId) {

            $implementation = Implementation::where('users_id', $focalUserId)
                ->where('id', $this->implementationId)
                ->first();

            $this->remainingBatchSlots = $implementation['total_slots'];

            $batchesCount = Batch::join('implementations', 'implementations.id', '=', 'batches.implementations_id')
                ->where('implementations.users_id', $focalUserId)
                ->where('implementations.id', $this->implementationId)
                ->select('batches.slots_allocated')
                ->get()
                ->toArray();

            foreach ($batchesCount as $batch) {
                $this->remainingBatchSlots -= $batch['slots_allocated'];
            }
        } else {
            $this->remainingBatchSlots = null;
        }

    }

    public function loadMoreBeneficiaries()
    {
        $focalUserId = auth()->id();
        $this->beneficiaries_on_page += 15;

        $this->beneficiaries = Implementation::where('users_id', $focalUserId)
            ->join('batches', 'implementations.id', '=', 'batches.implementations_id')
            ->join('beneficiaries', 'batches.id', '=', 'beneficiaries.batches_id')
            ->where('batches.id', $this->batchId)
            ->select(
                DB::raw('beneficiaries.*'),
            )
            ->latest()
            ->take($this->beneficiaries_on_page)
            ->get()
            ->toArray();

        $this->dispatch('init-reload')->self();
    }

    #[On('update-implementations')]
    public function updateImplementations()
    {
        $dateTimeFromEnd = $this->end;
        $value = substr($dateTimeFromEnd, 0, 10);

        $choosenDate = date('Y-m-d', strtotime($value));
        $currentTime = date('H:i:s', strtotime(now()));
        $this->end = $choosenDate . ' ' . $currentTime;

        $this->setListOfImplementations();

        // $messages[] = 'Project implementation successfully created!';
        // session()->put('success-now', $messages);
        $this->showAlert = true;
        $this->dispatch('show-alert');
    }

    // public function removeSuccessMessage($sessionMessage, $index)
    // {
    //     $messages = session()->get($sessionMessage, []);
    //     if (isset($messages[$index])) {
    //         unset($messages[$index]);
    //         session()->put($sessionMessage, array_values($messages));
    //     }
    // }

    public function mount()
    {
        /*
         *  Setting default dates in the datepicker
         */
        $this->start = date('Y-m-d H:i:s', strtotime(now()->startOfYear()));
        $this->end = date('Y-m-d H:i:s', strtotime(now()));

        $this->defaultStart = date('m/d/Y', strtotime($this->start));
        $this->defaultEnd = date('m/d/Y', strtotime($this->end));

        /*
         *  Setting the list of implementation projects
         */
        $this->setListOfImplementations();

        /*
         *  Setting the list of implementation projects
         */
        $this->setListOfBatchAssignments();

        /*
         *  Setting the list of implementation projects
         */
        $this->setListOfBeneficiaries();

        /*
         *   Check Full Slots
         */
        $this->checkIfFullSlots();

    }
    public function render()
    {
        if (Auth::user()->user_type === 'Focal')
            return view('livewire.focal.implementations');
        else if (Auth::user()->user_type === 'Coordinator')
            return redirect()->route('coordinator.home');
    }
}
