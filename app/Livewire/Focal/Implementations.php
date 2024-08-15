<?php

namespace App\Livewire\Focal;

use App\Models\Implementation;
use Crypt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Implementations | TU-Efficient')]
class Implementations extends Component
{
    public $selectedImplementationRow = 0;
    public $selectedBatchRow = 0;
    public $selectedBeneficiaryRow = 0;
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
        $this->start = date('Y-m-d', strtotime($value));
        $this->setListOfImplementations();
        $this->setListOfBatchAssignments();
        $this->setListOfBeneficiaries();

        $this->selectedImplementationRow = 0;
        $this->selectedBatchRow = 0;
        $this->selectedBeneficiaryRow = 0;
    }

    #[On('end-change')]
    public function setEndDate($value)
    {
        $this->end = date('Y-m-d', strtotime($value));
        $this->setListOfImplementations();
        $this->setListOfBatchAssignments();
        $this->setListOfBeneficiaries();

        $this->selectedImplementationRow = 0;
        $this->selectedBatchRow = 0;
        $this->selectedBeneficiaryRow = 0;
    }

    public function selectImplementationRow($key, $encryptedId)
    {
        $this->selectedImplementationRow = $key;
        $this->implementationId = Crypt::decrypt($encryptedId);

        $this->setListOfBatchAssignments();
        $this->setListOfBeneficiaries();

        $this->selectedBatchRow = 0;
        $this->selectedBeneficiaryRow = 0;
    }

    public function selectBatchRow($key, $encryptedId)
    {
        $this->selectedBatchRow = $key;
        $this->batchId = Crypt::decrypt($encryptedId);

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

        $this->implementationId = $this->implementations[0]['id'];
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

        $this->batchId = $this->batches[0]['batches_id'];
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
            ->get()
            ->toArray();

        $this->beneficiaryId = $this->beneficiaries[0]['id'];
    }

    public function mount()
    {
        /*
         *  Setting default dates in the datepicker
         */
        $this->start = date('Y-m-d', strtotime(now()->startOfYear()));
        $this->end = date('Y-m-d', strtotime(now()));

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

    }
    public function render()
    {

        if (Auth::user()->user_type === 'Focal')
            return view('livewire.focal.implementations');
        else if (Auth::user()->user_type === 'Coordinator')
            return redirect()->route('coordinator.home');
    }
}
