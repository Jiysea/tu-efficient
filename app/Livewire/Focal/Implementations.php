<?php

namespace App\Livewire\Focal;

use App\Livewire\Coordinator\Assignments;
use App\Models\Assignment;
use App\Models\Batch;
use App\Models\Beneficiary;
use App\Models\Implementation;
use App\Models\User;
use App\Models\UserSetting;
use App\Services\Barangays;
use App\Services\CitiesMunicipalities;
use App\Services\Districts;
use App\Services\MoneyFormat;
use App\Services\Provinces;
use Crypt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Implementations | TU-Efficient')]
class Implementations extends Component
{
    #[Locked]
    public $implementationId;
    #[Locked]
    public $batchId;
    #[Locked]
    public $beneficiaryId;

    # ------------------------------------------

    #[Locked]
    public $passedProjectId;
    public $createProjectModal = false;
    public $viewProjectModal = false;
    #[Locked]
    public $passedBatchId;
    public $viewBatchModal = false;
    public $assignBatchesModal = false;
    #[Locked]
    public $passedId;

    # ------------------------------------------

    public $temporaryCount = 0; # debugging purposes
    public $searchProjects;
    public $searchBeneficiaries;
    public $showAlert = false;
    public $alertMessage = '';
    public $totalImplementations;
    public $implementations_on_page = 15;
    public $beneficiaries_on_page = 15;
    public $selectedImplementationRow = -1;
    public $selectedBatchRow = -1;
    public $selectedBeneficiaryRow = -1;
    public $remainingBatchSlots;
    public $beneficiarySlots = [];
    public $start;
    public $end;
    public $defaultStart;
    public $defaultEnd;

    # ------------------------------------------

    # Runs real-time depending on wire:model suffix
    public function rules()
    {
        return [
            # Create Project Modal
            'project_num' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    # Check for uniqueness of the prefixed value in the database
                    $exists = DB::table('implementations')
                        ->where('project_num', $this->projectNumPrefix . $value)
                        ->exists();

                    if ($exists) {
                        # Fail the validation if the project number with the prefix already exists
                        $fail('This :attribute already exists.');
                    }
                },
            ],
            'project_title' => 'nullable',
            'purpose' => 'required',
            'district' => 'required',
            'province' => 'required',
            'city_municipality' => 'required',
            'budget_amount' => [
                'required',
                # Checks if the number is a valid number
                function ($attribute, $value, $fail) {
                    $money = new MoneyFormat();
                    // dump($value);
                    $number = $money->isMaskInt($value);

                    if (!$number) {

                        $fail('The :attribute should be a valid amount.');
                    }
                },
                # Checks if the number is less than 1
                function ($attribute, $value, $fail) {
                    $money = new MoneyFormat();
                    $negative = $money->isNegative($value);

                    if ($negative) {
                        $fail('The :attribute value should be more than 1.');
                    }
                },
            ],
            'total_slots' => 'required|integer|min:1',
            'days_of_work' => 'required|integer|min:1',

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
            'barangay_name' => [
                'required',

                # Checks uniqueness from the `Database`
                function ($attribute, $value, $fail) {

                    # Check for uniqueness of the prefixed value in the database
                    $exists = Batch::where('implementations_id', $this->implementation->id)
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
        ];
    }

    # The validation error messages
    public function messages()
    {
        return [
            # Create Project Modal
            'project_num.required' => 'The :attribute should not be empty.',
            'purpose.required' => 'Please select a :attribute .',
            'district.required' => 'The :attribute should not be empty.',
            'province.required' => 'The :attribute should not be empty.',
            'city_municipality.required' => 'The :attribute should not be empty.',
            'budget_amount.required' => 'The :attribute should not be empty.',
            'total_slots.required' => 'The :attribute should not be empty.',
            'days_of_work.required' => 'Invalid :attribute.',
            'project_num.integer' => 'The :attribute should be a valid number.',
            'total_slots.integer' => 'The :attribute should be a valid number.',
            'total_slots.min' => 'The :attribute value should be more than 1.',
            'days_of_work.integer' => 'The :attribute should be a valid number.',
            'days_of_work.min' => 'The :attribute value should be more than 1.',

            # Assign Batches Modal
            'batch_num.required' => 'This field is required.',
            'barangay_name.required' => 'This field is required.',
            'slots_allocated.required' => 'Invalid :attribute amount.',
            'assigned_coordinators.required' => 'There should be at least 1 :attribute.',
            'batch_num.integer' => 'The :attribute should be a valid number.',
            'slots_allocated.integer' => ':attribute should be a valid number.',
            'slots_allocated.min' => ':attribute should be > 0.',
            'slots_allocated.gte' => ':attribute should be nonnegative.',
            'slots_allocated.lte' => ':attribute should be less than total.',

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
        ];
    }

