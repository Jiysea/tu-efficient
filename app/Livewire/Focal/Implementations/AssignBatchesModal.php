<?php

namespace App\Livewire\Focal\Implementations;

use App\Livewire\Forms\BatchAndAssignmentForm;
use App\Models\Assignment;
use App\Models\Batch;
use App\Models\Implementation;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Reactive;
use Livewire\Attributes\Validate;
use Livewire\Component;

class AssignBatchesModal extends Component
{
    #[Locked]
    #[Reactive]
    public $implementationId;
    #[Locked]
    public $batchId;
    #[Locked]
    public $batches_id;
    public $temporaryBatchesList = [];
    public $batchesCount;
    public $implementation;
    public $assignments;
    public $selectedBatchRow = -1;
    public $previousSelectedBatchRow = -1;
    public $remainingSlots;

    # -------------------------------------
    public $coordinators;
    public $currentCoordinator;
    public $coordinatorFullName;
    public $selectedCoordinatorKey;
    public $selectedCoordinatorId;
    public $selectedCoordinatorIds = [];
    public $searchCoordinator;

    # -------------------------------------
    #[Validate]
    public $batch_num;
    #[Validate]
    public $barangay_name;
    #[Validate]
    public $slots_allocated;
    public $assigned_coordinators = [];


    # Runs real-time depending on wire:model suffix
    public function rules()
    {
        return [
            'batch_num' => 'required|unique:batches',
            'barangay_name' => 'required',
            'slots_allocated' => 'required|integer|min:1',
            'assigned_coordinators' => 'required',
        ];
    }

    # The validation error messages
    public function messages()
    {
        return [
            'batch_num.required' => 'The :attribute should not be empty.',
            'barangay_name.required' => 'The :attribute should not be empty.',
            'slots_allocated.required' => 'Invalid :attribute amount.',
            'assigned_coordinators' => 'There should be at least 1 :attribute.',

            'batch_num.unique' => 'This :attribute already exists.',

            'slots_allocated.integer' => 'The :attribute should be a valid number.',
            'slots_allocated.min' => 'The :attribute should be > 0.',
        ];
    }

    # Validation attribute names for human readability purpose
    # for example: The batch_num should not be empty.
    # instead of that: The batch number should not be empty.
    public function validationAttributes()
    {
        return [
            'batch_num' => 'batch number',
            'barangay_name' => 'barangay',
            'slots_allocated' => 'slots',
            'assigned_coordinators' => 'assigned coordinator',
        ];
    }

    # a livewire action executes after clicking the `Finish` button
    public function saveBatches()
    {
        $this->validate();

        $batch = Batch::create([
            'users_id' => Auth()->id(),
            'batch_num' => $this->batch_num,
            'barangay_name' => $this->barangay_name,
            'slots_allocated' => $this->slots_allocated,
        ]);

        # Get the ID and return the newly created Batch
        $this->batches_id = $batch->id;

        foreach ($this->selectedCoordinatorIds as $coordinator_id) {
            Assignment::create([
                'batches_id' => $this->batches_id,
                'users_id' => $coordinator_id,
            ]);
        }

        # it's bugged
        // $this->reset();

        $this->dispatch('update-batches');
    }

    # triggers when a user clicks the `pen` button which activates the edit mode
    # of the batch row that enables users to modify the coordinators, allocated slots 
    # and barangay names.
    public function editBatchRow($key, $encryptedId)
    {
        if ($this->previousSelectedBatchRow === $key) {
            # resets the rows styling/emphasis
            $this->selectedBatchRow = -1;
            $this->batchId = null;

            # also resets the input text boxes
            // $this->batch_num = null;
            // $this->barangay_name = null;
            // $this->slots_allocated = null;
            // $this->assigned_coordinators = [];
        } else {
            $this->selectedBatchRow = $key;
            $this->batchId = Crypt::decrypt($encryptedId);

            // $this->batch_num = $this->batches[$key]['batch_num'];
            // $this->barangay_name = $this->batches[$key]['barangay_name'];
            // $this->slots_allocated = $this->batches[$key]['slots_allocated'];
            // $this->assigned_coordinators = $this->getBatchCoordinators($key);
            // $this->validate();
        }

        $this->previousSelectedBatchRow = $this->selectedBatchRow;
    }

    // public function getBatchCoordinators($key)
    // {
    //     $coordinators = [];
    //     foreach ($this->assignments[$key]['assignments'] as $assignment) {
    //         $coordinators[] = $assignment['last_name'];
    //     }

    //     return $coordinators;
    // }

    // public function commaLastNames() {
    //     $coordinators = '';
    //     $lastIndex = count($this->assignments[$key]['assignments']) - 1;
    //     foreach ($this->assignments[$key]['assignments'] as $index => $assignment) {
    //         if ($index === $lastIndex) {
    //             $coordinators .= $assignment['last_name'];
    //         } else {
    //             $coordinators .= $assignment['last_name'] . ', ';
    //         }
    //     }
    //     return $coordinators;
    // }

    # triggers when clicking the `ADD BATCH` button which adds the user input
    # to the temporary batch list table for saving later
    public function addBatchRow()
    {
        $this->validate();

        $users_id = [];
        foreach ($this->assigned_coordinators as $coordinator)
            $users_id[] = $coordinator['users_id'];

        $this->temporaryBatchesList[] = [
            'batch_num' => $this->batch_num,
            'barangay_name' => $this->barangay_name,
            'slots_allocated' => $this->slots_allocated,
            'assignments' => [
                'users_id' => $users_id,
            ],
        ];
    }

