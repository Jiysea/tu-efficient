<?php

namespace App\Livewire\Focal\Implementations;

use App\Models\Assignment;
use App\Models\Batch;
use App\Models\Beneficiary;
use App\Models\Implementation;
use App\Models\User;
use App\Models\UserSetting;
use App\Services\Barangays;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Reactive;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ViewBatch extends Component
{
    #[Reactive]
    #[Locked]
    public $passedId;
    public $ignoredIDs;

    # ------------------------------

    public $edit = false;
    public $isEmpty = true;
    public $batchDeleteModal = false;
    public $batchNumPrefix;
    public $searchBarangay;
    public $searchCoordinator;
    public $currentCoordinator;
    public $defaultCoordinator;
    public $coordinatorKey;
    public $totalSlots;

    # ------------------------------

    #[Validate]
    public $batch_num;
    #[Validate]
    public $barangay_name;
    #[Validate]
    public $slots_allocated;
    public $assigned_coordinators = [];

    # ------------------------------

    # Runs real-time depending on wire:model suffix
    public function rules()
    {
        return [
            'batch_num' => [
                'required',

                # Checks uniqueness from the `Database`
                function ($attribute, $value, $fail) {

                    // Check for uniqueness of the prefixed value in the database
                    $exists = DB::table('batches')
                        ->where('batch_num', $this->batchNumPrefix . $value)
                        ->exists();

                    if ($exists) {
                        // Fail the validation if the project number with the prefix already exists
                        $fail('This :attribute already exists.');
                    }
                },
            ],
            'barangay_name' => [
                'required',

                # Checks uniqueness from the Temporary Batch List
                function ($attribute, $value, $fail) {
                    $batch = Batch::where('id', decrypt($this->passedId))
                        ->where('barangay_name', $value)
                        ->exists();
                    if ($batch) {
                        $fail('There\'s already another same :attribute.');
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

            'batch_num.integer' => 'The :attribute should be a valid number.',

            'slots_allocated.integer' => ':attribute should be a valid number.',
            'slots_allocated.min' => ':attribute should be > 0.',
            'slots_allocated.gte' => ':attribute should be nonnegative.',
            'slots_allocated.lte' => ':attribute should be less than total.',
        ];
    }

    # Validation attribute names for human readability purpose
    public function validationAttributes()
    {
        return [
            'batch_num' => 'batch number',
            'barangay_name' => 'barangay',
            'slots_allocated' => 'Slots',
            'assigned_coordinators' => 'assigned coordinator',
        ];
    }

    #[Computed]
    public function implementation()
    {
        $batch = Implementation::find($this->batch->implementations_id);
        return $batch;
    }

    #[Computed]
    public function batch()
    {
        $batch = Batch::find(decrypt($this->passedId));
        return $batch;
    }

    #[Computed]
    public function barangays()
    {
        $b = new Barangays();
        # this returns an array
        $barangays = $b->getBarangays($this->implementation->city_municipality, $this->implementation->district);

        # If searchBarangay is set, filter the barangays array
        if ($this->searchBarangay) {
            $barangays = array_values(array_filter($barangays, function ($barangay) {
                return stripos($barangay, $this->searchBarangay) !== false; # Case-insensitive search
            }));
        }

        return $barangays;
    }

    #[Computed]
    public function assignments()
    {
        $assignments = Assignment::where('batches_id', decrypt($this->passedId))
            ->get();

        return $assignments;
    }

    #[Computed]
    public function coordinators()
    {
        $coordinators = null;

        if ($this->ignoredIDs) {
            $coordinators = User::where('user_type', 'Coordinator')
                ->where('regional_office', Auth::user()->regional_office)
                ->where('field_office', Auth::user()->field_office)
                ->whereNotIn('id', $this->ignoredIDs)
                ->get();
        } else {
            $coordinators = User::where('user_type', 'Coordinator')
                ->where('regional_office', Auth::user()->regional_office)
                ->where('field_office', Auth::user()->field_office)
                ->get();
        }

        return $coordinators;
    }

    #[Computed]
    public function assignedCoordinators()
    {
        $assignedCoordinators = collect();

        foreach ($this->assignments as $assignment) {
            $coordinator = User::find($assignment->users_id);
            $assignedCoordinators->push([
                'users_id' => $assignment->users_id,
                'first_name' => $coordinator->first_name,
                'middle_name' => $coordinator->middle_name,
                'last_name' => $coordinator->last_name,
                'extension_name' => $coordinator->extension_name,
            ]);
        }

        return $assignedCoordinators;
    }

    # this function adds the selected coordinator from the `Add Coordinator` dropdown
    # and append it to the `Assigned Coordinators` as a toast-like element.
    public function addToastCoordinator()
    {
        if (sizeof($this->coordinators) !== 0) {
            # appends the coordinator to the `Assigned Coordinators` box
            $this->assigned_coordinators[] = [
                'users_id' => $this->coordinators[$this->coordinatorKey]['id'],
                'first_name' => $this->coordinators[$this->coordinatorKey]['first_name'],
                'middle_name' => $this->coordinators[$this->coordinatorKey]['middle_name'],
                'last_name' => $this->coordinators[$this->coordinatorKey]['last_name'],
                'extension_name' => $this->coordinators[$this->coordinatorKey]['extension_name'],
            ];

            # removes the coordinator from the `Add Coordinator` dropdown
            $this->ignoredIDs[] = $this->assigned_coordinators[sizeof($this->assigned_coordinators) - 1]['users_id'];

            $this->currentCoordinator = $this->defaultCoordinator;
            $this->validateOnly('assigned_coordinators');
        }
    }

    # triggers when a user clicks the `X` from the toast of the `Assigned Coordinators` box
    public function removeToastCoordinator($key)
    {
        # removes the coordinator from the `Assigned Coordinators` box
        unset($this->assigned_coordinators[$key]);

        # appens the coordinator to the `Add Coordinator` dropdown
        $this->ignoredIDs = [];
        foreach ($this->assigned_coordinators as $coordinatorId)
            $this->ignoredIDs[] = $coordinatorId['users_id'];
    }

    #[Computed]
    public function getFullNameByFull($first, $middle, $last, $ext)
    {
        $fullName = null;

        $fullName = $first;

        if ($middle) {
            $fullName .= ' ' . $middle;
        }

        $fullName .= ' ' . $last;

        if ($ext) {
            $fullName .= ' ' . $ext;
        }

        return $fullName;
    }

    #[Computed]
    public function getFullName($key)
    {
        $fullName = null;
        $first = $this->coordinators[$key]->first_name;
        $middle = $this->coordinators[$key]->middle_name;
        $last = $this->coordinators[$key]->last_name;
        $ext = $this->coordinators[$key]->extension_name;
        $fullName = $first;

        if ($middle) {
            $fullName .= ' ' . $middle;
        }

        $fullName .= ' ' . $last;

        if ($ext) {
            $fullName .= ' ' . $ext;
        }

        return $fullName;
    }

    #[Computed]
    public function batchCountDelta()
    {
        $batchCountDelta = 0;

        # retrieves all of the `slots_alloted` values from the batches table
        $batchesCount = Batch::join('implementations', 'implementations.id', '=', 'batches.implementations_id')
            ->where('implementations.users_id', Auth::id())
            ->where('implementations.id', $this->batch->implementations_id)
            ->select('slots_allocated')
            ->get();

        # retrieves all the slots allocated from existing (if any) batches
        # and iterate it as a single value
        foreach ($batchesCount as $batch) {
            $batchCountDelta += $batch->slots_allocated;
        }

        return $batchCountDelta;
    }

    #[Computed]
    public function remainingSlots()
    {
        # This condition initializes the slots & give an indicator of how many remaining slots left for the user to input
        $this->totalSlots = $this->implementation->total_slots;

        # re-assign the total slots based on batch counts (if any)
        # then assign the remaining slots to it
        $this->totalSlots -= $this->batchCountDelta;
        $remainingSlots = $this->totalSlots;

        # this condition basically filters the input to take only numbers
        if (ctype_digit($this->slots_allocated)) {
            # assign the difference between the remaining slots and the input slots value
            $newRemainingSlots = intval($remainingSlots) - intval($this->slots_allocated);

            # it also filters
            if ($newRemainingSlots >= 0) {
                $remainingSlots = $newRemainingSlots;
            } else if ($newRemainingSlots < 0) {
                $remainingSlots = 0;
            }
        }

        return $remainingSlots;
    }

    # Check if there are any existing beneficiaries under this batch
    public function checkEmpty()
    {
        $query = Beneficiary::where('batches_id', decrypt($this->passedId))
            ->exists();

        # If there's any rows that exists...
        if ($query) {
            # then it's not empty
            $this->isEmpty = false;
        } else {
            # otherwise, it is empty.
            $this->isEmpty = true;
        }
    }

    public function toggleEdit()
    {
        $this->edit = !$this->edit;

        if ($this->edit) {
            # Only initialize values on fields if edit mode is on
            $this->batch_num = intval(substr($this->batch->batch_num, strlen($this->batchNumPrefix)));
            $this->barangay_name = $this->batch->barangay_name;
            $this->slots_allocated = $this->batch->slots_allocated;

            # assign the coordinators by collection array
            $this->assigned_coordinators = $this->assignedCoordinators;

            foreach ($this->assigned_coordinators as $assignment) {
                $this->ignoredIDs[] = $assignment['users_id'];
            }

            $this->coordinatorKey = 0;
            $this->currentCoordinator = $this->getFullName($this->coordinatorKey);
        } else {
            $this->reset(
                'batch_num',
                'barangay_name',
                'slots_allocated',
                'assigned_coordinators',
            );
            $this->coordinatorKey = -1;
            $this->currentCoordinator = 'N/A';
        }
    }

    public function deleteBatch()
    {
        $batch = Batch::find(decrypt($this->passedId));
        $this->authorize('delete-batch', $batch);
        $batch->delete();

        $this->edit = false;
        $this->resetEverything();
        $this->dispatch('delete-batches');
    }

    public function resetEverything()
    {
        if ($this->edit) {
            $this->reset(
                'batch_num',
                'barangay_name',
                'slots_allocated',
                'assigned_coordinators',
                'edit',
            );
        }
    }

    public function render()
    {
        # Check if there's no batches made with this project yet
        $this->checkEmpty();
        $settings = UserSetting::where('users_id', Auth::id())
            ->pluck('value', 'key');
        $this->batchNumPrefix = $settings->get('batch_number_prefix', config('settings.batch_number_prefix'));
        return view('livewire.focal.implementations.view-batch');
    }
}
