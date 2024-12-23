<?php

namespace App\Livewire\Focal\Implementations;

use App\Models\Assignment;
use App\Models\Batch;
use App\Models\Implementation;
use App\Models\User;
use App\Models\UserSetting;
use App\Services\Barangays;
use App\Services\Districts;
use App\Services\LogIt;
use Auth;
use Carbon\Carbon;
use DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Reactive;
use Livewire\Attributes\Validate;
use Livewire\Component;

class AssignBatchesModal extends Component
{
    #[Reactive]
    #[Locked]
    public $implementationId;
    public $batchNumPrefix;
    public $remainingSlots;
    public $totalSlots;
    public $ignoredCoordinatorIDs;
    public $selectedCoordinatorKey;
    public $currentCoordinator;
    public $searchCoordinator;
    public $searchBarangay;
    public $searchDistrict;
    public $batchesCount;
    public $selectedBatchListRow = -1;
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
    public $temporaryBatchesList = [];

    # Runs real-time depending on wire:model suffix
    public function rules()
    {
        return [
            # Assign Batches Modal
            'batch_num' => [
                'required',

                # Checks uniqueness from the `Database`
                function ($attribute, $value, $fail) {

                    # Check for uniqueness of the prefixed value in the database
                    $exists = DB::table('batches')
                        ->where('batch_num', $this->batchNumPrefix . $value)
                        ->exists();

                    if ($exists) {
                        # Fail the validation if the project number with the prefix already exists
                        $fail('This batch number already exists.');
                    }
                },

                # Checks uniqueness from the Temporary Batch List
                function ($attribute, $value, $fail) {
                    foreach ($this->temporaryBatchesList as $batch) {
                        if ($batch['batch_num'] === $this->batchNumPrefix . $value) {
                            $fail('This batch number has already been added.');
                        }
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
                    $exists = Batch::where('implementations_id', $this->implementation?->id)
                        ->where('barangay_name', $value)
                        ->exists();

                    if ($exists) {
                        # Fail the validation if this barangay already existed 
                        $fail('This :attribute already existed on this project.');
                    }
                },

                # Checks uniqueness from the Temporary Batch List
                function ($attribute, $value, $fail) {
                    foreach ($this->temporaryBatchesList as $batch) {
                        if ($batch['barangay_name'] === $value) {
                            $fail('This :attribute has already been added.');
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
            # Assign Batches Modal
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
        ];
    }

    # Validation attribute names for human readability purpose
    public function validationAttributes()
    {
        return [
            # Assign Batches Modal
            'barangay_name' => 'barangay',
            'slots_allocated' => 'Slots',
            'assigned_coordinators' => 'assigned coordinator',
        ];
    }

    protected function generateBatchNum()
    {
        $code = null;
        do {
            $code = '';
            for ($a = 0; $a < 8; $a++) {
                $code .= fake()->randomElement(['#']);
            }

            $this->batch_num = fake()->bothify($code);

        } while (Batch::where('batch_num', $this->batchNumPrefix . $this->batch_num)->exists());

    }

    public function regenerateBatchNum()
    {
        $this->generateBatchNum();
    }

    # triggers when clicking the `ADD BATCH` button which adds the user input
    # to the temporary batch list table for saving later.
    public function addBatchRow()
    {
        $this->validate(
            [
                'batch_num' => [
                    'required',

                    # Checks uniqueness from the `Database`
                    function ($attribute, $value, $fail) {

                        # Check for uniqueness of the prefixed value in the database
                        $exists = DB::table('batches')
                            ->where('batch_num', $this->batchNumPrefix . $value)
                            ->exists();

                        if ($exists) {
                            # Fail the validation if the project number with the prefix already exists
                            $fail('This :attribute already exists.');
                        }
                    },

                    # Checks uniqueness from the Temporary Batch List
                    function ($attribute, $value, $fail) {
                        foreach ($this->temporaryBatchesList as $batch) {
                            if ($batch['batch_num'] === $this->batchNumPrefix . $value) {
                                $fail('This :attribute has already been added.');
                            }
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
                        $exists = Batch::where('implementations_id', $this->implementation?->id)
                            ->where('barangay_name', $value)
                            ->exists();

                        if ($exists) {
                            # Fail the validation if this barangay already existed 
                            $fail('This :attribute already existed on this project.');
                        }
                    },

                    # Checks uniqueness from the Temporary Batch List
                    function ($attribute, $value, $fail) {
                        foreach ($this->temporaryBatchesList as $batch) {
                            if ($batch['barangay_name'] === $value) {
                                $fail('This :attribute has already been added.');
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
            ],
            [
                'batch_num.required' => 'This field is required.',
                'is_sectoral.required' => 'Please select a type.',
                'sector_title.required_if' => 'This field is required.',
                'district.required_if' => 'This field is required.',
                'barangay_name.required_if' => 'This field is required.',
                'slots_allocated.required' => 'Invalid :attribute amount.',
                'assigned_coordinators.required' => 'There should be at least 1 :attribute.',

                'batch_num.integer' => 'The :attribute should be a valid number.',
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
                'batch_num' => 'batch number',
                'barangay_name' => 'barangay',
                'slots_allocated' => 'Slots',
                'assigned_coordinators' => 'assigned coordinator',
            ]
        );

        $this->batch_num = $this->batchNumPrefix . now()->format('Y-') . $this->batch_num;

        if ($this->is_sectoral) {
            $this->temporaryBatchesList[] = [
                'batch_num' => $this->batch_num,
                'is_sectoral' => $this->is_sectoral,
                'sector_title' => $this->sector_title,
                'district' => null,
                'barangay_name' => null,
                'slots_allocated' => $this->slots_allocated,
                'assigned_coordinators' => $this->assigned_coordinators,
            ];
        } else {
            $this->temporaryBatchesList[] = [
                'batch_num' => $this->batch_num,
                'is_sectoral' => $this->is_sectoral,
                'sector_title' => null,
                'district' => $this->district,
                'barangay_name' => $this->barangay_name,
                'slots_allocated' => $this->slots_allocated,
                'assigned_coordinators' => $this->assigned_coordinators,
            ];
        }

        $this->totalSlots -= $this->slots_allocated;
        $this->reset(
            'is_sectoral',
            'sector_title',
            'district',
            'barangay_name',
            'slots_allocated',
            'assigned_coordinators',
            'ignoredCoordinatorIDs',
            'searchBarangay',
        );

        $this->validateOnly('temporaryBatchesList');

        $this->generateBatchNum();

        unset($this->coordinators);
        $this->setCoordinator();

    }

    # triggers when clicking the `X` button which removes the
    # batch row from the list and temporary batch array.
    public function removeBatchRow($key)
    {
        $this->totalSlots += $this->temporaryBatchesList[$key]['slots_allocated'];
        // $this->liveUpdateRemainingSlots();

        if ($this->selectedBatchListRow === $key) {
            # resets the rows styling/emphasis
            $this->selectedBatchListRow = -1;
        } else if ($key > $this->selectedBatchListRow) {
            $this->selectedBatchListRow -= 1;
        }

        unset($this->temporaryBatchesList[$key]);
    }

    # a livewire action executes after clicking the `Finish` button
    public function saveBatches()
    {
        $this->validate(['temporaryBatchesList' => 'required'], ['temporaryBatchesList.required' => 'There should be at least 1 :attribute before finishing.',], ['temporaryBatchesList' => 'batch assignment',]);

        DB::transaction(function () {

            try {
                foreach ($this->temporaryBatchesList as $keyBatch => $batch) {
                    $implementation = Implementation::lockForUpdate()->find($this->implementationId ? decrypt($this->implementationId) : null);

                    if (!$implementation) {
                        DB::rollBack();
                        $this->dispatch('alertNotification', type: 'implementation', message: 'The project does not exist', color: 'red');
                        return;
                    }

                    $batch = Batch::create([
                        'implementations_id' => $implementation->id,
                        'batch_num' => $batch['batch_num'],
                        'is_sectoral' => $batch['is_sectoral'],
                        'sector_title' => $batch['sector_title'],
                        'district' => $batch['district'],
                        'barangay_name' => $batch['barangay_name'],
                        'slots_allocated' => $batch['slots_allocated'],
                        'approval_status' => 'pending',
                        'submission_status' => 'unopened'
                    ]);

                    $batch_id = $batch->id;
                    LogIt::set_create_batches($implementation, $batch, auth()->user());

                    foreach ($this->temporaryBatchesList[$keyBatch]['assigned_coordinators'] as $coordinator) {
                        $assignment = Assignment::create([
                            'batches_id' => $batch_id,
                            'users_id' => decrypt($coordinator['users_id']),
                        ]);
                        LogIt::set_assign_coordinator_to_batch($batch, $assignment, auth()->user());
                    }
                }

                $this->dispatch('alertNotification', type: 'batch', message: 'Successfully assigned a batch', color: 'indigo');

            } catch (\Throwable $e) {
                DB::rollBack();
                LogIt::set_log_exception('An error has occured during execution. Error ' . $e->getCode(), auth()->user(), $e->getTrace());
                $this->dispatch('alertNotification', type: 'batch', message: 'An error has occured during execution. Error ' . $e->getCode(), color: 'red');
            } finally {
                $this->resetBatches();
                $this->js('assignBatchesModal = false;');
            }
        }, 5);
    }

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
        if (!$this->temporaryBatchesList) {

            $this->totalSlots = $this->implementation?->total_slots;

            # retrieves all of the `slots_alloted` values from the batches table
            $this->batchesCount = Batch::join('implementations', 'implementations.id', '=', 'batches.implementations_id')
                ->where('implementations.users_id', Auth::id())
                ->where('implementations.id', decrypt($this->implementationId))
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

        } else {

            # re-assigns the remaining slots to the total slots that was
            # also re-assigned during addition of the new batch to the temporary list.
            $this->remainingSlots = $this->totalSlots;

        }

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
                ->where('regional_office', auth()->user()->regional_office)
                ->where('field_office', auth()->user()->field_office)
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
                ->where('regional_office', auth()->user()->regional_office)
                ->where('field_office', auth()->user()->field_office)
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
        if ($this->implementationId) {
            $implementation = Implementation::find(decrypt($this->implementationId));
            return $implementation;
        }
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

    # It's a Livewire `Hook` for properties so the system can take action
    # when a specific property has updated its state. 
    public function updated($prop)
    {
        if ($prop === 'district') {
            $this->reset('barangay_name', 'searchBarangay');
            $this->resetValidation('barangay_name');
        }

        if ($prop === 'barangay_name') {
            $this->reset('searchBarangay');
        }

        if ($prop === 'is_sectoral') {
            $this->reset('sector_title', 'district', 'barangay_name');
        }
    }

    public function resetBatches()
    {
        $this->reset(
            'searchBarangay',
            'temporaryBatchesList',
            'selectedBatchListRow',
            'ignoredCoordinatorIDs',
            'batch_num',
            'district',
            'barangay_name',
            'slots_allocated',
            'assigned_coordinators',
            'remainingSlots',
            'totalSlots',
        );

        $this->generateBatchNum();
        $this->resetValidation();
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
        $this->generateBatchNum();
        $this->barangay_name = null;
        $this->district = null;
    }

    public function render()
    {
        $this->batchNumPrefix = $this->settings->get('batch_number_prefix', config('settings.batch_number_prefix'));

        # Assign Batches Modal
        if ($this->implementationId) {
            $this->liveUpdateRemainingSlots();
        }

        return view('livewire.focal.implementations.assign-batches-modal');
    }
}
