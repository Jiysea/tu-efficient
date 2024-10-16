<?php

namespace App\Livewire\Coordinator;

use App\Models\Assignment;
use App\Models\Batch;
use App\Models\Beneficiary;
use App\Models\Credential;
use App\Models\UserSetting;
use DB;
use Hash;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Storage;

#[Layout('layouts.app')]
#[Title('Submissions | TU-Efficient')]
class Submissions extends Component
{
    #[Locked]
    public $beneficiaryId;
    #[Locked]
    public $batchId;
    #[Locked]
    public $passedCredentialId;
    public $batchNumPrefix;
    public $showAlert = false;
    public $alertMessage = '';
    public $identity;
    public $special;
    public $addBeneficiariesModal = false;
    public $editBeneficiaryModal = false;
    public $deleteBeneficiaryModal = false;
    public $viewCredentialsModal = false;
    public $approveSubmissionModal = false;

    # --------------------------------------------------------------------------

    public $defaultBatches_on_page = 15;
    public $defaultBeneficiaries_on_page = 30;
    public $batches_on_page = 15;
    public $beneficiaries_on_page = 30;
    public $selectedBatchRow = -1;
    public $selectedBeneficiaryRow = -1;
    public $selectedSpecialCaseRow = -1;
    public $searchBeneficiaries;
    public $searchBatches;

    # ------------------------------------------

    public $start;
    public $end;
    public $defaultStart;
    public $defaultEnd;
    public $approvalStatuses = [
        'approved' => false,
        'pending' => true,
    ];

    public $submissionStatuses = [
        'submitted' => true,
        'encoding' => true,
        'unopened' => true,
        'revalidate' => true,
    ];

    public array $filter = [];

    # ------------------------------------------

    #[Validate]
    public $password_approve;
    #[Validate]
    public $password_delete;

    # ------------------------------------------

