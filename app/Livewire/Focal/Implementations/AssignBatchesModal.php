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
    public $implementation;
    public $temporaryBatchesList = [];
    public $batchesCount;
    public $selectedBatchRow = -1;
    public $previousSelectedBatchRow = -1;
    public $remainingSlots;
    public $totalSlots;

    # -------------------------------------
    public $coordinators;
    public $currentCoordinator;
    public $coordinatorFullName;
    public $selectedCoordinatorKey;
    public $searchCoordinator;
    # ---------------------------------------
    public $batchListCoordinators;
    public $selectedCoordinatorKeyInBatchListDropdown;

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
            'batch_num' => [
                'required',
                'unique:batches',
                function ($attribute, $value, $fail) {
                    foreach ($this->temporaryBatchesList as $batch) {
                        if ($batch['batch_num'] === $value) {
                            $fail('The :attribute has already been added.');
                        }
                    }
                },
            ],
            'barangay_name' => [
                'required',
                function ($attribute, $value, $fail) {
                    foreach ($this->temporaryBatchesList as $batch) {
                        if ($batch['barangay_name'] === $value) {
                            $fail('The :attribute has already been added.');
                        }
                    }
                },
            ],
            'slots_allocated' => [
                'required',
                'integer',
                'gte:0',
                'min:1',
                'lte:' . $this->totalSlots,
            ],
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
            'assigned_coordinators.required' => 'There should be at least 1 :attribute.',
            'temporaryBatchesList.required' => 'There should be at least 1 :attribute before finishing.',

            'batch_num.unique' => 'This :attribute already exists.',

            'slots_allocated.integer' => ':attribute should be a valid number.',
            'slots_allocated.min' => ':attribute should be > 0.',
            'slots_allocated.gte' => ':attribute should be nonnegative.',
            'slots_allocated.lte' => ':attribute should be less than total.',
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
            'slots_allocated' => 'Slots',
            'assigned_coordinators' => 'assigned coordinator',
            'temporaryBatchesList' => 'batch assignment',
        ];
    }

    # a livewire action executes after clicking the `Finish` button
    public function saveBatches()
    {
        $this->validate(['temporaryBatchesList' => 'required']);

        foreach ($this->temporaryBatchesList as $keyBatch => $batch) {
            $batch = Batch::create([
                'implementations_id' => $this->implementationId,
                'batch_num' => $batch['batch_num'],
                'barangay_name' => $batch['barangay_name'],
                'slots_allocated' => $batch['slots_allocated'],
                'approval_status' => 'PENDING',
                'submission_status' => 'UNOPENED'
            ]);

            $batch_id = $batch->id;

            foreach ($this->temporaryBatchesList[$keyBatch]['assigned_coordinators'] as $coordinator) {
                Assignment::create([
                    'batches_id' => $batch_id,
                    'users_id' => $coordinator['users_id'],
                ]);
            }
        }

        # resets after submitting
        $this->batch_num = null;
        $this->barangay_name = null;
        $this->slots_allocated = null;
        $this->assigned_coordinators = [];
        $this->temporaryBatchesList = [];
        $this->dispatch('assign-create-batches');
    }

    # triggers when a user clicks the `pen` button which activates the edit mode
    # of the batch row that enables users to modify the coordinators, allocated slots 
    # and barangay names.
    public function editBatchRow($key)
    {
        if ($this->previousSelectedBatchRow === $key) {
            # resets the rows styling/emphasis
            $this->selectedBatchRow = -1;


        } else {
            $this->selectedBatchRow = $key;
        }

        $this->previousSelectedBatchRow = $this->selectedBatchRow;
    }

    # triggers when clicking the `ADD BATCH` button which adds the user input
    # to the temporary batch list table for saving later.
    public function addBatchRow()
    {
        $this->validate();
        // $this->validateOnly('batch_num');
        // $this->validateOnly('barangay_name');
        // $this->validateOnly('slots_allocated');
        // $this->validateOnly('assigned_coordinators');

        $this->temporaryBatchesList[] = [
            'batch_num' => $this->batch_num,
            'barangay_name' => $this->barangay_name,
            'slots_allocated' => $this->slots_allocated,
            'assigned_coordinators' => $this->assigned_coordinators,
        ];

        $this->totalSlots -= $this->slots_allocated;
        $this->batch_num = null;
        $this->barangay_name = null;
        $this->slots_allocated = null;
        $this->assigned_coordinators = [];
        $this->getAllCoordinators();
        $this->getAllCoordinatorsForBatchList();
        $this->validateOnly('temporaryBatchesList');

    }

    # triggers when clicking the `X` button which removes the
    # batch row from the list and temporary batch array.
    public function removeBatchRow($key)
    {
        $this->totalSlots += $this->temporaryBatchesList[$key]['slots_allocated'];
        $this->liveUpdateRemainingSlots();

        if ($this->selectedBatchRow === $key) {
            # resets the rows styling/emphasis
            $this->selectedBatchRow = -1;
        } else if ($key > $this->selectedBatchRow) {
            $this->selectedBatchRow -= 1;
        }

        unset($this->temporaryBatchesList[$key]);
    }

    # this function adds the selected coordinator from the `Add Coordinator` dropdown
    # and append it to the `Assigned Coordinators` as a toast-like element.
    # It would also remove the added coordinator from the `Add Coordinator` dropdown list
    # so as to avoid duplicating/conflicting names.
    public function addToastCoordinator()
    {
        if (count($this->coordinators) === 0) {
            # nope not happening

        } else {
            # appends the coordinator to the `Assigned Coordinators` box
            $this->assigned_coordinators[] = [
                'users_id' => $this->coordinators[$this->selectedCoordinatorKey]['id'],
                'last_name' => $this->coordinators[$this->selectedCoordinatorKey]['last_name'],
            ];

            # removes the coordinator from the `Add Coordinator` dropdown
            $ignoredIDs = [];
            foreach ($this->assigned_coordinators as $coordinator)
                $ignoredIDs[] = $coordinator['users_id'];

            $this->getAllCoordinators($ignoredIDs);
            $this->validateOnly('assigned_coordinators');
        }
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

    public function addToastCoordinatorInBatchList()
    {
        if (count($this->batchListCoordinators) === 0) {
            # nope not happening

        } else {
            # appends the coordinator to the `Assigned Coordinators` box
            $this->temporaryBatchesList[$this->selectedBatchRow]['assigned_coordinators'][] = [
                'users_id' => $this->batchListCoordinators[$this->selectedCoordinatorKeyInBatchListDropdown]['id'],
                'last_name' => $this->batchListCoordinators[$this->selectedCoordinatorKeyInBatchListDropdown]['last_name'],
            ];
            # removes the coordinator from the `Add Coordinator` dropdown
            $ignoredIDs = [];
            foreach ($this->temporaryBatchesList[$this->selectedBatchRow]['assigned_coordinators'] as $coordinatorId)
                $ignoredIDs[] = $coordinatorId['users_id'];

            $this->getAllCoordinatorsForBatchList($ignoredIDs);
        }
    }

    # triggers when a user clicks the `X` from the toast of the `Assigned Coordinators` box
    public function removeToastCoordinatorFromBatchList($keyBatch, $keyCoodinator)
    {
        # check first if there is only 1 key value in the `assigned_coordinators` nested array
        if (count($this->temporaryBatchesList[$keyBatch]['assigned_coordinators']) === 1) {
            # nope not happening

        } else {
            # removes the coordinator from the `assigned_coordinators` nested array
            # in the `temporaryBatchesList` array
            unset($this->temporaryBatchesList[$keyBatch]['assigned_coordinators'][$keyCoodinator]);

            # resets the coordinator list for the `+` button dropup
            $ignoredIDs = [];
            foreach ($this->temporaryBatchesList[$keyBatch]['assigned_coordinators'] as $coordinatorId)
                $ignoredIDs[] = $coordinatorId['users_id'];

            $this->getAllCoordinatorsForBatchList($ignoredIDs);
        }
    }

    public function liveUpdateRemainingSlots()
    {
        $focalUserId = auth()->id();
        $batchCountDelta = 0;

        # this condition initializes the slots
        # and it makes sense to have when the 1st time this component
        # is rendered will have empty and temporary batch list.
        #
        # It's also counterproductive to also retrieve all the existing batches
        # from the selected implementation project because it's designed to 
        # assign and create `new` batch assignments.
        #
        # So, the only value that we're retrieving is the `total_slots` from the implementations
        # and the `slots_alloted` from the batches to give an indicator of how many remaining
        # slots left for the user to input.
        if (!$this->temporaryBatchesList) {
            $this->totalSlots = $this->implementation['total_slots'];

            # retrieves all of the `slots_alloted` values from the batches table
            $this->batchesCount = Batch::join('implementations', 'implementations.id', '=', 'batches.implementations_id')
                ->where('implementations.users_id', $focalUserId)
                ->where('implementations.id', $this->implementationId)
                ->select('batches.slots_allocated')
                ->get()
                ->toArray();

            # retrieves all the slots allocated from existing (if any) batches
            # and iterate it as a single value
            foreach ($this->batchesCount as $batch) {
                $batchCountDelta += $batch['slots_allocated'];
            }

            # re-assign the total slots based on batch counts (if any)
            # then assign the remaining slots to it
            $this->totalSlots -= $batchCountDelta;
            $this->remainingSlots = $this->totalSlots;

        } else {

            # re-assigns the remaining slots to the total slots that was
            # also re-assigned during addition of the new batch to the temporary list.
            $this->remainingSlots = $this->totalSlots;

        }

        # this condition basically filters the input to take only numbers
        if (ctype_digit($this->slots_allocated)) {
            # assign the difference between the remaining slots and the input slots value
            $newRemainingSlots = intval($this->remainingSlots) - intval($this->slots_allocated);

            # it also filters
            if ($newRemainingSlots >= 0) {
                $this->remainingSlots = $newRemainingSlots;
            } else if ($newRemainingSlots < 0) {
                $this->remainingSlots = 0;
            }
        }
    }

    # triggers when a user clicks one of the list from the `Add Coordinator` dropdown
    public function updateCurrentCoordinator($key)
    {
        $this->setFullName($key);
        $this->currentCoordinator = $this->coordinatorFullName;
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

    # triggers when a user clicks one of the list from the `+` dropup
    # in batch list.
    public function selectCurrentCoordinator($key)
    {
        $this->selectedCoordinatorKeyInBatchListDropdown = $key;
    }

    # this should be called after clicking the + button
    public function getAllCoordinatorsForBatchList($ignoredIDs = null)
    {
        $focalInfo = User::where('id', auth()->id())
            ->first()
            ->toArray();

        if ($ignoredIDs) {
            if (!is_array($ignoredIDs)) {
                $ignoredIDs = json_decode($ignoredIDs, true);
            }

            $this->batchListCoordinators = User::where('user_type', 'Coordinator')
                ->where('regional_office', $focalInfo['regional_office'])
                ->where('field_office', $focalInfo['field_office'])
                ->whereNotIn('id', $ignoredIDs)
                ->get()
                ->toArray();
        } else {
            $this->batchListCoordinators = User::where('user_type', 'Coordinator')
                ->where('regional_office', $focalInfo['regional_office'])
                ->where('field_office', $focalInfo['field_office'])
                ->get()
                ->toArray();
        }
        // $this->setFullName(0);
        $this->selectedCoordinatorKeyInBatchListDropdown = 0;
    }

    # mount and call from click (1 time)
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

        if (!$this->coordinators) {
            $this->selectedCoordinatorKey = -1;
            $this->currentCoordinator = 'N/A';
        } else {
            $this->setFullName(0);
            $this->selectedCoordinatorKey = 0;
            $this->currentCoordinator = $this->coordinatorFullName;
        }

    }

    public function mount()
    {
        $this->getAllCoordinators();
        $this->getAllCoordinatorsForBatchList();
    }

    public function render()
    {
        $focalUserId = auth()->id();

        $this->implementation = Implementation::where('users_id', $focalUserId)
            ->where('id', $this->implementationId)
            ->first();

        $this->liveUpdateRemainingSlots();

        return view('livewire.focal.implementations.assign-batches-modal');
    }
}