    # Validation attribute names for human readability purpose
    public function validationAttributes()
    {
        return [
            # Create Project Modal
            'project_num' => 'project number',
            'purpose' => 'purpose',
            'district' => 'district',
            'province' => 'province',
            'city_municipality' => 'city or municipality',
            'budget_amount' => 'budget',
            'total_slots' => 'slots',
            'days_of_work' => 'days of work',

            # Assign Batches Modal
            'batch_num' => 'batch number',
            'barangay_name' => 'barangay',
            'slots_allocated' => 'Slots',
            'assigned_coordinators' => 'assigned coordinator',

            # View Batch Modal
            'view_batch_num' => 'batch number',
            'view_barangay_name' => 'barangay',
            'view_slots_allocated' => 'Slots',
            'view_assigned_coordinators' => 'assigned coordinator',
        ];
    }

    #[On('start-change')]
    public function setStartDate($value)
    {
        $choosenDate = date('Y-m-d', strtotime($value));
        $currentTime = date('H:i:s', strtotime(now()));

        $this->start = $choosenDate . ' ' . $currentTime;
        $this->implementations_on_page = 15;
        $this->beneficiaries_on_page = 15;

        $this->passedProjectId = null;
        $this->passedBatchId = null;
        $this->passedId = null;
        $this->implementationId = null;
        $this->batchId = null;

        $this->selectedImplementationRow = -1;
        $this->selectedBatchRow = -1;
        $this->selectedBeneficiaryRow = -1;

        $this->dispatch('init-reload')->self();
    }

    #[On('end-change')]
    public function setEndDate($value)
    {
        $choosenDate = date('Y-m-d', strtotime($value));
        $currentTime = date('H:i:s', strtotime(now()));

        $this->end = $choosenDate . ' ' . $currentTime;
        $this->implementations_on_page = 15;
        $this->beneficiaries_on_page = 15;

        $this->passedProjectId = null;
        $this->passedBatchId = null;
        $this->passedId = null;
        $this->implementationId = null;
        $this->batchId = null;

        $this->selectedImplementationRow = -1;
        $this->selectedBatchRow = -1;
        $this->selectedBeneficiaryRow = -1;

        $this->dispatch('init-reload')->self();
    }

    public function viewProject(string $implementationId)
    {
        $this->passedProjectId = $implementationId;
        $this->viewProjectModal = true;
    }

    public function viewBatch(string $batchId)
    {
        $this->passedBatchId = $batchId;
        $this->viewBatchModal = true;
    }

    public function selectImplementationRow($key, $encryptedId)
    {
        if ($key === $this->selectedImplementationRow) {
            $this->selectedImplementationRow = -1;
            $this->implementationId = null;
        } else {
            $this->selectedImplementationRow = $key;
            $this->implementationId = $encryptedId;
        }

        $this->beneficiaries_on_page = 15;
        $this->batchId = null;
        $this->selectedBatchRow = -1;
        $this->selectedBeneficiaryRow = -1;

        $this->dispatch('init-reload')->self();
    }

    public function selectBatchRow($key, $encryptedId)
    {
        if ($key === $this->selectedBatchRow) {
            $this->selectedBatchRow = -1;
            $this->batchId = null;
        } else {
            $this->selectedBatchRow = $key;
            $this->batchId = $encryptedId;
        }

        $this->beneficiaries_on_page = 15;
        $this->beneficiaryId = null;
        $this->selectedBeneficiaryRow = -1;

        $this->dispatch('init-reload')->self();
    }