    # this function adds the selected coordinator from the `Add Coordinator` dropdown
    # and append it to the `Assigned Coordinators` as a toast-like element.
    # It would also remove the added coordinator from the `Add Coordinator` dropdown list
    # so as to avoid duplicating/conflicting names.
    public function addToastCoordinator()
    {
        # appends the coordinator to the `Assigned Coordinators` box
        $this->assigned_coordinators[] = [
            'users_id' => $this->coordinators[$this->selectedCoordinatorKey]['id'],
            'last_name' => $this->coordinators[$this->selectedCoordinatorKey]['last_name'],
        ];

        # removes the coordinator from the `Add Coordinator` dropdown
        $ignoredIDs = [];
        foreach ($this->assigned_coordinators as $coordinatorId)
            $ignoredIDs[] = $coordinatorId['users_id'];

        $this->getAllCoordinators($ignoredIDs);
    }

    # triggers when a user clicks the `X` from the toast of the `Assigned Coordinators` box
    public function removeToastCoordinator($key)
    {
        # removes the coordinator from the `Assigned Coordinators` box
        unset($this->assigned_coordinators[$key]);

        # appens the coordinator to the `Add Coordinator` dropdown
        $ignoredIDs = [];
        foreach ($this->assigned_coordinators as $coordinatorId)
            $ignoredIDs[] = $coordinatorId['users_id'];

        $this->getAllCoordinators($ignoredIDs);
    }

    public function liveUpdateRemainingSlots($firstTime = false)
    {
        $focalUserId = auth()->id();

        if ($firstTime) {
            $this->remainingSlots = $this->implementation['total_slots'];
        }

        $this->batchesCount = Batch::join('implementations', 'implementations.id', '=', 'batches.implementations_id')
            ->where('implementations.users_id', $focalUserId)
            ->where('implementations.id', $this->implementationId)
            ->select('batches.slots_allocated')
            ->get()
            ->toArray();


        # get the id of each batches, probably a foreach loop
        # then some code to get the coordinator's last names in a certain format by row
        # in assignment table
        $batchCountDelta = 0;
        foreach ($this->batchesCount as $batch) {
            # determine the remaining slots
            $batchCountDelta -= $batch['slots_allocated'];
        }

        if (is_int(intval($this->slots_allocated))) {
            $this->remainingSlots -= $this->slots_allocated - $batchCountDelta;
        }

        if (!$firstTime) {
            $this->validateOnly('slots_allocated');
        }

    }

    # triggers when a user clicks one of the list from the `Add Coordinator` dropdown
    public function updateCurrentCoordinator($key)
    {
        $this->setFullName($key);
        $this->currentCoordinator = $this->coordinatorFullName;
        // $this->selectedCoordinatorId = $this->coordinators[$key]['id'];
        $this->selectedCoordinatorKey = $key;
    }

    public function setFullName($key)
    {
        $first = $this->coordinators[$key]['first_name'];
        $middle = $this->coordinators[$key]['middle_name'];
        $last = $this->coordinators[$key]['last_name'];
        $ext = $this->coordinators[$key]['extension_name'];

        if ($ext === '-' && $middle === '-') {
            $this->coordinatorFullName = $first . " " . $last;
        } else if ($middle === '-' && $ext !== '-') {
            $this->coordinatorFullName = $first . " " . $last . " " . $ext;
        } else if ($middle !== '-' && $ext === '-') {
            $this->coordinatorFullName = $first . " " . $middle . " " . $last;
        } else {
            $this->coordinatorFullName = $first . " " . $middle . " " . $last . " " . $ext;
        }
    }

    # mount (1 time)
    public function getAllCoordinators($ignoredIDs = null)
    {
        $focalInfo = User::where('id', auth()->id())
            ->first()
            ->toArray();

        if ($ignoredIDs) {
            $this->coordinators = User::where('user_type', 'Coordinator')
                ->where('regional_office', $focalInfo['regional_office'])
                ->where('field_office', $focalInfo['field_office'])
                ->whereNotIn('id', $ignoredIDs)
                ->get()
                ->toArray();
        } else {
            $this->coordinators = User::where('user_type', 'Coordinator')
                ->where('regional_office', $focalInfo['regional_office'])
                ->where('field_office', $focalInfo['field_office'])
                ->get()
                ->toArray();
        }
        $this->setFullName(0);
        $this->selectedCoordinatorKey = 0;
        $this->selectedCoordinatorId = $this->coordinators[0]['id'];
        $this->currentCoordinator = $this->coordinatorFullName;
    }

    public function mount()
    {
        $this->getAllCoordinators();
    }

    public function render()
    {
        $focalUserId = auth()->id();

        $this->implementation = Implementation::where('users_id', $focalUserId)
            ->where('id', $this->implementationId)
            ->first();

        $this->liveUpdateRemainingSlots(true);

        # determine the remaining slots
        // $this->remainingSlots -= $batch['slots_allocated'];
        # some code to keep adding more array for each batch row
        // $assignments = Assignment::join('users', 'users.id', '=', 'assignments.users_id')
        //     ->where('assignments.batches_id', $batch['id'])
        //     ->select([
        //         'assignments.*',
        //         'users.last_name'
        //     ])
        //     ->get()
        //     ->toArray();

        # supposedly should return every row/array by batch row
        // $this->assignments[] = [
        //     'batch_id' => $batch['id'], // Store batch ID as a reference
        //     'assignments' => $assignments // Store the fetched assignments
        // ];

        // $users_id = [];
        // foreach ($assignments as $assignment) {
        //     $users_id[] = $assignment['users_id'];
        // }

        // $this->selectedCoordinatorIds[] = [
        //     'batch_id' => $batch['id'], // Store batch ID as a reference
        //     'users_id' => $users_id,
        // ];

        return view('livewire.focal.implementations.assign-batches-modal');
    }
}
