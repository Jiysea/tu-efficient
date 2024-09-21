<?php

namespace App\Livewire\Coordinator\Assignments;

use App\Models\Assignment;
use App\Models\Batch;
use App\Models\Beneficiary;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Reactive;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ViewBatchModal extends Component
{
    #[Reactive]
    #[Locked]
    public $passedId;

    # ------------------------------

    #[Computed]
    public function batch()
    {
        $batch = Batch::find(decrypt($this->passedId));

        return $batch;
    }

    #[Computed]
    public function assignments()
    {
        $assignments = Assignment::where('assignments.batches_id', decrypt($this->passedId))
            ->join('users', 'users.id', '=', 'assignments.users_id')
            ->whereNotIn('assignments.users_id', [Auth::id()])
            ->get();

        return $assignments;
    }

    #[Computed]
    public function getFullName($person)
    {
        $name = $person->first_name;

        if ($person->middle_name) {
            $name .= ' ' . $person->middle_name;
        }

        $name .= ' ' . $person->last_name;

        if ($person->extension_name) {
            $name .= ' ' . $person->extension_name;
        }

        return $name;
    }

    #[Computed]
    public function currentSlots()
    {
        $currentSlots = Beneficiary::join('batches', 'batches.id', '=', 'beneficiaries.batches_id')
            ->where('batches.id', decrypt($this->passedId))
            ->count();

        return $currentSlots;
    }

    public function resetEverything()
    {

    }
    public function render()
    {
        return view('livewire.coordinator.assignments.view-batch-modal');
    }
}