    public function selectBeneficiaryRow($key, $encryptedId)
    {
        $this->selectedBeneficiaryRow = $key;
        $this->beneficiaryId = Crypt::decrypt($encryptedId);
    }

    #[Computed]
    public function implementations()
    {
        $focalUserId = auth()->id();
        $projectNumPrefix = config('settings.project_number_prefix', 'XII-DCFO-');

        $implementations = Implementation::where('users_id', $focalUserId)
            ->whereBetween('created_at', [$this->start, $this->end])
            ->where('project_num', 'LIKE', $projectNumPrefix . '%' . $this->searchProjects . '%')
            ->latest('updated_at')
            ->take($this->implementations_on_page)
            ->get();

        return $implementations;
    }

    #[Computed]
    public function batches()
    {
        if ($this->implementationId) {
            $batches = Implementation::where('implementations.users_id', Auth::id())
                ->join('batches', 'implementations.id', '=', 'batches.implementations_id')
                ->leftJoin('beneficiaries', 'batches.id', '=', 'beneficiaries.batches_id')
                ->where('implementations.id', decrypt($this->implementationId))
                ->select([
                    'batches.id',
                    'batches.barangay_name',
                    'batches.slots_allocated',
                    DB::raw('COUNT(DISTINCT beneficiaries.id) AS current_slots'),
                    DB::raw('batches.approval_status AS approval_status')
                ])
                ->groupBy('batches.id', 'barangay_name', 'slots_allocated', 'approval_status')
                ->orderBy('batches.id', 'desc')
                ->get();

            return $batches;
        }
    }

    #[Computed]
    public function beneficiaries()
    {
        if ($this->batchId) {
            $beneficiaries = Implementation::where('implementations.users_id', Auth::id())
                ->join('batches', 'implementations.id', '=', 'batches.implementations_id')
                ->join('beneficiaries', 'batches.id', '=', 'beneficiaries.batches_id')
                ->where('batches.id', decrypt($this->batchId))
                ->when($this->searchBeneficiaries, function ($q) {
                    # Check if the search field starts with '#' and filter by contact number
                    if (str_contains($this->searchBeneficiaries, '#')) {
                        $searchValue = trim(str_replace('#', '', $this->searchBeneficiaries));

                        if (strpos($searchValue, '0') === 0) {
                            $searchValue = substr($searchValue, 1);
                        }
                        $q->where('beneficiaries.contact_num', 'LIKE', '%' . $searchValue . '%');
                    } else {
                        # Otherwise, search by first, middle, or last name
                        $q->where(function ($query) {
                            $query->where('beneficiaries.first_name', 'LIKE', '%' . $this->searchBeneficiaries . '%')
                                ->orWhere('beneficiaries.middle_name', 'LIKE', '%' . $this->searchBeneficiaries . '%')
                                ->orWhere('beneficiaries.last_name', 'LIKE', '%' . $this->searchBeneficiaries . '%');
                        });
                    }
                })
                ->select(
                    DB::raw('beneficiaries.*'),
                )
                ->take($this->beneficiaries_on_page)
                ->get();

            return $beneficiaries;
        }
    }

    public function checkImplementationTotalSlots()
    {
        $focalUserId = auth()->id();

        $this->totalImplementations = Implementation::where('users_id', $focalUserId)
            ->whereBetween('created_at', [$this->start, $this->end])
            ->count();
    }

    public function checkBatchRemainingSlots()
    {
        if ($this->implementationId) {

            $this->remainingBatchSlots = $this->implementation->total_slots;

            $batchesCount = Batch::join('implementations', 'implementations.id', '=', 'batches.implementations_id')
                ->where('implementations.users_id', Auth::id())
                ->where('implementations.id', decrypt($this->implementationId))
                ->select('batches.slots_allocated')
                ->orderBy('batches.id', 'desc')
                ->get();

            foreach ($batchesCount as $batch) {
                $this->remainingBatchSlots -= $batch->slots_allocated;
            }
        } else {
            $this->remainingBatchSlots = null;
        }
    }

