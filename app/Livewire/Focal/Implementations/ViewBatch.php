<?php

namespace App\Livewire\Focal\Implementations;

use App\Models\Assignment;
use App\Models\Batch;
use App\Models\Beneficiary;
use App\Models\Code;
use App\Models\Implementation;
use App\Models\User;
use App\Models\UserSetting;
use App\Services\Barangays;
use App\Services\Districts;
use App\Services\LogIt;
use Auth;
use DB;
use Hash;
use Illuminate\Auth\Access\AuthorizationException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Reactive;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Log;

class ViewBatch extends Component
{
    #[Reactive]
    #[Locked]
    public $batchId;
    public $batchNumPrefix;

    # --------------------------------------------------------------------------

    public $accessCodeModal = false;
    public $forceApproveModal = false;
    public $pendBatchModal = false;
    public $deleteBatchModal = false;
    #[Locked]
    public $code;
    #[Validate]
    public $password_force_approve;
    #[Validate]
    public $password_pend_batch;

    # --------------------------------------------------------------------------
    public $remainingSlots;
    public $totalSlots;
    public $ignoredCoordinatorIDs;
    public $selectedCoordinatorKey;
    public $currentCoordinator;
    public $searchCoordinator;
    public $searchBarangay;
    public $editMode = false;
    public $batchesCount;
    public $selectedBatchListRow = -1;

    # --------------------------------------------------------------------------

    #[Validate]
    public $batch_num;
    #[Validate]
    public $is_sectoral = 0;
    #[Validate]
    public $sector_title;
    #[Validate]
    public $district;
    #[Validate]
    public $barangay_name;
    #[Validate]
    public $slots_allocated;
    #[Validate]
    public $assigned_coordinators = [];

