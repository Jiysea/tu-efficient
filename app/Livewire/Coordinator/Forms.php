<?php

namespace App\Livewire\Coordinator;

use App\Models\Assignment;
use App\Models\Batch;
use App\Models\UserSetting;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Forms | TU-Efficient')]
class Forms extends Component
{

    #[Locked]
    public $batchId;
    #[Locked]
    public $formTypeKey = 0;
    #[Locked]
    public $currentAssignment;
    #[Locked]
    public $currentFormType;
    #[Locked]
    public $batchNumPrefix;
    public $searchBatches;

    # ------------------------------------

    public $start;
    public $end;
    public $defaultStart;
    public $defaultEnd;

    # ------------------------------------

    #[Computed]
    public function assignments()
    {
        $assignments = Batch::join('assignments', 'batches.id', '=', 'assignments.batches_id')
            ->where('assignments.users_id', Auth::id())
            ->whereBetween('batches.created_at', [$this->start, $this->end])
            ->when($this->searchBatches, function ($q) {
                # Check if the search term contains '#' and filter by batch_num
                if (ctype_digit($this->searchBatches)) {
                    dump($this->searchBatches);
                    $q->where('batches.batch_num', 'LIKE', '%' . $this->batchNumPrefix . '%' . $this->searchBatches . '%');
                } else {
                    # Otherwise, search by barangay_name
                    $q->where('batches.barangay_name', 'LIKE', $this->searchBatches . '%');
                }
            })
            ->select(
                [
                    'batches.id',
                    'batches.batch_num',
                    'batches.barangay_name',
                ]
            )
            ->groupBy([
                'batches.id',
                'batches.batch_num',
                'batches.barangay_name',
            ])
            ->orderBy('batches.id', 'desc')
            ->get();
        return $assignments;
    }

    #[Computed]
    public function formTypes()
    {
        $formTypes = collect();
        $formTypes->add([
            'Annex D - Profile',
            'Annex E-1 - COS',
            'Annex E-2 - COS (co-partner)',
            'Annex J-2 - Attendance Sheet',
            'Annex L - Payroll',
            'Annex L - Payroll with Sign'
        ]);

        return $formTypes->collapseWithKeys();
    }

    public function selectAssignment($key, $encryptedId)
    {
        $this->currentAssignment = $this->assignments[$key]->batch_num . ' (' . $this->assignments[$key]->barangay_name . ')';
        $this->batchId = $encryptedId;
    }

    public function selectFormType($key)
    {
        $this->currentFormType = $this->formTypes[$key];
        $this->formTypeKey = $key;
    }

    public function mount()
    {
        if (Auth::user()->user_type !== 'coordinator') {
            $this->redirectIntended();
        }

        $settings = UserSetting::where('users_id', Auth::id())
            ->pluck('value', 'key');
        $this->batchNumPrefix = $settings->get('batch_num_prefix', config('settings.batch_number_prefix'));

        $this->start = date('Y-m-d H:i:s', strtotime(now()->startOfYear()));
        $this->end = date('Y-m-d H:i:s', strtotime(now()));

        $this->currentAssignment = $this->assignments[0]->batch_num . ' (' . $this->assignments[0]->barangay_name . ')';
        $this->currentFormType = $this->formTypes[0];

        $this->defaultStart = date('m/d/Y', strtotime($this->start));
        $this->defaultEnd = date('m/d/Y', strtotime($this->end));
    }
    public function render()
    {
        return view('livewire.coordinator.forms');
    }
}