    public function checkBeneficiarySlots()
    {
        if ($this->batchId) {

            $batch = Batch::where('id', decrypt($this->batchId))
                ->first();

            $this->beneficiarySlots = $batch->slots_allocated;

            $beneficiaryCount = Beneficiary::where('batches_id', decrypt($this->batchId))
                ->count();

            $this->beneficiarySlots = [
                'batch_slots_allocated' => $batch->slots_allocated,
                'num_of_beneficiaries' => $beneficiaryCount
            ];

        } else {
            $this->beneficiarySlots = [
                'batch_slots_allocated' => null,
                'num_of_beneficiaries' => null
            ];
        }
    }

    public function loadMoreImplementations()
    {
        $this->implementations_on_page += 15;
        $this->dispatch('init-reload')->self();
    }

    public function loadMoreBeneficiaries()
    {
        $this->beneficiaries_on_page += 15;
        $this->dispatch('init-reload')->self();
    }

    #[On('edit-implementations')]
    public function editImplementation()
    {
        $dateTimeFromEnd = $this->end;
        $value = substr($dateTimeFromEnd, 0, 10);

        $choosenDate = date('Y-m-d', strtotime($value));
        $currentTime = date('H:i:s', strtotime(now()));
        $this->end = $choosenDate . ' ' . $currentTime;

        unset($this->implementation);

        $this->showAlert = true;
        $this->alertMessage = 'Saved changes!';
        $this->dispatch('show-alert');
        $this->dispatch('init-reload')->self();
    }

    #[On('delete-implementations')]
    public function deleteImplementation()
    {
        $dateTimeFromEnd = $this->end;
        $value = substr($dateTimeFromEnd, 0, 10);

        $choosenDate = date('Y-m-d', strtotime($value));
        $currentTime = date('H:i:s', strtotime(now()));
        $this->end = $choosenDate . ' ' . $currentTime;

        $this->implementationId = null;
        $this->batchId = null;
        $this->beneficiaryId = null;

        $this->selectedImplementationRow = -1;
        $this->selectedBatchRow = -1;
        $this->selectedBeneficiaryRow = -1;

        $this->showAlert = true;
        $this->alertMessage = 'Successfully deleted the project!';
        $this->dispatch('show-alert');
        $this->dispatch('init-reload')->self();
    }

    #[On('add-beneficiaries')]
    public function addBeneficiaries()
    {
        $dateTimeFromEnd = $this->end;
        $value = substr($dateTimeFromEnd, 0, 10);

        $choosenDate = date('Y-m-d', strtotime($value));
        $currentTime = date('H:i:s', strtotime(now()));
        $this->end = $choosenDate . ' ' . $currentTime;

        $this->beneficiaryId = null;

        $this->selectedBeneficiaryRow = -1;

        $this->showAlert = true;
        $this->alertMessage = 'Beneficiary added successfully!';
        $this->dispatch('show-alert');
        $this->dispatch('init-reload')->self();
    }

    # CREATE PROJECT && VIEW PROJECT MODAL -------------------------------------

    public $minimumWage;
    public $projectNumPrefix;
    public $isAutoComputeEnabled = false;
    #[Validate]
    public $project_num;
    #[Validate]
    public $project_title;
    #[Validate]
    public $purpose;
    #[Validate]
    public $province;
    #[Validate]
    public $city_municipality;
    #[Validate]
    public $district;
    #[Validate]
    public $budget_amount;
    #[Validate]
    public $total_slots;
    #[Validate]
    public $days_of_work;

