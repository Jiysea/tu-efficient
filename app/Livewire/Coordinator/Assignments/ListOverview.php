<?php

namespace App\Livewire\Coordinator\Assignments;

use App\Models\Batch;
use App\Models\Implementation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class ListOverview extends Component
{
    #[Locked]
    public $batchId;
    #[Locked]
    public $projectId;
    #[Locked]
    public $beneficiaries;
    #[Locked]
    public $full_name;
    #[Locked]
    public $location;
    #[Locked]
    public $accessCode;

    #[On('change-batch')]
    public function setBatchId($batchId)
    {
        $this->batchId = $batchId;
        $this->updateBeneficiaryList();
    }

    public function updateBeneficiaryList()
    {
        $coordinatorUserId = Auth::user()->id;
        $decryptedId = Crypt::decrypt($this->batchId);

        $this->beneficiaries = User::where('users_id', $coordinatorUserId)
            ->join('assignments', 'users.id', '=', 'assignments.users_id')
            ->join('batches', 'batches.id', '=', 'assignments.batches_id')
            ->join('beneficiaries', 'batches.id', '=', 'beneficiaries.batches_id')
            ->where('batches.id', $decryptedId)
            ->select(
                DB::raw('beneficiaries.id AS id'),
                DB::raw('beneficiaries.first_name AS first_name'),
                DB::raw('beneficiaries.middle_name AS middle_name'),
                DB::raw('beneficiaries.last_name AS last_name'),
                DB::raw('beneficiaries.extension_name AS extension_name'),
                DB::raw('beneficiaries.birthdate AS birthdate'),
                DB::raw('beneficiaries.contact_num AS contact_num')
            )
            ->get()
            ->toArray();

        $this->location = Batch::join('implementations', 'implementations.id', '=', 'batches.implementations_id')
            ->where('batches.id', $decryptedId)
            ->select(
                DB::raw('implementations.district'),
                DB::raw('implementations.city_municipality'),
                DB::raw('implementations.province'),
                DB::raw('batches.barangay_name'),
            )
            ->first()
            ->toArray();

        $this->accessCode = Batch::join('codes', 'batches.id', '=', 'codes.batches_id')
            ->where('batches.id', $decryptedId)
            ->select(
                DB::raw('codes.access_code'),
            )
            ->first()
            ->toArray();

    }

    public function mount()
    {
        $coordinatorUserId = Auth::user()->id;

        $defaultBatch = User::where('users_id', $coordinatorUserId)
            ->join('assignments', 'users.id', '=', 'assignments.users_id')
            ->join('batches', 'batches.id', '=', 'assignments.batches_id')
            ->join('beneficiaries', 'batches.id', '=', 'beneficiaries.batches_id')
            ->select(
                DB::raw('batches.id AS batches_id'),
            )->first();
        // dd($defaultBatch);

        $this->beneficiaries = User::where('users_id', $coordinatorUserId)
            ->join('assignments', 'users.id', '=', 'assignments.users_id')
            ->join('batches', 'batches.id', '=', 'assignments.batches_id')
            ->join('beneficiaries', 'batches.id', '=', 'beneficiaries.batches_id')
            ->where('batches.id', $defaultBatch->batches_id)
            ->select(
                DB::raw('beneficiaries.id AS id'),
                DB::raw('beneficiaries.first_name AS first_name'),
                DB::raw('beneficiaries.middle_name AS middle_name'),
                DB::raw('beneficiaries.last_name AS last_name'),
                DB::raw('beneficiaries.extension_name AS extension_name'),
                DB::raw('beneficiaries.birthdate AS birthdate'),
                DB::raw('beneficiaries.contact_num AS contact_num')
            )
            ->get()
            ->toArray();

        $this->location = Batch::join('implementations', 'implementations.id', '=', 'batches.implementations_id')
            ->where('batches.id', $defaultBatch->batches_id)
            ->select(
                DB::raw('implementations.district'),
                DB::raw('implementations.city_municipality'),
                DB::raw('implementations.province'),
                DB::raw('batches.barangay_name'),
            )
            ->first()
            ->toArray();

        $this->accessCode = Batch::join('codes', 'batches.id', '=', 'codes.batches_id')
            ->where('batches.id', $defaultBatch->batches_id)
            ->select(
                DB::raw('codes.access_code'),
            )
            ->first()
            ->toArray();

    }

    public function render()
    {
        return view('livewire.coordinator.assignments.list-overview');
    }
}
