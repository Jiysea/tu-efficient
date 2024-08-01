<?php

namespace App\Livewire\Focal\Implementations;

use App\Models\Implementation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class BeneficiaryPreview extends Component
{
    #[Locked]
    public $beneficiaryId;
    #[Locked]
    public $preview;

    #[On('change-beneficiary')]
    public function setCurrentProject($beneficiaryId)
    {
        $this->beneficiaryId = $beneficiaryId;
    }

    public function mount()
    {
        $focalUserId = 2;
        $this->before = Carbon::now()->startOfYear();
        $this->after = Carbon::now()->endOfYear();

        $defaultBeneficiaryId = Implementation::where('users_id', $focalUserId)
            ->join('batches', 'implementations.id', '=', 'batches.implementations_id')
            ->join('beneficiaries', 'batches.id', '=', 'beneficiaries.batches_id')
            ->whereBetween('batches.created_at', [$this->before, $this->after])
            ->value('beneficiaries.id');

        $this->beneficiaries = Implementation::where('users_id', $focalUserId)
            ->join('batches', 'implementations.id', '=', 'batches.implementations_id')
            ->join('beneficiaries', 'batches.id', '=', 'beneficiaries.batches_id')
            ->where('batches.id', $defaultBeneficiaryId)
            ->select(
                DB::raw('beneficiaries.*'),
            )
            ->get()
            ->toArray();
    }

    public function render()
    {
        return view('livewire.focal.implementations.beneficiary-preview');
    }
}