    # a livewire action executes after clicking the `Create Project` button
    public function saveProject()
    {
        $this->validate(
            [
                'project_num' => [
                    'required',
                    'integer',
                    function ($attribute, $value, $fail) {
                        # Check for uniqueness of the prefixed value in the database
                        $exists = DB::table('implementations')
                            ->where('project_num', $this->projectNumPrefix . $value)
                            ->exists();

                        if ($exists) {
                            # Fail the validation if the project number with the prefix already exists
                            $fail('This :attribute already exists.');
                        }
                    },
                ],
                'project_title' => 'nullable',
                'purpose' => 'required',
                'district' => 'required',
                'province' => 'required',
                'city_municipality' => 'required',
                'budget_amount' => [
                    'required',
                    # Checks if the number is a valid number
                    function ($attribute, $value, $fail) {
                        $money = new MoneyFormat();
                        // dump($value);
                        $number = $money->isMaskInt($value);

                        if (!$number) {

                            $fail('The :attribute should be a valid amount.');
                        }
                    },
                    # Checks if the number is less than 1
                    function ($attribute, $value, $fail) {
                        $money = new MoneyFormat();
                        $negative = $money->isNegative($value);

                        if ($negative) {
                            $fail('The :attribute value should be more than 1.');
                        }
                    },
                ],
                'total_slots' => 'required|integer|min:1',
                'days_of_work' => 'required|integer|min:1',
            ],
            [
                'project_num.required' => 'The :attribute should not be empty.',
                'purpose.required' => 'Please select a :attribute .',
                'district.required' => 'The :attribute should not be empty.',
                'province.required' => 'The :attribute should not be empty.',
                'city_municipality.required' => 'The :attribute should not be empty.',
                'budget_amount.required' => 'The :attribute should not be empty.',
                'total_slots.required' => 'The :attribute should not be empty.',
                'days_of_work.required' => 'Invalid :attribute.',
                'project_num.integer' => 'The :attribute should be a valid number.',
                'total_slots.integer' => 'The :attribute should be a valid number.',
                'total_slots.min' => 'The :attribute value should be more than 1.',
                'days_of_work.integer' => 'The :attribute should be a valid number.',
                'days_of_work.min' => 'The :attribute value should be more than 1.',
            ],
            [
                'project_num' => 'project number',
                'purpose' => 'purpose',
                'district' => 'district',
                'province' => 'province',
                'city_municipality' => 'city or municipality',
                'budget_amount' => 'budget',
                'total_slots' => 'slots',
                'days_of_work' => 'days of work',
            ]
        );

        $this->project_num = $this->projectNumPrefix . $this->project_num;
        $money = new MoneyFormat();
        $this->budget_amount = $money->unmask($this->budget_amount);

        Implementation::create([
            'users_id' => Auth()->id(),
            'project_num' => $this->project_num,
            'project_title' => $this->project_title,
            'purpose' => $this->purpose,
            'district' => $this->district,
            'province' => $this->province,
            'city_municipality' => $this->city_municipality,
            'budget_amount' => $this->budget_amount,
            'total_slots' => $this->total_slots,
            'days_of_work' => $this->days_of_work
        ]);

        $this->resetProject();

        $dateTimeFromEnd = $this->end;
        $value = substr($dateTimeFromEnd, 0, 10);

        $choosenDate = date('Y-m-d', strtotime($value));
        $currentTime = date('H:i:s', strtotime(now()));
        $this->end = $choosenDate . ' ' . $currentTime;

        $this->implementationId = null;
        $this->batchId = null;

        $this->selectedImplementationRow = -1;
        $this->selectedBatchRow = -1;
        $this->selectedBeneficiaryRow = -1;

        $this->showAlert = true;
        $this->alertMessage = 'Project implementation successfully created!';
        $this->dispatch('show-alert');
        $this->dispatch('init-reload')->self();
        $this->createProjectModal = false;
    }

    # a livewire action for toggling the auto computation for total slots
    public function autoCompute()
    {
        # checks if the toggle (checkbox) is on/true OR off/false
        if ($this->isAutoComputeEnabled) {

            # The minimum wage value is a global variable located in the .env file
            # So if you want to change it, change it there manually
            # Also logically, real-life money has only 2 digits below a ph peso
            # So it doesn't matter how many decimal digits it has, it will
            # always be formatted to 2 simple digits (rounded off)

            $money = new MoneyFormat();
            $tempBudget = $money->unmask($this->budget_amount ?? '0.00');

            ($this->days_of_work === null || intval($this->days_of_work) === 0) ? $this->days_of_work = 1 : $this->days_of_work;
            $this->total_slots = intval($tempBudget / ($this->minimumWage * $this->days_of_work));

            $this->validateOnly('total_slots');
            $this->validateOnly('days_of_work');
        }
    }