    # Runs real-time depending on wire:model suffix
    public function rules()
    {
        return [
            # View Batch Modal
            'batch_num' => [
                'required',
                # Checks uniqueness from the `Database`
                function ($attribute, $value, $fail) {

                    # Check for uniqueness of the prefixed value in the database
                    $exists = DB::table('batches')
                        ->where('batch_num', $this->batchNumPrefix . $value)
                        ->whereNotIn('id', [$this->batch->id])
                        ->exists();

                    if ($exists) {
                        # Fail the validation if the project number with the prefix already exists
                        $fail('This batch number already exists.');
                    }
                },
            ],
            'is_sectoral' => 'required|integer',
            'sector_title' => [
                'exclude_if:is_sectoral,0',
                'required_if:is_sectoral,1',
                'string',
                'min:1',
                'max:64'
            ],
            'district' => [
                'exclude_if:is_sectoral,1',
                'required_if:is_sectoral,0',
            ],
            'barangay_name' => [
                'exclude_if:is_sectoral,1',
                'required_if:is_sectoral,0',

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
            'slots_allocated' => [
                'required',
                'integer',
                'gte:0',
                'min:1',
                'lte:' . $this->totalSlots,
            ],
            'assigned_coordinators' => 'required',
            'password_force_approve' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!Hash::check($value, Auth::user()->password)) {
                        $fail('Wrong password.');
                    }
                },

            ],
            'password_pend_batch' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!Hash::check($value, Auth::user()->password)) {
                        $fail('Wrong password.');
                    }
                },
            ]
        ];
    }

    # The validation error messages
    public function messages()
    {
        return [
            # View Batch Modal
            'batch_num.required' => 'This field is required.',
            'is_sectoral.required' => 'Please select a type.',
            'sector_title.required_if' => 'This field is required.',
            'barangay_name.required_if' => 'This field is required.',
            'district.required_if' => 'This field is required.',
            'slots_allocated.required' => 'Invalid :attribute amount.',
            'assigned_coordinators.required' => 'There should be at least 1 :attribute.',

            'is_sectoral.integer' => 'Invalid type.',

            'sector_title.string' => 'Value should be a string.',
            'sector_title.min' => 'Value should have at least 1 character.',
            'sector_title.max' => 'Value cannot exceed more than 64 characters.',

            'slots_allocated.integer' => ':attribute should be a valid number.',
            'slots_allocated.min' => ':attribute should be > 0.',
            'slots_allocated.gte' => ':attribute should be nonnegative.',
            'slots_allocated.lte' => ':attribute should be less than total.',

            'password_force_approve.required' => 'This field is required.',
            'password_pend_batch.required' => 'This field is required.',
        ];
    }

    # Validation attribute names for human readability purpose
    public function validationAttributes()
    {
        return [
            # View Batch Modal
            'barangay_name' => 'barangay',
            'slots_allocated' => 'Slots',
            'assigned_coordinators' => 'assigned coordinator',
        ];
    }

    # ----------------------------------------------------------------------------------------------

    public function forceApprove()
    {
        DB::transaction(function () {
            try {
                $this->validateOnly('password_force_approve');
                $batch = Batch::lockForUpdate()->findOrFail($this->batchId ? decrypt($this->batchId) : null);

                $this->authorize('batch-focal', $batch);

                if (in_array($batch->submission_status, ['encoding', 'revalidate'])) {
                    $accessCode = Code::where('batches_id', $batch->id)
                        ->where('is_accessible', 'yes')
                        ->lockForUpdate()
                        ->first();

                    if ($accessCode) {
                        $accessCode->is_accessible = 'no';
                        $accessCode->save();

                        $batch->submission_status = 'submitted';
                    }
                }

                $batch->approval_status = 'approved';
                $batch->save();

                LogIt::set_force_approve($this->batch, auth()->user());
                $this->dispatch('alertNotification', type: 'batch-modify', message: 'Successfully approved the batch submission', color: 'indigo');

            } catch (AuthorizationException $e) {
                DB::rollBack();
                LogIt::set_log_exception('An unauthorized action has been made while approving a batch. Error ' . $e->getCode(), auth()->user(), $e->getTrace());
                $this->dispatch('alertNotification', type: 'batch-modify', message: $e->getMessage(), color: 'red');
            } catch (\Throwable $e) {
                DB::rollBack();
                LogIt::set_log_exception('An error has occured during execution. Error ' . $e->getCode(), auth()->user(), $e->getTrace());
                $this->dispatch('alertNotification', type: 'batch-modify', message: 'An error has occured during execution. Error ' . $e->getCode(), color: 'red');

                $this->js('viewBatchModal = false;');
            } finally {
                $this->forceApproveModal = false;
                $this->resetPasswords();
            }
        }, 5);
    }

    public function pendBatch()
    {
        DB::transaction(function () {
            try {
                $this->validateOnly('password_pend_batch');
                $batch = Batch::lockForUpdate()->findOrFail($this->batchId ? decrypt($this->batchId) : null);

                $this->authorize('batch-focal', $batch);

                $batch->approval_status = 'pending';
                $batch->save();

                LogIt::set_pend_batch($this->batch, auth()->user());
                $this->dispatch('alertNotification', type: 'batch-modify', message: 'Successfully reverted batch status to pending', color: 'indigo');

            } catch (AuthorizationException $e) {
                DB::rollBack();
                LogIt::set_log_exception('An unauthorized action has been made while marking a batch to pending. Error ' . $e->getCode(), auth()->user(), $e->getTrace());
                $this->dispatch('alertNotification', type: 'batch-modify', message: $e->getMessage(), color: 'red');
            } catch (\Throwable $e) {
                DB::rollBack();
                LogIt::set_log_exception('An error has occured during execution. Error ' . $e->getCode(), auth()->user(), $e->getTrace());
                $this->dispatch('alertNotification', type: 'batch-modify', message: 'An error has occured during execution. Error ' . $e->getCode(), color: 'red');

                $this->js('viewBatchModal = false;');
            } finally {
                $this->pendBatchModal = false;
                $this->resetPasswords();
            }
        }, 5);
    }

    public function resetPasswords()
    {
        $this->reset('password_force_approve', 'password_pend_batch');
        $this->resetValidation(['password_force_approve', 'password_pend_batch']);
    }

    # ----------------------------------------------------------------------------------------------

    # this function adds the selected coordinator from the `Add Coordinator` dropdown
    # and append it to the `Assigned Coordinators` as a toast-like element.
    # It would also remove the added coordinator from the `Add Coordinator` dropdown list
    # so as to avoid duplicating/conflicting names.
    public function addToastCoordinator()
    {
        if ($this->coordinators->isNotEmpty()) {

            # appends the coordinator to the `Assigned Coordinators` box
            $this->assigned_coordinators[] = [
                'users_id' => encrypt($this->coordinators[$this->selectedCoordinatorKey]->id),
                'first_name' => $this->coordinators[$this->selectedCoordinatorKey]->first_name,
                'middle_name' => $this->coordinators[$this->selectedCoordinatorKey]->middle_name,
                'last_name' => $this->coordinators[$this->selectedCoordinatorKey]->last_name,
                'extension_name' => $this->coordinators[$this->selectedCoordinatorKey]->extension_name,
            ];

            $this->validateOnly('assigned_coordinators');

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
        unset($this->assigned_coordinators[$key]);

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
        $this->totalSlots = $this->implementation?->total_slots ?? 0;

        # retrieves all of the `slots_alloted` values from the batches table
        $this->batchesCount = Batch::join('implementations', 'implementations.id', '=', 'batches.implementations_id')
            ->where('implementations.users_id', Auth::id())
            ->where('implementations.id', $this->implementation?->id)
            ->whereNotIn('batches.id', [$this->batchId ? decrypt($this->batchId) : null])
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
        if (ctype_digit((string) $this->slots_allocated)) {

            # assign the difference between the remaining slots and the input slots value
            $newRemainingSlots = 0;
            $newRemainingSlots = intval($this->remainingSlots) - intval($this->slots_allocated);

            # it also filters
            if ($newRemainingSlots >= 0) {
                $this->remainingSlots = $newRemainingSlots;
            } else if ($newRemainingSlots < 0) {
                $this->remainingSlots = 0;
            }
        }
    }

    # Check if there are any existing beneficiaries under this batch
    #[Computed]
    public function isEmpty()
    {
        $query = Beneficiary::where('batches_id', $this->batchId ? decrypt($this->batchId) : null)
            ->exists();

        # If there's no rows...
        if (!$query) {
            # then it's empty
            return true;
        }

        # otherwise, it is empty.
        return false;
    }

    public function toggleEditBatch()
    {
        $this->editMode = !$this->editMode;

        if ($this->editMode) {
            $batch = Batch::find($this->batchId ? decrypt($this->batchId) : null);

            if (!$batch) {
                $this->js('viewBatchModal = false;');
                $this->dispatch('alertNotification', type: 'batch-modify', message: 'This batch does not exist', color: 'red');
                $this->resetExcept(
                    'batchId',
                    'batchNumPrefix',
                    'code',
                );
                return;
            }

            # Only initialize values on fields if edit mode is on
            $this->batch_num = intval(substr($batch?->batch_num, strlen($this->batchNumPrefix)));
            $this->is_sectoral = $batch?->is_sectoral;
            $this->sector_title = $batch?->sector_title;
            $this->district = $batch?->district;
            $this->barangay_name = $batch?->barangay_name;
            $this->slots_allocated = $batch?->slots_allocated;

            # assign the coordinators by collection array
            foreach ($this->assignedCoordinators as $assignment) {
                $this->assigned_coordinators[] = [
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
                'batch_num',
                'is_sectoral',
                'barangay_name',
                'slots_allocated',
                'assigned_coordinators',
                'ignoredCoordinatorIDs',
            );

            unset($this->batch);
            unset($this->coordinators);
            $this->setCoordinator();
        }
    }

    # saves the edits made on the batch
    public function editBatch()
    {
        if ($this->isEmpty) {
            # validate the fields before proceeding
            $this->validate(
                [
                    'batch_num' => [
                        'required',

                        # Checks uniqueness from the `Database`
                        function ($attribute, $value, $fail) {

                            # Check for uniqueness of the prefixed value in the database
                            $exists = DB::table('batches')
                                ->where('batch_num', $this->batchNumPrefix . $value)
                                ->whereNotIn('id', [$this->batch->id])
                                ->exists();

                            if ($exists) {
                                # Fail the validation if the project number with the prefix already exists
                                $fail('This batch number already exists.');
                            }
                        },
                    ],
                    'is_sectoral' => 'required|integer',
                    'sector_title' => [
                        'exclude_if:is_sectoral,0',
                        'required_if:is_sectoral,1',
                        'string',
                        'min:1',
                        'max:64'
                    ],
                    'district' => [
                        'exclude_if:is_sectoral,1',
                        'required_if:is_sectoral,0',
                    ],
                    'barangay_name' => [
                        'exclude_if:is_sectoral,1',
                        'required_if:is_sectoral,0',

                        # Checks uniqueness from the `Database`
                        function ($attribute, $value, $fail) {

                            # Check for uniqueness of the prefixed value in the database
                            $exists = Batch::where('implementations_id', $this->implementation->id)
                                ->where('barangay_name', $value)
                                ->whereNotIn('id', [$this->batch->id])
                                ->exists();

                            if ($exists) {
                                # Fail the validation if this barangay already existed 
                                $fail('This batch number already existed on this project.');
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
                ],
                [
                    'is_sectoral.required' => 'Please select a type.',
                    'sector_title.required_if' => 'This field is required.',
                    'barangay_name.required_if' => 'This field is required.',
                    'district.required_if' => 'This field is required.',
                    'slots_allocated.required' => 'Invalid :attribute amount.',
                    'assigned_coordinators.required' => 'There should be at least 1 :attribute.',

                    'is_sectoral.integer' => 'Invalid type.',
                    'sector_title.string' => 'Value should be a string.',
                    'sector_title.min' => 'Value should have at least 1 character.',
                    'sector_title.max' => 'Value cannot exceed more than 64 characters.',

                    'slots_allocated.integer' => ':attribute should be a valid number.',
                    'slots_allocated.min' => ':attribute should be > 0.',
                    'slots_allocated.gte' => ':attribute should be nonnegative.',
                    'slots_allocated.lte' => ':attribute should be less than total.',
                ],
                [
                    'barangay_name' => 'barangay',
                    'slots_allocated' => 'Slots',
                    'assigned_coordinators' => 'assigned coordinator',
                ]
            );
        } else {
            # validate the fields before proceeding
            $this->validate(
                [
                    'is_sectoral' => 'required|integer',
                    'sector_title' => [
                        'exclude_if:is_sectoral,0',
                        'required_if:is_sectoral,1',
                        'string',
                        'min:1',
                        'max:64'
                    ],
                    'assigned_coordinators' => 'required',
                ],

                [
                    'is_sectoral.required' => 'Please select a type.',
                    'is_sectoral.integer' => 'Invalid type.',
                    'sector_title.required_if' => 'This field is required.',
                    'sector_title.string' => 'Value should be a string.',
                    'sector_title.min' => 'Value should have at least 1 character.',
                    'sector_title.max' => 'Value cannot exceed more than 64 characters.',

                    'assigned_coordinators.required' => 'There should be at least 1 :attribute.',
                ],

                [
                    'assigned_coordinators' => 'assigned coordinator',
                ]
            );
        }

        DB::transaction(function () {
            try {
                $batch = Batch::with([
                    'implementation' => function ($query) {
                        $query->lockForUpdate();
                    }
                ])->findOrFail(decrypt($this->batchId));
                $implementation = $batch->implementation;
                $assignmentsDirty = false;
                $this->authorize('batch-focal', $batch);

                if ($this->isEmpty) {

                    # save any updates on the batch model
                    $batch->is_sectoral = $this->is_sectoral;

                    if ($this->is_sectoral) {

                        $batch->sector_title = $this->sector_title;
                        $batch->district = null;
                        $batch->barangay_name = null;

                    } elseif (!$this->is_sectoral) {

                        $batch->sector_title = null;
                        $batch->district = $this->district;
                        $batch->barangay_name = $this->barangay_name;

                    }

                    $batch->slots_allocated = $this->slots_allocated;

                } else {
                    # save any updates on the batch model
                    if ($batch->is_sectoral) {
                        $batch->sector_title = $this->sector_title;
                    } elseif (!$batch->is_sectoral) {
                        $batch->sector_title = null;
                    }
                }

                # first, get all the coordinators IDs to ignore
                foreach ($this->assigned_coordinators as $assignedCoordinator) {

                    # create new assignments if these IDs hasn't been created yet
                    $ass = Assignment::firstOrCreate(
                        [
                            'batches_id' => $batch->id,
                            'users_id' => decrypt($assignedCoordinator['users_id'])
                        ]
                    );

                    # if it's a `create`, it will determine the Assignment model as `Dirty`
                    # and will log the newly assigned coordinator.
                    if ($ass->wasRecentlyCreated) {
                        $assignmentsDirty = true;
                        LogIt::set_assign_coordinator_to_batch($batch, $ass, auth()->user(), 'update');
                    }
                }

                # get all ignored IDs
                $ignoredIDs = [];
                foreach ($this->ignoredCoordinatorIDs as $id) {
                    $ignoredIDs[] = decrypt($id);
                }

                # then get all the assignments except those in the `whereNotIn`
                $assignments = Assignment::where('batches_id', decrypt($this->batchId))
                    ->whereNotIn('users_id', $ignoredIDs)
                    ->lockForUpdate()
                    ->get();

                # check first if there are any `assignments` returned
                if ($assignments->isNotEmpty()) {

                    # then this means that there are some assignment changes
                    $assignmentsDirty = true;

                    # then remove those who were not in `ignoredCoordinatorIDs`
                    foreach ($assignments as $assignment) {
                        $assignment->deleteOrFail();
                        LogIt::set_remove_coordinator_assignment($assignment, $batch, auth()->user());
                    }
                }

                if ($batch->isDirty() || $assignmentsDirty) {

                    if ($batch->isDirty()) {
                        $batch->save();
                    }

                    LogIt::set_edit_batches($implementation, $batch, auth()->user());
                    $this->dispatch('alertNotification', type: 'batch-modify', message: 'Successfully modified a batch', color: 'indigo');
                }

            } catch (AuthorizationException $e) {
                DB::rollBack();
                LogIt::set_log_exception('An unauthorized action has been made while deleting a batch. Error ' . $e->getCode(), auth()->user(), $e->getTrace());
                $this->dispatch('alertNotification', type: 'batch-modify', message: $e->getMessage(), color: 'red');
            } catch (\Throwable $e) {
                DB::rollBack();
                LogIt::set_log_exception('An error has occured during execution. Error ' . $e->getCode(), auth()->user(), $e->getTrace());
                $this->dispatch('alertNotification', type: 'batch-modify', message: 'An error has occured during execution. Error ' . $e->getCode(), color: 'red');
            } finally {
                $this->toggleEditBatch();
            }
        });
    }

    # Deletes the batch as long as there's empty beneficiaries on it
    public function deleteBatch()
    {
        DB::transaction(function () {
            try {
                $batch = Batch::with([
                    'implementation' => function ($query) {
                        $query->lockForUpdate();
                    }
                ])->findOrFail(decrypt($this->batchId));
                $implementation = $batch->implementation;
                if (!$this->isEmpty) {
                    DB::rollBack();
                    $this->dispatch('alertNotification', type: 'batch', message: 'This batch is not empty', color: 'red');
                    return;
                }
                $this->authorize('batch-focal', $batch);

                $assignments = Assignment::where('batches_id', decrypt($this->batchId))
                    ->lockForUpdate()
                    ->get();
                foreach ($assignments as $assignment) {
                    $assignment->deleteOrFail();
                }

                $codes = Code::where('batches_id', $batch->id)->get();
                if ($codes->isNotEmpty()) {
                    foreach ($codes as $code) {
                        $code->deleteOrFail();
                    }
                }

                $batch->deleteOrFail();

                LogIt::set_delete_batches($implementation, $batch, auth()->user());
                $this->dispatch('alertNotification', type: 'batch', message: 'Successfully deleted a batch', color: 'indigo');
            } catch (AuthorizationException $e) {
                DB::rollBack();
                LogIt::set_log_exception('An unauthorized action has been made while deleting a batch. Error ' . $e->getCode(), auth()->user(), $e->getTrace());
                $this->dispatch('alertNotification', type: 'batch', message: $e->getMessage(), color: 'red');
            } catch (\Throwable $e) {
                DB::rollBack();
                LogIt::set_log_exception('An error has occured during execution. Error ' . $e->getCode(), auth()->user(), $e->getTrace());
                $this->dispatch('alertNotification', type: 'batch', message: 'An error has occured during execution. Error ' . $e->getCode(), color: 'red');
            } finally {
                $this->resetViewBatch();
                $this->js('viewBatchModal = false;');
            }
        }, 5);
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
                ->whereNot('email_verified_at', null)
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
                ->whereNot('email_verified_at', null)
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
        $batch = Batch::find($this->batchId ? decrypt($this->batchId) : null);
        return Implementation::find($batch?->implementations_id);
    }

    #[Computed]
    public function batch()
    {
        return Batch::find($this->batchId ? decrypt($this->batchId) : null);
    }

    #[Computed]
    public function assignments()
    {
        return Assignment::where('batches_id', $this->batchId ? decrypt($this->batchId) : null)
            ->get();
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

    # Gets all the districts (unless it's a lone district) according to the choosen city/municipality by the user
    #[Computed]
    public function districts()
    {
        return Districts::getDistricts($this->implementation?->city_municipality, $this->implementation?->province);
    }

    # this function returns all of the barangays based on the project's location
    #[Computed]
    public function barangays()
    {
        # this returns an array
        $barangays = Barangays::getBarangays($this->implementation?->city_municipality, $this->district);

        # If searchBarangay is set, filter the barangays array
        if ($this->searchBarangay) {
            $barangays = array_values(array_filter($barangays, function ($barangay) {
                return stripos($barangay, $this->searchBarangay) !== false; # Case-insensitive search
            }));
        }

        return $barangays ?? [];
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

    public function resetBarangays()
    {
        $this->reset('barangay_name');
        $this->resetValidation('barangay_name');
    }

    public function resetViewBatch()
    {
        $this->resetExcept('batchId', 'batchNumPrefix');
        $this->resetValidation();
    }

    public function setCoordinator()
    {
        if ($this->coordinators->isEmpty()) {
            $this->selectedCoordinatorKey = -1;
            $this->currentCoordinator = '-';
        } else {
            $this->selectedCoordinatorKey = 0;
            $this->currentCoordinator = $this->getFullName($this->coordinators[$this->selectedCoordinatorKey]);
        }
    }

    #[Computed]
    public function settings()
    {
        return UserSetting::where('users_id', Auth::id())
            ->pluck('value', 'key');
    }

    public function mount()
    {
        $this->setCoordinator();
    }

    public function render()
    {
        $this->batchNumPrefix = $this->settings->get('batch_number_prefix', config('settings.batch_number_prefix'));
        if ($this->batch) {
            $this->liveUpdateRemainingSlots();
        }

        return view('livewire.focal.implementations.view-batch');
    }
}
