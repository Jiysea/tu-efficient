<?php

namespace App\Livewire\Barangay;

use App\Models\Assignment;
use App\Models\Batch;
use App\Models\Beneficiary;
use App\Models\Implementation;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Locked;
use Livewire\Component;

class BatchInformation extends Component
{
    #[Locked]
    public $accessCode;
    #[Locked]
    public $users;
    #[Locked]
    public $location;
    #[Locked]
    public $slots;

    public function mount($accessCode)
    {
        // Retrieve the access code from the session
        $this->accessCode = $accessCode;

        $this->users = User::join('assignments', 'users.id', '=', 'assignments.users_id')
            ->join('batches', 'batches.id', '=', 'assignments.batches_id')
            ->join('codes', 'codes.batches_id', '=', 'batches.id')
            ->where('codes.access_code', $this->accessCode)
            ->select(
                DB::raw('users.last_name')
            )->get()
            ->toArray();

        $this->location = Implementation::join('batches', 'implementations.id', '=', 'batches.implementations_id')
            ->join('codes', 'batches.id', '=', 'codes.batches_id')
            ->where('codes.access_code', $this->accessCode)
            ->select(
                DB::raw('implementations.district'),
                DB::raw('batches.barangay_name'),
            )
            ->first()
            ->toArray();

        $this->slots = Batch::join('beneficiaries', 'batches.id', '=', 'beneficiaries.batches_id')
            ->join('codes', 'batches.id', '=', 'codes.batches_id')
            ->where('codes.access_code', $this->accessCode)
            ->select(
                DB::raw('batches.slots_allocated AS slots_allocated'),
                DB::raw('COUNT(DISTINCT beneficiaries.id) AS current_slots'),
            )
            ->groupBy('slots_allocated')
            ->first()
            ->toArray();
    }
    public function render()
    {
        return view('livewire.barangay.batch-information');
    }
}