    # Gets all the provinces according to the authenticated user's (focal) regional office
    #[Computed]
    public function provinces()
    {
        $p = new Provinces();
        return $p->getProvinces(Auth::user()->regional_office);
    }

    # Gets all the cities/municipalities according to the choosen province by the user
    #[Computed]
    public function cities_municipalities()
    {
        $c = new CitiesMunicipalities();
        return $c->getCitiesMunicipalities($this->province);
    }

    # Gets all the districts (unless it's a lone district) according to the choosen city/municipality by the user
    #[Computed]
    public function districts()
    {
        $d = new Districts();
        return $d->getDistricts($this->city_municipality, $this->province);
    }

    public function updatedProvince()
    {
        $this->city_municipality = $this->cities_municipalities[0];
        $this->district = $this->districts[0];
    }

    public function updatedCityMunicipality()
    {
        $this->district = $this->districts[0];
    }

    public function resetProject()
    {
        $this->reset(
            'project_num',
            'project_title',
            'purpose',
            'budget_amount',
            'total_slots',
            'days_of_work',
            'isAutoComputeEnabled',
        );

        $this->resetValidation();
        $this->province = $this->provinces[0];
        $this->city_municipality = $this->cities_municipalities[0];
        $this->district = $this->districts[0];
    }


    # CREATE PROJECT && VIEW PROJECT MODAL -------------------------------------
    # ASSIGN BATCHES && VIEW BATCH MODAL ---------------------------------------

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
    public $batch_num;
    #[Validate]
    public $barangay_name;
    #[Validate]
    public $slots_allocated;
    #[Validate]
    public $assigned_coordinators = [];
    public $temporaryBatchesList = [];
    #[Validate]
    public $view_batch_num;
    #[Validate]
    public $view_barangay_name;
    #[Validate]
    public $view_slots_allocated;
    public $view_assigned_coordinators = [];

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
                'barangay_name' => [
                    'required',

                    # Checks uniqueness from the `Database`
                    function ($attribute, $value, $fail) {

                        # Check for uniqueness of the prefixed value in the database
                        $exists = Batch::where('implementations_id', $this->implementation->id)
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
                'barangay_name.required' => 'This field is required.',
                'slots_allocated.required' => 'Invalid :attribute amount.',
                'assigned_coordinators.required' => 'There should be at least 1 :attribute.',

                'batch_num.integer' => 'The :attribute should be a valid number.',

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

        $this->batch_num = $this->batchNumPrefix . $this->batch_num;

        $this->temporaryBatchesList[] = [
            'batch_num' => $this->batch_num,
            'barangay_name' => $this->barangay_name,
            'slots_allocated' => $this->slots_allocated,
            'assigned_coordinators' => $this->assigned_coordinators,
        ];

        $this->totalSlots -= $this->slots_allocated;
        $this->reset(
            'batch_num',
            'barangay_name',
            'slots_allocated',
            'assigned_coordinators',
        );

        $this->validateOnly('temporaryBatchesList');
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

    # this function adds the selected coordinator from the `Add Coordinator` dropdown
    # and append it to the `Assigned Coordinators` as a toast-like element.
    # It would also remove the added coordinator from the `Add Coordinator` dropdown list
    # so as to avoid duplicating/conflicting names.
    public function addToastCoordinator()
    {
        if ($this->coordinators->isNotEmpty()) {

            if ($this->assignBatchesModal) {

                # appends the coordinator to the `Assigned Coordinators` box
                $this->assigned_coordinators[] = [
                    'users_id' => encrypt($this->coordinators[$this->selectedCoordinatorKey]->id),
                    'first_name' => $this->coordinators[$this->selectedCoordinatorKey]->first_name,
                    'middle_name' => $this->coordinators[$this->selectedCoordinatorKey]->middle_name,
                    'last_name' => $this->coordinators[$this->selectedCoordinatorKey]->last_name,
                    'extension_name' => $this->coordinators[$this->selectedCoordinatorKey]->extension_name,
                ];

                $this->validateOnly('assigned_coordinators');

            } elseif ($this->viewBatchModal) {

                # appends the coordinator to the `Assigned Coordinators` box
                $this->view_assigned_coordinators[] = [
                    'users_id' => encrypt($this->coordinators[$this->selectedCoordinatorKey]->id),
                    'first_name' => $this->coordinators[$this->selectedCoordinatorKey]->first_name,
                    'middle_name' => $this->coordinators[$this->selectedCoordinatorKey]->middle_name,
                    'last_name' => $this->coordinators[$this->selectedCoordinatorKey]->last_name,
                    'extension_name' => $this->coordinators[$this->selectedCoordinatorKey]->extension_name,
                ];

                $this->validateOnly('view_assigned_coordinators');

            }

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
        if ($this->assignBatchesModal) {
            unset($this->assigned_coordinators[$key]);
        } elseif ($this->viewBatchModal) {
            unset($this->view_assigned_coordinators[$key]);
        }

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
        if (!$this->temporaryBatchesList || $this->viewBatchModal) {

            $this->totalSlots = $this->implementation->total_slots;

            # retrieves all of the `slots_alloted` values from the batches table
            if ($this->assignBatchesModal) {

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

            } elseif ($this->viewBatchModal) {

                $this->batchesCount = Batch::join('implementations', 'implementations.id', '=', 'batches.implementations_id')
                    ->where('implementations.users_id', Auth::id())
                    ->where('implementations.id', decrypt($this->implementationId))
                    ->whereNotIn('batches.id', [decrypt($this->passedBatchId)])
                    ->select('batches.slots_allocated')
                    ->get();

                # retrieves all the slots allocated from existing (if any) batches
                # and iterate it as a single value
                foreach ($this->batchesCount as $batch) {
                    $batchCountDelta += $batch['slots_allocated'];
                }

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
        if (ctype_digit((string) $this->slots_allocated) || ctype_digit((string) $this->view_slots_allocated)) {

            # assign the difference between the remaining slots and the input slots value
            $newRemainingSlots = 0;
            if ($this->assignBatchesModal) {
                $newRemainingSlots = intval($this->remainingSlots) - intval($this->slots_allocated);
            } elseif ($this->viewBatchModal) {
                $newRemainingSlots = intval($this->remainingSlots) - intval($this->view_slots_allocated);
            }

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

    # a livewire action executes after clicking the `Finish` button
    public function saveBatches()
    {
        $this->validate(['temporaryBatchesList' => 'required'], ['temporaryBatchesList.required' => 'There should be at least 1 :attribute before finishing.',], ['temporaryBatchesList' => 'batch assignment',]);

        foreach ($this->temporaryBatchesList as $keyBatch => $batch) {
            $batch = Batch::create([
                'implementations_id' => decrypt($this->implementationId),
                'batch_num' => $batch['batch_num'],
                'barangay_name' => $batch['barangay_name'],
                'slots_allocated' => $batch['slots_allocated'],
                'approval_status' => 'pending',
                'submission_status' => 'unopened'
            ]);

            $batch_id = $batch->id;

            foreach ($this->temporaryBatchesList[$keyBatch]['assigned_coordinators'] as $coordinator) {
                Assignment::create([
                    'batches_id' => $batch_id,
                    'users_id' => decrypt($coordinator['users_id']),
                ]);
            }
        }

        # resets after submitting
        $this->reset(
            'batch_num',
            'barangay_name',
            'slots_allocated',
            'assigned_coordinators',
            'temporaryBatchesList',
        );

        $dateTimeFromEnd = $this->end;
        $value = substr($dateTimeFromEnd, 0, 10);

        $choosenDate = date('Y-m-d', strtotime($value));
        $currentTime = date('H:i:s', strtotime(now()));
        $this->end = $choosenDate . ' ' . $currentTime;

        $this->batchId = null;
        $this->beneficiaryId = null;

        $this->selectedBatchRow = -1;
        $this->selectedBeneficiaryRow = -1;

        $this->showAlert = true;
        $this->alertMessage = 'Batches successfully assigned!';
        $this->dispatch('show-alert');
        $this->dispatch('init-reload')->self();
        $this->assignBatchesModal = false;
    }

    # Deletes the batch as long as there's empty beneficiaries on it
    public function deleteBatch()
    {
        $assignments = Assignment::where('batches_id', decrypt($this->passedBatchId))
            ->get();
        $batch = Batch::find(decrypt($this->passedBatchId));

        $this->authorize('delete-batch', [$this->implementation, $batch]);

        foreach ($assignments as $assignment) {
            $assignment->delete();
        }

        $batch->delete();

        $this->editMode = false;
        $this->resetViewBatch();

        $dateTimeFromEnd = $this->end;
        $value = substr($dateTimeFromEnd, 0, 10);

        $choosenDate = date('Y-m-d', strtotime($value));
        $currentTime = date('H:i:s', strtotime(now()));
        $this->end = $choosenDate . ' ' . $currentTime;

        $this->batchId = null;
        $this->beneficiaryId = null;

        $this->selectedBatchRow = -1;
        $this->selectedBeneficiaryRow = -1;

        $this->showAlert = true;
        $this->alertMessage = 'Successfully removed the batch!';
        $this->dispatch('show-alert');
        $this->dispatch('init-reload')->self();

        $this->deleteBatchModal = false;
        $this->viewBatchModal = false;
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

            dd($this->view_assigned_coordinators);

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

        $dateTimeFromEnd = $this->end;
        $value = substr($dateTimeFromEnd, 0, 10);

        $choosenDate = date('Y-m-d', strtotime($value));
        $currentTime = date('H:i:s', strtotime(now()));
        $this->end = $choosenDate . ' ' . $currentTime;

        $this->showAlert = true;
        $this->alertMessage = 'Batch successfully updated!';
        $this->dispatch('show-alert');
        $this->dispatch('init-reload')->self();
        $this->toggleEdit();
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
        if ($this->implementationId) {
            $implementation = Implementation::find(decrypt($this->implementationId));
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

    public function resetBatches()
    {
        $this->reset(
            'searchBarangay',
            'temporaryBatchesList',
            'selectedBatchListRow',
            'ignoredCoordinatorIDs',
            'batch_num',
            'barangay_name',
            'slots_allocated',
            'assigned_coordinators',
            'remainingSlots',
            'totalSlots',
        );

        $this->resetValidation();
    }

    public function resetViewBatch()
    {
        if ($this->editMode) {
            $this->reset(
                'view_batch_num',
                'view_barangay_name',
                'view_slots_allocated',
                'view_assigned_coordinators',
                'editMode',
                'remainingSlots',
                'totalSlots',
            );
        }
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

    # ASSIGN BATCHES && VIEW BATCH MODAL ---------------------------------------

    public function mount()
    {
        if (Auth::user()->user_type !== 'focal') {
            $this->redirectIntended();
        }

        # setting up settings
        $settings = UserSetting::where('users_id', Auth::id())
            ->pluck('value', 'key');
        $this->batchNumPrefix = $settings->get('batch_number_prefix', config('settings.batch_number_prefix'));

        $minimumWage = $settings->get('minimum_wage', config('settings.minimum_wage'));
        $this->minimumWage = intval(str_replace([',', '.'], '', number_format(floatval($minimumWage), 2)));
        $this->projectNumPrefix = $settings->get('project_number_prefix', config('settings.project_number_prefix'));

        # Setting default dates in the datepicker
        $this->start = date('Y-m-d H:i:s', strtotime(now()->startOfYear()));
        $this->end = date('Y-m-d H:i:s', strtotime(now()));

        $this->defaultStart = date('m/d/Y', strtotime($this->start));
        $this->defaultEnd = date('m/d/Y', strtotime($this->end));

        # Assign Batches Modal
        $this->setCoordinator();
    }

    public function render()
    {
        # Check slots && Empty
        $this->checkImplementationTotalSlots();
        $this->checkBatchRemainingSlots();
        $this->checkBeneficiarySlots();
        $this->checkEmpty();

        # Create Project Modal
        $this->province = $this->provinces[0];
        $this->city_municipality = $this->cities_municipalities[0];
        $this->district = $this->districts[0];

        # Assign Batches Modal
        if ($this->implementationId) {
            $this->liveUpdateRemainingSlots();
        }

        return view('livewire.focal.implementations');
    }
}
