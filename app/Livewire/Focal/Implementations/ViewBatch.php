<?php

namespace App\Livewire\Focal\Implementations;

use App\Models\Assignment;
use App\Models\Batch;
use App\Models\Beneficiary;
use App\Models\Implementation;
use App\Models\User;
use App\Models\UserSetting;
use App\Services\Barangays;
use Auth;
use DB;
use Hash;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Reactive;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ViewBatch extends Component
{
    #[Reactive]
    #[Locked]
    public $passedBatchId;
    public $batchNumPrefix;
    public $remainingSlots;
    public $totalSlots;
    public $ignoredCoordinatorIDs;
    public $selectedCoordinatorKey;
    public $currentCoordinator;
    public $searchCoordinator;
    public $searchBarangay;
    public $editMode = false;
    #[Locked]
    public $isEmpty = true;
    public $deleteBatchModal = false;
    public $batchesCount;
    public $selectedBatchListRow = -1;
    #[Validate]
    public $view_batch_num;
    #[Validate]
    public $view_barangay_name;
    #[Validate]
    public $view_slots_allocated;
    #[Validate]
    public $view_assigned_coordinators = [];
    #[Validate]
    public $password;

    # Runs real-time depending on wire:model suffix
    public function rules()
    {
        return [
            # View Batch Modal
            'view_batch_num' => [
                'required',
                'integer',
                # Checks uniqueness from the `Database`
                function ($attribute, $value, $fail) {

                    # Check for uniqueness of the prefixed value in the database
                    $exists = DB::table('batches')
                        ->where('batch_num', $this->batchNumPrefix . $value)
                        ->whereNotIn('id', [$this->batch->id])
                        ->exists();

                    if ($exists) {
                        # Fail the validation if the project number with the prefix already exists
                        $fail('This :attribute already exists.');
                    }
                },
            ],
            'view_barangay_name' => [
                'required',

                # Checks uniqueness from the `Database`
                function ($attribute, $value, $fail) {

                    # Check for uniqueness of the prefixed value in the database
                    $exists = Batch::where('implementations_id', $this->implementation->id)
                        ->where('barangay_name', $value)
                        ->whereNotIn('id', [$this->batch->id])
                        ->exists();

                    if ($exists) {
                        # Fail the validation if this barangay already existed 
                        $fail('This :attribute already existed on this project.');
                    }
                },
            ],
            'view_slots_allocated' => [
                'required',
                'integer',
                'gte:0',
                'min:1',
                'lte:' . $this->totalSlots,
            ],
            'view_assigned_coordinators' => 'required',
            'password' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!Hash::check($value, Auth::user()->password)) {
                        $fail('Wrong password.');
                    }
                },
            ],
        ];
    }

    # The validation error messages
    public function messages()
    {
        return [
            # View Batch Modal
            'view_batch_num.required' => 'The :attribute should not be empty.',
            'view_barangay_name.required' => 'The :attribute should not be empty.',
            'view_slots_allocated.required' => 'Invalid :attribute amount.',
            'view_assigned_coordinators.required' => 'There should be at least 1 :attribute.',
            'view_batch_num.integer' => 'The :attribute should be a valid number.',
            'view_slots_allocated.integer' => ':attribute should be a valid number.',
            'view_slots_allocated.min' => ':attribute should be > 0.',
            'view_slots_allocated.gte' => ':attribute should be nonnegative.',
            'view_slots_allocated.lte' => ':attribute should be less than total.',
            'password.required' => 'This field is required.',
        ];
    }

    # Validation attribute names for human readability purpose
    public function validationAttributes()
    {
        return [
            # View Batch Modal
            'view_batch_num' => 'batch number',
            'view_barangay_name' => 'barangay',
            'view_slots_allocated' => 'Slots',
            'view_assigned_coordinators' => 'assigned coordinator',
        ];
    }

    # this function adds the selected coordinator from the `Add Coordinator` dropdown
    # and append it to the `Assigned Coordinators` as a toast-like element.
    # It would also remove the added coordinator from the `Add Coordinator` dropdown list
    # so as to avoid duplicating/conflicting names.
    public function addToastCoordinator()
    {
        if ($this->coordinators->isNotEmpty()) {

            # appends the coordinator to the `Assigned Coordinators` box
            $this->view_assigned_coordinators[] = [
                'users_id' => encrypt($this->coordinators[$this->selectedCoordinatorKey]->id),
                'first_name' => $this->coordinators[$this->selectedCoordinatorKey]->first_name,
                'middle_name' => $this->coordinators[$this->selectedCoordinatorKey]->middle_name,
                'last_name' => $this->coordinators[$this->selectedCoordinatorKey]->last_name,
                'extension_name' => $this->coordinators[$this->selectedCoordinatorKey]->extension_name,
            ];

            $this->validateOnly('view_assigned_coordinators');

            # removes the coordinator from the `Add Coordinator` dropdown
            $this->ignoredCoordinatorIDs[] = encrypt($this->coordinators[$this->selectedCoordinatorKey]->id);

            # bust the cache to refresh the coordinators list
            unset($this->coordinators);
            $this->setCoordinator();

        }
    }

    # triggers when a user clicks the `X` from the toast of the `Assigned Coordinators` box
    public function removeToastCoordinator($key)
    {
        # removes the coordinator from the `Assigned Coordinators` box
        unset($this->view_assigned_coordinators[$key]);

        # appens the coordinator back to the `Add Coordinator` dropdown
        unset($this->ignoredCoordinatorIDs[$key]);

        # bust the cache to refresh the coordinators list
        unset($this->coordinators);
        $this->setCoordinator();
    }

    public function liveUpdateRemainingSlots()
    {
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
        $this->totalSlots = $this->implementation->total_slots;

        # retrieves all of the `slots_alloted` values from the batches table
        $this->batchesCount = Batch::join('implementations', 'implementations.id', '=', 'batches.implementations_id')
            ->where('implementations.users_id', Auth::id())
            ->where('implementations.id', $this->implementation->id)
            ->whereNotIn('batches.id', [decrypt($this->passedBatchId)])
            ->select('batches.slots_allocated')
            ->get();

        # retrieves all the slots allocated from existing (if any) batches
        # and iterate it as a single value
        foreach ($this->batchesCount as $batch) {
            $batchCountDelta += $batch['slots_allocated'];
        }

        # re-assign the total slots based on batch counts (if any)
        # then assign the remaining slots to it
        $this->totalSlots -= $batchCountDelta;
        $this->remainingSlots = $this->totalSlots;

        # this condition basically filters the input to take only numbers
        if (ctype_digit((string) $this->view_slots_allocated)) {

            # assign the difference between the remaining slots and the input slots value
            $newRemainingSlots = 0;
            $newRemainingSlots = intval($this->remainingSlots) - intval($this->view_slots_allocated);

            # it also filters
            if ($newRemainingSlots >= 0) {
                $this->remainingSlots = $newRemainingSlots;
            } else if ($newRemainingSlots < 0) {
                $this->remainingSlots = 0;
            }
        }
    }

    # Check if there are any existing beneficiaries under this batch
    public function checkEmpty()
    {
        if ($this->passedBatchId) {
            $query = Beneficiary::where('batches_id', decrypt($this->passedBatchId))
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
    }

    public function toggleEditBatch()
    {
        $this->editMode = !$this->editMode;

        if ($this->editMode) {
            # Only initialize values on fields if edit mode is on
            $this->view_batch_num = intval(substr($this->batch->batch_num, strlen($this->batchNumPrefix)));
            $this->view_barangay_name = $this->batch->barangay_name;
            $this->view_slots_allocated = $this->batch->slots_allocated;

            # assign the coordinators by collection array
            foreach ($this->assignedCoordinators as $assignment) {
                $this->view_assigned_coordinators[] = [
                    'users_id' => encrypt($assignment['users_id']),
                    'first_name' => $assignment['first_name'],
                    'middle_name' => $assignment['middle_name'],
                    'last_name' => $assignment['last_name'],
                    'extension_name' => $assignment['extension_name'],
                ];
                $this->ignoredCoordinatorIDs[] = encrypt($assignment['users_id']);
            }

            $this->setCoordinator();
        } else {
            $this->reset(
                'view_batch_num',
                'view_barangay_name',
                'view_slots_allocated',
                'view_assigned_coordinators',
                'ignoredCoordinatorIDs',
            );

            unset($this->batch);
            unset($this->coordinators);
            $this->setCoordinator();
        }
    }

    # Deletes the batch as long as there's empty beneficiaries on it
    public function deleteBatch()
    {
        $this->validateOnly('password');
        $assignments = Assignment::where('batches_id', decrypt($this->passedBatchId))
            ->get();
        $batch = Batch::find(decrypt($this->passedBatchId));

        $this->authorize('delete-batch-focal', $batch);

        foreach ($assignments as $assignment) {
            $assignment->delete();
        }

        $batch->delete();

        $this->editMode = false;
        $this->dispatch('delete-batch');
        $this->resetViewBatch();
    }

    # saves the edits made on the batch
    public function editBatch()
    {
        $batch = Batch::find(decrypt($this->passedBatchId));

        if ($this->isEmpty) {

            # validate the fields before proceeding
            $this->validate(
                [
                    'view_batch_num' => [
                        'required',
                        'integer',
                        # Checks uniqueness from the `Database`
                        function ($attribute, $value, $fail) {

                            # Check for uniqueness of the prefixed value in the database
                            $exists = DB::table('batches')
                                ->where('batch_num', $this->batchNumPrefix . $value)
                                ->whereNotIn('id', [$this->batch->id])
                                ->exists();

                            if ($exists) {
                                # Fail the validation if the project number with the prefix already exists
                                $fail('This :attribute already exists.');
                            }
                        },
                    ],
                    'view_barangay_name' => [
                        'required',

                        # Checks uniqueness from the `Database`
                        function ($attribute, $value, $fail) {

                            # Check for uniqueness of the prefixed value in the database
                            $exists = Batch::where('implementations_id', $this->implementation->id)
                                ->where('barangay_name', $value)
                                ->whereNotIn('id', [$this->batch->id])
                                ->exists();

                            if ($exists) {
                                # Fail the validation if this barangay already existed 
                                $fail('This :attribute already existed on this project.');
                            }
                        },
                    ],
                    'view_slots_allocated' => [
                        'required',
                        'integer',
                        'gte:0',
                        'min:1',
                        'lte:' . $this->totalSlots,
                    ],
                    'view_assigned_coordinators' => 'required',
                ],
                [
                    'view_batch_num.required' => 'The :attribute should not be empty.',
                    'view_barangay_name.required' => 'The :attribute should not be empty.',
                    'view_slots_allocated.required' => 'Invalid :attribute amount.',
                    'view_assigned_coordinators.required' => 'There should be at least 1 :attribute.',

                    'view_batch_num.integer' => 'The :attribute should be a valid number.',

                    'view_slots_allocated.integer' => ':attribute should be a valid number.',
                    'view_slots_allocated.min' => ':attribute should be > 0.',
                    'view_slots_allocated.gte' => ':attribute should be nonnegative.',
                    'view_slots_allocated.lte' => ':attribute should be less than total.',
                ],
                [
                    'view_batch_num' => 'batch number',
                    'view_barangay_name' => 'barangay',
                    'view_slots_allocated' => 'Slots',
                    'view_assigned_coordinators' => 'assigned coordinator',
                ]
            );

            # save any updates on the batch model
            $batch->batch_num = $this->batchNumPrefix . $this->view_batch_num;
            $batch->barangay_name = $this->view_barangay_name;
            $batch->slots_allocated = $this->view_slots_allocated;
            $batch->save();

            # first, get all the coordinators IDs to ignore
            foreach ($this->view_assigned_coordinators as $assignedCoordinator) {

                # create new assignments if these IDs hasn't been created yet
                Assignment::firstOrCreate(
                    [
                        'batches_id' => $batch->id,
                        'users_id' => decrypt($assignedCoordinator['users_id'])
                    ]
                );
            }

            # get all ignored IDs
            $ignoredIDs = [];
            foreach ($this->ignoredCoordinatorIDs as $id) {
                $ignoredIDs[] = decrypt($id);
            }

            # then get all the assignments except those in the `whereNotIn`
            $assignments = Assignment::where('batches_id', decrypt($this->passedBatchId))
                ->whereNotIn('users_id', $ignoredIDs)
                ->get();

            # check first if there are any `assignments` returned
            if ($assignments->isNotEmpty()) {

                # then remove those who were not in `ignoredCoordinatorIDs`
                foreach ($assignments as $assignment) {
                    $assignment->delete();
                }
            }

        } else {

            # validate the fields before proceeding
            $this->validate(
                [
                    'view_batch_num' => [
                        'required',
                        'integer',
                        # Checks uniqueness from the `Database`
                        function ($attribute, $value, $fail) {

                            # Check for uniqueness of the prefixed value in the database
                            $exists = DB::table('batches')
                                ->where('batch_num', $this->batchNumPrefix . $value)
                                ->whereNotIn('id', [$this->batch->id])
                                ->exists();

                            if ($exists) {
                                # Fail the validation if the project number with the prefix already exists
                                $fail('This :attribute already exists.');
                            }
                        },
                    ],
                    'view_assigned_coordinators' => 'required',
                ],

                [
                    'view_batch_num.required' => 'The :attribute should not be empty.',
                    'view_assigned_coordinators.required' => 'There should be at least 1 :attribute.',
                    'view_batch_num.integer' => 'The :attribute should be a valid number.',
                ],

                [
                    'view_batch_num' => 'batch number',
                    'view_assigned_coordinators' => 'assigned coordinator',
                ]
            );

            # save any updates on the batch model
            $batch->batch_num = $this->batchNumPrefix . $this->view_batch_num;
            $batch->save();

            # first, get all the coordinators IDs to ignore
            foreach ($this->view_assigned_coordinators as $assignedCoordinator) {

                # create new assignments if these IDs hasn't been created yet
                Assignment::firstOrCreate(
                    [
                        'batches_id' => $batch->id,
                        'users_id' => decrypt($assignedCoordinator['users_id'])
                    ]
                );
            }

            # get all ignored IDs
            $ignoredIDs = [];
            foreach ($this->ignoredCoordinatorIDs as $id) {
                $ignoredIDs[] = decrypt($id);
            }

            # then get all the assignments except those in the `whereNotIn`
            $assignments = Assignment::where('batches_id', decrypt($this->passedBatchId))
                ->whereNotIn('users_id', $ignoredIDs)
                ->get();

            # check first if there are any `assignments` returned
            if ($assignments->isNotEmpty()) {

                # then remove those who were not in `ignoredCoordinatorIDs`
                foreach ($assignments as $assignment) {
                    $assignment->delete();
                }
            }
        }

        $this->dispatch('edit-batch');
        $this->toggleEditBatch();
    }

    #[Computed]
    public function coordinators()
    {
        $coordinators = null;

        if ($this->ignoredCoordinatorIDs) {
            $ignoredIDs = [];
            foreach ($this->ignoredCoordinatorIDs as $encryptedId) {
                $ignoredIDs[] = decrypt($encryptedId);
            }

            $coordinators = User::where('user_type', 'Coordinator')
                ->where('regional_office', Auth::user()->regional_office)
                ->where('field_office', Auth::user()->field_office)
                ->when($this->searchCoordinator, function ($q) {
                    # Otherwise, search by first, middle, or last name
                    $q->where(function ($query) {
                        $searchValue = trim($this->searchCoordinator);
                        $query->where('first_name', 'LIKE', '%' . $searchValue . '%')
                            ->orWhere('middle_name', 'LIKE', '%' . $searchValue . '%')
                            ->orWhere('last_name', 'LIKE', '%' . $searchValue . '%');
                    });
                })
                ->whereNotIn('id', $ignoredIDs)
                ->get();

        } else {
            $coordinators = User::where('user_type', 'Coordinator')
                ->where('regional_office', Auth::user()->regional_office)
                ->where('field_office', Auth::user()->field_office)
                ->when($this->searchCoordinator, function ($q) {
                    # Otherwise, search by first, middle, or last name
                    $q->where(function ($query) {
                        $searchValue = trim($this->searchCoordinator);
                        $query->where('first_name', 'LIKE', '%' . $searchValue . '%')
                            ->orWhere('middle_name', 'LIKE', '%' . $searchValue . '%')
                            ->orWhere('last_name', 'LIKE', '%' . $searchValue . '%');
                    });
                })
                ->get();
        }

        return $coordinators;
    }

    #[Computed]
    public function implementation()
    {
        if ($this->passedBatchId) {
            $implementation = Implementation::find($this->batch->implementations_id);
            return $implementation;
        }
    }

    #[Computed]
    public function batch()
    {
        if ($this->passedBatchId) {
            $batch = Batch::find(decrypt($this->passedBatchId));
            return $batch;
        }
    }

    #[Computed]
    public function assignments()
    {
        if ($this->passedBatchId) {
            $assignments = Assignment::where('batches_id', decrypt($this->passedBatchId))
                ->get();

            return $assignments;
        }
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

    # this function returns all of the barangays based on the project's location
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
    public function getFullName($person)
    {
        $fullName = $person['first_name'];

        if ($person['middle_name']) {
            $fullName .= ' ' . $person['middle_name'];
        }

        $fullName .= ' ' . $person['last_name'];

        if ($person['extension_name']) {
            $fullName .= ' ' . $person['extension_name'];
        }

        return $fullName;
    }
    public function resetViewBatch()
    {
        $this->reset(
            'view_batch_num',
            'view_barangay_name',
            'view_slots_allocated',
            'view_assigned_coordinators',
            'editMode',
            'remainingSlots',
            'totalSlots',
            'ignoredCoordinatorIDs',
            'selectedCoordinatorKey',
            'searchBarangay',
            'searchCoordinator',
            'deleteBatchModal',
        );
        $this->resetValidation();
    }

    public function setCoordinator()
    {
        if ($this->coordinators->isEmpty()) {
            $this->selectedCoordinatorKey = -1;
            $this->currentCoordinator = 'None';
        } else {
            $this->selectedCoordinatorKey = 0;
            $this->currentCoordinator = $this->getFullName($this->coordinators[$this->selectedCoordinatorKey]);
        }
    }

    public function mount()
    {
        $settings = UserSetting::where('users_id', Auth::id())
            ->pluck('value', 'key');
        $this->batchNumPrefix = $settings->get('batch_number_prefix', config('settings.batch_number_prefix'));
        $this->setCoordinator();
    }

    public function render()
    {
        # View Batch Modal
        $this->checkEmpty();

        if ($this->passedBatchId) {
            $this->liveUpdateRemainingSlots();
        }

        return view('livewire.focal.implementations.view-batch');
    }
}