    public function rules()
    {
        return [
            'password_approve' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!Hash::check($value, Auth::user()->password)) {
                        $fail('Wrong password.');
                    }
                },
            ],

            'password_delete' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!Hash::check($value, Auth::user()->password)) {
                        $fail('Wrong password.');
                    }
                },
            ],
        ];
    }

    public function messages()
    {
        return [
            'password_approve.required' => 'This field is required.',
            'password_delete.required' => 'This field is required.',
        ];
    }

    #[On('start-change')]
    public function setStartDate($value)
    {
        $choosenDate = date('Y-m-d', strtotime($value));
        $currentTime = date('H:i:s', strtotime(now()));

        $this->start = $choosenDate . ' ' . $currentTime;
        $this->batches_on_page = $this->defaultBatches_on_page;
        $this->beneficiaries_on_page = $this->defaultBeneficiaries_on_page;

        if ($this->batches->isNotEmpty()) {
            $this->batchId = encrypt($this->batches[0]->id);
        } else {
            $this->batchId = null;
            $this->searchBatches = null;
            $this->searchBeneficiaries = null;
        }

        $this->beneficiaryId = null;
        $this->selectedBatchRow = -1;
        $this->selectedBeneficiaryRow = -1;

        $this->dispatch('init-reload')->self();
        $this->dispatch('scroll-to-top')->self();
    }

    #[On('end-change')]
    public function setEndDate($value)
    {
        $choosenDate = date('Y-m-d', strtotime($value));
        $currentTime = date('H:i:s', strtotime(now()));

        $this->end = $choosenDate . ' ' . $currentTime;
        $this->batches_on_page = $this->defaultBatches_on_page;
        $this->beneficiaries_on_page = $this->defaultBeneficiaries_on_page;

        if ($this->batches->isNotEmpty()) {
            $this->batchId = encrypt($this->batches[0]->id);
        } else {
            $this->batchId = null;
            $this->searchBatches = null;
            $this->searchBeneficiaries = null;
        }

        $this->beneficiaryId = null;
        $this->selectedBatchRow = -1;
        $this->selectedBeneficiaryRow = -1;

        $this->dispatch('init-reload')->self();
        $this->dispatch('scroll-to-top')->self();
    }

    public function selectBatchRow($key, $encryptedId)
    {
        $this->selectedBatchRow = $key;
        $this->batchId = $encryptedId;
        $this->beneficiaryId = null;
        $this->selectedBeneficiaryRow = -1;
        $this->searchBeneficiaries = null;
        $this->beneficiaries_on_page = $this->defaultBeneficiaries_on_page;

        $this->dispatch('init-reload')->self();
        $this->dispatch('scroll-to-top')->self();
    }

    public function selectBeneficiaryRow($key, $encryptedId)
    {
        if ($this->selectedBeneficiaryRow === $key) {
            $this->selectedBeneficiaryRow = -1;
            $this->beneficiaryId = null;

            $this->identity = null;
            $this->special = null;

        } else {
            $this->selectedBeneficiaryRow = $key;
            $this->beneficiaryId = $encryptedId;

            $this->identity = null;
            $this->special = null;

            foreach ($this->credentials as $credential) {
                if ($credential->for_duplicates === 'no') {
                    $this->identity = $credential->image_file_path;
                } elseif ($credential->for_duplicates === 'yes') {
                    $this->special = $credential->image_file_path;
                }
            }
        }

        $this->dispatch('init-reload')->self();
    }

    public function openEdit()
    {
        $this->editBeneficiaryModal = true;
    }

    public function viewCredential($type)
    {
        if ($type === 'identity') {

            foreach ($this->credentials as $credential) {
                if ($credential->for_duplicates === 'no') {
                    $this->passedCredentialId = encrypt($credential->id);
                    $this->viewCredentialsModal = true;
                }
            }

        } elseif ($type === 'special') {

            foreach ($this->credentials as $credential) {
                if ($credential->for_duplicates === 'yes') {
                    $this->passedCredentialId = encrypt($credential->id);
                    $this->viewCredentialsModal = true;
                }
            }
        }
    }

    public function applyFilter()
    {
        $this->filter = [
            'approval_status' => $this->approvalStatuses,
            'submission_status' => $this->submissionStatuses,
        ];
    }

    public function resetFilter()
    {
        $this->approvalStatuses = $this->filter['approval_status'];
        $this->submissionStatuses = $this->filter['submission_status'];
    }

    #[Computed]
    public function batches()
    {
        $approvalStatuses = array_keys(array_filter($this->filter['approval_status']));
        $submissionStatuses = array_keys(array_filter($this->filter['submission_status']));

        $batches = Batch::join('assignments', 'batches.id', '=', 'assignments.batches_id')
            ->where('assignments.users_id', Auth::id())
            ->whereBetween('batches.created_at', [$this->start, $this->end])
            ->when(!empty($approvalStatuses), function ($q) use ($approvalStatuses) {
                $q->whereIn('batches.approval_status', $approvalStatuses);
            })
            ->when(!empty($submissionStatuses), function ($q) use ($submissionStatuses) {
                $q->whereIn('batches.submission_status', $submissionStatuses);
            })
            ->where('batches.batch_num', 'LIKE', $this->batchNumPrefix . '%' . $this->searchBatches . '%')
            ->select(
                [
                    'batches.id',
                    'batches.batch_num',
                    'batches.barangay_name',
                    'batches.approval_status',
                    'batches.submission_status'
                ]
            )
            ->groupBy([
                'batches.id',
                'batches.batch_num',
                'batches.barangay_name',
                'batches.approval_status',
                'batches.submission_status'
            ])
            ->orderBy('batches.id', 'desc')
            ->get();

        return $batches;
    }

    #[Computed]
    public function beneficiaries()
    {
        if ($this->batchId) {
            $beneficiaries = Batch::join('assignments', 'batches.id', '=', 'assignments.batches_id')
                ->join('beneficiaries', 'batches.id', '=', 'beneficiaries.batches_id')
                ->where('assignments.users_id', Auth::id())
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
                        # Otherwise, search by first, middle, last or extension name
                        $q->where(function ($query) {
                            $query->where('beneficiaries.first_name', 'LIKE', '%' . $this->searchBeneficiaries . '%')
                                ->orWhere('beneficiaries.middle_name', 'LIKE', '%' . $this->searchBeneficiaries . '%')
                                ->orWhere('beneficiaries.last_name', 'LIKE', '%' . $this->searchBeneficiaries . '%')
                                ->orWhere('beneficiaries.extension_name', 'LIKE', '%' . $this->searchBeneficiaries . '%');
                        });
                    }

                })
                ->select([
                    'beneficiaries.*',
                ])
                ->take($this->beneficiaries_on_page)
                ->get();

            return $beneficiaries;
        }

        return null;
    }

    #[Computed]
    public function batch()
    {
        if ($this->batchId) {
            $batch = Batch::find(decrypt($this->batchId));
            return $batch;
        }

        return null;
    }

    #[Computed]
    public function beneficiary()
    {
        if ($this->beneficiaryId) {
            $beneficiary = Beneficiary::find(decrypt($this->beneficiaryId));
            return $beneficiary;
        }

        return null;
    }

    #[Computed]
    public function specialCases()
    {
        if ($this->batchId) {
            $special_cases = Batch::join('assignments', 'batches.id', '=', 'assignments.batches_id')
                ->join('beneficiaries', 'batches.id', '=', 'beneficiaries.batches_id')
                ->where('assignments.users_id', Auth::id())
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
                        # Otherwise, search by first, middle, last or extension name
                        $q->where(function ($query) {
                            $query->where('beneficiaries.first_name', 'LIKE', '%' . $this->searchBeneficiaries . '%')
                                ->orWhere('beneficiaries.middle_name', 'LIKE', '%' . $this->searchBeneficiaries . '%')
                                ->orWhere('beneficiaries.last_name', 'LIKE', '%' . $this->searchBeneficiaries . '%')
                                ->orWhere('beneficiaries.extension_name', 'LIKE', '%' . $this->searchBeneficiaries . '%');
                        });
                    }

                })
                ->where('beneficiaries.beneficiary_type', 'special case')
                ->select([
                    'beneficiaries.*',
                ])
                ->count();
            return $special_cases;
        }

        return 0;
    }

    #[Computed]
    public function getIdType()
    {
        $type_of_id = null;

        if ($this->beneficiaryId) {

            if (str_contains($this->beneficiary->type_of_id, 'PWD')) {
                $type_of_id = 'PWD ID';
            } else if (str_contains($this->beneficiary->type_of_id, 'COMELEC')) {
                $type_of_id = 'Voter\'s ID';
            } else if (str_contains($this->beneficiary->type_of_id, 'PhilID')) {
                $type_of_id = 'PhilID';
            } else if (str_contains($this->beneficiary->type_of_id, '4Ps')) {
                $type_of_id = '4Ps ID';
            } else if (str_contains($this->beneficiary->type_of_id, 'IBP')) {
                $type_of_id = 'IBP ID';
            } else {
                $type_of_id = $this->beneficiary->type_of_id;
            }

        }

        return $type_of_id;
    }

    #[Computed]
    public function batchesCount()
    {
        $approvalStatuses = array_keys(array_filter($this->filter['approval_status']));
        $submissionStatuses = array_keys(array_filter($this->filter['submission_status']));

        $batchesCount = Assignment::join('batches', 'batches.id', '=', 'assignments.batches_id')
            ->where('assignments.users_id', Auth::id())
            ->whereBetween('batches.created_at', [$this->start, $this->end])
            ->when(!empty($approvalStatuses), function ($q) use ($approvalStatuses) {
                $q->whereIn('batches.approval_status', $approvalStatuses);
            })
            ->when(!empty($submissionStatuses), function ($q) use ($submissionStatuses) {
                $q->whereIn('batches.submission_status', $submissionStatuses);
            })
            ->where('batches.batch_num', 'LIKE', $this->batchNumPrefix . '%' . $this->searchBatches . '%')
            ->count();

        return $batchesCount;
    }

    #[Computed]
    public function beneficiarySlots()
    {
        if ($this->batchId) {

            $batch = Batch::where('id', decrypt($this->batchId))
                ->first();

            $totalSlots = $batch->slots_allocated;

            $totalBeneficiaries = Beneficiary::where('beneficiaries.batches_id', decrypt($this->batchId))
                ->count();


            return [
                'slots_allocated' => $totalSlots ?? 0,
                'num_of_beneficiaries' => $totalBeneficiaries,
            ];
        }

        return [
            'slots_allocated' => 0,
            'num_of_beneficiaries' => 0,
        ];
    }

    #[Computed]
    public function currentBatch()
    {
        $currentBatch = null;

        if ($this->batchId) {
            $currentBatch = Batch::where('id', decrypt($this->batchId))
                ->first()->batch_num;
        } else if ($this->batches->isNotEmpty()) {
            $currentBatch = Batch::where('id', $this->batches[0]->id)
                ->first()->batch_num;
        } else {
            $currentBatch = 'None';
        }

        return $currentBatch;
    }

    #[Computed]
    public function credentials()
    {
        if ($this->beneficiaryId) {
            $credentials = Credential::where('beneficiaries_id', decrypt($this->beneficiaryId))
                ->get();

            return $credentials;
        }
    }

    # this loads the beneficiaries to take more after scrolling to the bottom on the table list
    public function loadMoreBeneficiaries()
    {
        $this->beneficiaries_on_page += $this->defaultBeneficiaries_on_page;
        $this->dispatch('init-reload')->self();
    }

    #[Computed]
    public function getFullName($key)
    {
        $full_name = null;

        $first = $this->beneficiaries[$key]['first_name'];
        $middle = $this->beneficiaries[$key]['middle_name'];
        $last = $this->beneficiaries[$key]['last_name'];
        $ext = $this->beneficiaries[$key]['extension_name'];

        $full_name = $first;
        if ($middle) {
            $full_name .= ' ' . $middle;
        }

        $full_name .= ' ' . $last;

        if ($ext) {
            $full_name .= ' ' . $ext;
        }

        return $full_name;
    }

    public function approveSubmission()
    {
        $this->validateOnly(field: 'password_approve');
        $batch = Batch::find(decrypt($this->batchId));
        $this->authorize('approve-submission-coordinator', $batch);

        $this->approveSubmissionModal = false;
        unset($this->batches);
    }

    #[On('add-beneficiaries')]
    public function saveBeneficiaries()
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

    #[On('edit-beneficiary')]
    public function editBeneficiary()
    {
        $dateTimeFromEnd = $this->end;
        $value = substr($dateTimeFromEnd, 0, 10);

        $choosenDate = date('Y-m-d', strtotime($value));
        $currentTime = date('H:i:s', strtotime(now()));
        $this->end = $choosenDate . ' ' . $currentTime;

        $this->showAlert = true;
        $this->alertMessage = 'Beneficiary successfully updated!';
        $this->dispatch('show-alert');
        $this->dispatch('init-reload')->self();
    }

    public function deleteBeneficiary()
    {
        $this->validateOnly('password_delete');
        $beneficiary = Beneficiary::find(decrypt($this->beneficiaryId));
        $this->authorize('delete-beneficiary-coordinator', $beneficiary);

        DB::transaction(function () use ($beneficiary) {

            $credentials = Credential::where('beneficiaries_id', decrypt($this->beneficiaryId))
                ->get();

            foreach ($credentials as $credential) {
                if (isset($credential->image_file_path)) {
                    Storage::delete($credential->image_file_path);
                }

                $credential->delete();
            }
            $beneficiary->delete();

            $dateTimeFromEnd = $this->end;
            $value = substr($dateTimeFromEnd, 0, 10);

            $choosenDate = date('Y-m-d', strtotime($value));
            $currentTime = date('H:i:s', strtotime(now()));
            $this->end = $choosenDate . ' ' . $currentTime;

            $this->beneficiaryId = null;
            $this->selectedBeneficiaryRow = -1;

            $this->showAlert = true;
            $this->alertMessage = 'Successfully deleted a beneficiary!';
            $this->dispatch('show-alert');
            $this->dispatch('init-reload')->self();

        });

        $this->deleteBeneficiaryModal = false;
        $this->reset('password_delete');
        $this->resetValidation('password_delete');
    }

    public function resetPassword()
    {
        $this->reset('password_approve', 'password_delete');
        $this->resetValidation(['password_approve', 'password_delete']);
    }

    # batchId and coordinatorId will only be NOT null when the user clicks `View List` from the assignments page
    public function mount($batchId = null, $coordinatorId = null)
    {
        if (Auth::user()->user_type !== 'coordinator') {
            $this->redirectIntended();
        }

        if ($coordinatorId !== null) {
            if ($coordinatorId && Auth::user()->id === $coordinatorId) {
                $this->redirectIntended();
            }
        }

        $this->filter = [
            'approval_status' => $this->approvalStatuses,
            'submission_status' => $this->submissionStatuses,
        ];

        $this->start = date('Y-m-d H:i:s', strtotime(now()->startOfYear()));
        $this->end = date('Y-m-d H:i:s', strtotime(now()));

        if ($batchId !== null) {
            $this->batchId = $batchId;
        } elseif ($this->batches->isEmpty()) {
            $this->batchId = null;
        } else {
            $this->batchId = encrypt($this->batches[0]->id);
        }

        $settings = UserSetting::where('users_id', Auth::id())
            ->pluck('value', 'key');
        $this->batchNumPrefix = $settings->get('batch_num_prefix', config('settings.batch_number_prefix'));

        $this->defaultStart = date('m/d/Y', strtotime($this->start));
        $this->defaultEnd = date('m/d/Y', strtotime($this->end));

    }

    public function render()
    {
        return view('livewire.coordinator.submissions');
    }
}
