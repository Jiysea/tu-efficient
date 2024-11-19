<?php

namespace App\Livewire\Coordinator;

use App\Models\Archive;
use App\Models\Assignment;
use App\Models\Batch;
use App\Models\Beneficiary;
use App\Models\Credential;
use App\Models\UserSetting;
use App\Services\Annex;
use App\Services\LogIt;
use Carbon\Carbon;
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
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
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
    #[Locked]
    public $exportBatchId;
    public $batchNumPrefix;
    public $defaultArchive;
    public $showAlert = false;
    public $alertMessage = '';
    public $identity;
    public $special;
    public $addBeneficiariesModal = false;
    public $editBeneficiaryModal = false;
    public $deleteBeneficiaryModal = false;
    public $viewCredentialsModal = false;
    public $approveSubmissionModal = false;
    public $importFileModal = false;
    public $showExportModal = false;

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

    # --------------------------------------------------------------------------

    public $defaultExportStart;
    public $defaultExportEnd;
    public $export_start;
    public $export_end;
    public $exportFormat = 'xlsx';
    public $exportTypeCsv = 'annex_e1';
    public array $exportType = [
        'annex_e1' => true,
        'annex_e2' => false,
        'annex_j2' => false,
        'annex_l' => false,
        'annex_l_sign' => false,
    ]; # annex_e1, annex_e2, annex_j2, annex_l, annex_l_sign
    public $currentExportBatch;
    public $searchExportBatch;

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
            'defaultExportStart' => [
                'required'
            ],
            'defaultExportEnd' => [
                'required'
            ],
            'exportBatchId' => [
                'required'
            ],
        ];
    }

    public function messages()
    {
        return [
            'password_approve.required' => 'This field is required.',
            'defaultExportStart.required' => 'This field is required.',
            'defaultExportEnd.required' => 'This field is required.',
            'exportBatchId.required' => 'This field is required.',
        ];
    }

    public function showExport()
    {
        $this->defaultExportStart = Carbon::parse($this->start)->format('m/d/Y');
        $this->defaultExportEnd = Carbon::parse($this->end)->format('m/d/Y');

        $date = Carbon::createFromFormat('m/d/Y', $this->defaultExportStart)->format('Y-m-d');
        $choosenDate = date('Y-m-d', strtotime($date));
        $currentTime = date('H:i:s', strtotime(now()));
        $this->export_start = $choosenDate . ' ' . $currentTime;

        $date = Carbon::createFromFormat('m/d/Y', $this->defaultExportEnd)->format('Y-m-d');
        $choosenDate = date('Y-m-d', strtotime($date));
        $currentTime = date('H:i:s', strtotime(now()));
        $this->export_end = $choosenDate . ' ' . $currentTime;

        # By Selected Batch
        if ($this->batchId) {
            $this->exportBatchId = $this->batchId;
            $this->currentExportBatch = $this->exportBatch->batch_num . ' / ' . $this->exportBatch->barangay_name;
        }

        $this->showExportModal = true;
    }

    public function exportAnnex()
    {
        $this->validate([
            'exportBatchId' => [
                'required'
            ],
            'defaultExportStart' => [
                'required'
            ],
            'defaultExportEnd' => [
                'required'
            ],
        ], [
            'exportBatchId.required' => 'This field is required.',
            'defaultExportStart.required' => 'This field is required.',
            'defaultExportEnd.required' => 'This field is required.',
        ]);

        $batch = $this->exportBatch;

        $spreadsheet = new Spreadsheet();

        $writer = null;
        $fileName = null;

        if ($this->exportFormat === 'xlsx') {
            # Types of Annexes: annex_e1, annex_e2, annex_j2, annex_l, annex_l_sign
            $spreadsheet = Annex::export($spreadsheet, $batch, $this->exportType, $this->exportFormat);
            $writer = new Xlsx($spreadsheet);
            $fileName = 'TUPAD Annex.xlsx';
        } elseif ($this->exportFormat === 'csv') {
            # Types of Annexes: annex_e1, annex_e2, annex_j2, annex_l, annex_l_sign
            $spreadsheet = Annex::export($spreadsheet, $batch, $this->exportTypeCsv, $this->exportFormat);
            $writer = new Csv($spreadsheet);
            $fileName = 'TUPAD Annex.csv';
            $writer->setDelimiter(';');
            $writer->setEnclosure('"');
        }

        $filePath = storage_path($fileName);
        $writer->save($filePath);

        # Download the file
        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    public function selectExportBatchRow($encryptedId)
    {
        $this->exportBatchId = $encryptedId;
    }

    #[Computed]
    public function exportBatch()
    {
        $batch = Batch::find($this->exportBatchId ? decrypt($this->exportBatchId) : null);
        return $batch;
    }

    #[Computed]
    public function exportBatches()
    {
        $batches = Batch::whereHas('beneficiary')
            ->whereHas('assignment', function ($q) {
                $q->where('users_id', Auth::id());
            })
            ->join('assignments', 'batches.id', '=', 'assignments.batches_id')
            ->where('assignments.users_id', Auth::id())
            ->when(isset($this->searchExportBatch) && !empty($this->searchExportBatch), function ($q) {
                $q->where('batches.batch_num', 'LIKE', '%' . $this->searchExportBatch . '%')
                    ->orWhere('batches.barangay_name', 'LIKE', '%' . $this->searchExportBatch . '%');
            })
            ->whereBetween('batches.created_at', [$this->export_start, $this->export_end])
            ->latest('batches.updated_at')
            ->select([
                'batches.*'
            ])
            ->distinct()
            ->get();

        return $batches;
    }

    public function resetExport()
    {
        $this->reset('exportType', 'exportFormat');
        $this->resetValidation(['exportBatchId']);

        if ($this->exportBatches->isEmpty()) {
            $this->exportBatchId = null;
            $this->currentExportBatch = 'None';
        } else {
            $this->exportBatchId = encrypt($this->exportBatches[0]->id);
            $this->currentExportBatch = $this->exportBatches[0]->batch_num . ' / ' . $this->exportBatches[0]->barangay_name;
        }

    }

    # ------------------------------------------------------------------------------------------------------------------

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

        if ($this->batches->isNotEmpty()) {
            $this->batchId = encrypt($this->batches[0]->id);
        } else {
            $this->batchId = null;
            $this->searchBatches = null;
            $this->searchBeneficiaries = null;
        }
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
            ->when(isset($this->searchBatches) && !empty($this->searchBatches), function ($q) {
                $q->where('batches.batch_num', 'LIKE', $this->batchNumPrefix . '%' . $this->searchBatches . '%')
                    ->orWhere('batches.barangay_name', 'LIKE', '%' . $this->searchBatches . '%');
            })

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
        $beneficiaries = Batch::join('assignments', 'batches.id', '=', 'assignments.batches_id')
            ->join('beneficiaries', 'batches.id', '=', 'beneficiaries.batches_id')
            ->where('assignments.users_id', Auth::id())
            ->where('batches.id', $this->batchId ? decrypt($this->batchId) : null)
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
            ->orderBy('beneficiaries.last_name', 'asc')
            ->select([
                'beneficiaries.*',
            ])
            ->take($this->beneficiaries_on_page)
            ->get();

        return $beneficiaries;

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
    public function full_last_first($person)
    {

        $full_name = $person->last_name;
        $full_name .= ', ' . $person->first_name;

        if ($person->middle_name) {
            $full_name .= ' ' . $person->middle_name;
        }

        if ($person->extension_name) {
            $full_name .= ' ' . $person->extension_name;
        }

        return $full_name;
    }

    public function approveSubmission()
    {
        $this->validateOnly(field: 'password_approve');
        $batch = Batch::find(decrypt($this->batchId));
        $this->authorize('approve-submission-coordinator', $batch);

        $checkSlots = Batch::whereHas('beneficiary')
            ->where('batches.id', $batch->id)
            ->exists();

        if ($batch->approval_status !== 'approved' && ($batch->submission_status === 'submitted' || $batch->submission_status === 'unopened') && $checkSlots) {

            $batch->approval_status = 'approved';
            $batch->submission_status = 'submitted';
            $batch->save();

            $this->showAlert = true;
            $this->alertMessage = 'Successfully approved the batch assignment!';
            $this->dispatch('show-alert');

            LogIt::set_approve_batch(auth()->user(), $batch);
        } else {
            $this->showAlert = true;
            $this->alertMessage = 'Cannot approve batch assignment when it is not submitted';
            $this->dispatch('show-alert');
        }

        $this->dispatch('init-reload')->self();
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
        $beneficiary = Beneficiary::find(decrypt($this->beneficiaryId));
        $this->authorize('delete-beneficiary-coordinator', $beneficiary);

        DB::transaction(function () use ($beneficiary) {

            $credentials = Credential::where('beneficiaries_id', decrypt($this->beneficiaryId))
                ->get();
            if ($this->defaultArchive) {

                # Archive their credentials first
                foreach ($credentials as $credential) {
                    Archive::create([
                        'last_id' => $credential->id,
                        'source_table' => 'credentials',
                        'data' => $credential->toArray(),
                    ]);
                    $credential->delete();
                }

                # then archive the Beneficiary record
                Archive::create([
                    'last_id' => $beneficiary->id,
                    'source_table' => 'beneficiaries',
                    'data' => $beneficiary->toArray(),
                ]);
                $beneficiary->delete();

                LogIt::set_archive_beneficiary($beneficiary);

                $this->resetViewBeneficiary();
                $this->js('viewBeneficiaryModal = false;');
                $this->dispatch('archive-beneficiary');
                $this->showAlert = true;
                $this->alertMessage = 'Moved beneficiary to Archives';
            }

            # otherwise, we could just delete it.
            else {
                foreach ($credentials as $credential) {
                    if (isset($credential->image_file_path) && Storage::exists($credential->image_file_path)) {
                        Storage::delete($credential->image_file_path);
                    }
                    $credential->delete();
                }

                $beneficiary->delete();

                LogIt::set_delete_beneficiary($beneficiary, auth()->id());
                $this->showAlert = true;
                $this->alertMessage = 'Successfully deleted a beneficiary';
            }

            $dateTimeFromEnd = $this->end;
            $value = substr($dateTimeFromEnd, 0, 10);

            $choosenDate = date('Y-m-d', strtotime($value));
            $currentTime = date('H:i:s', strtotime(now()));
            $this->end = $choosenDate . ' ' . $currentTime;

            $this->beneficiaryId = null;
            $this->selectedBeneficiaryRow = -1;

            $this->dispatch('show-alert');
            $this->dispatch('init-reload')->self();

        });

        $this->deleteBeneficiaryModal = false;
    }

    #[On('optimistic-lock')]
    public function optimisticLockBeneficiary($message)
    {
        $dateTimeFromEnd = $this->end;
        $value = substr($dateTimeFromEnd, 0, 10);

        $choosenDate = date('Y-m-d', strtotime($value));
        $currentTime = date('H:i:s', strtotime(now()));
        $this->end = $choosenDate . ' ' . $currentTime;

        $this->passedBeneficiaryId = null;

        $this->selectedBeneficiaryRow = -1;

        $this->showAlert = true;
        $this->alertMessage = $message;
        $this->dispatch('show-alert');
        $this->dispatch('init-reload')->self();
        $this->viewBeneficiaryModal = false;
    }

    #[On('finished-importing')]
    public function finishedImporting()
    {
        $dateTimeFromEnd = $this->end;
        $value = substr($dateTimeFromEnd, 0, 10);

        $choosenDate = date('Y-m-d', strtotime($value));
        $currentTime = date('H:i:s', strtotime(now()));
        $this->end = $choosenDate . ' ' . $currentTime;

        if (!$this->importFileModal) {
            $this->showAlert = true;
            $this->alertMessage = 'Finished processing your import. Head to the Import tab.';
            $this->dispatch('show-alert');
        }
        $this->dispatch('init-reload')->self();
    }

    public function updated($prop)
    {
        if ($prop === 'defaultExportStart') {
            $this->validateOnly('defaultExportStart');
            $date = Carbon::createFromFormat('m/d/Y', $this->defaultExportStart)->format('Y-m-d');
            $choosenDate = date('Y-m-d', strtotime($date));
            $currentTime = date('H:i:s', strtotime(now()));
            $this->export_start = $choosenDate . ' ' . $currentTime;

            if ($this->exportBatches->isEmpty()) {
                $this->exportBatchId = null;
                $this->currentExportBatch = 'None';
            } else {
                $this->exportBatchId = encrypt($this->exportBatches[0]->id);
                $this->currentExportBatch = $this->exportBatches[0]->batch_num . ' / ' . $this->exportBatches[0]->barangay_name;
            }
        }

        if ($prop === 'defaultExportEnd') {
            $this->validateOnly('defaultExportEnd');
            $date = Carbon::createFromFormat('m/d/Y', $this->defaultExportEnd)->format('Y-m-d');
            $choosenDate = date('Y-m-d', strtotime($date));
            $currentTime = date('H:i:s', strtotime(now()));
            $this->export_end = $choosenDate . ' ' . $currentTime;

            if ($this->exportBatches->isEmpty()) {
                $this->exportBatchId = null;
                $this->currentExportBatch = 'None';
            } else {
                $this->exportBatchId = encrypt($this->exportBatches[0]->id);
                $this->currentExportBatch = $this->exportBatches[0]->batch_num . ' / ' . $this->exportBatches[0]->barangay_name;
            }
        }
    }

    public function resetPassword()
    {
        $this->reset('password_approve');
        $this->resetValidation(['password_approve']);
    }

    # batchId and coordinatorId will only be NOT null when the user clicks `View List` from the assignments page
    public function mount($batchId = null, $coordinatorId = null)
    {
        $user = Auth::user();
        if ($user->user_type !== 'coordinator') {
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

        $this->export_start = date('Y-m-d H:i:s', strtotime(now()->startOfYear()));
        $this->export_end = date('Y-m-d H:i:s', strtotime(now()));

        $this->defaultStart = date('m/d/Y', strtotime($this->start));
        $this->defaultEnd = date('m/d/Y', strtotime($this->end));

        $this->defaultExportStart = $this->defaultStart;
        $this->defaultExportEnd = $this->defaultEnd;

        if ($batchId !== null) {
            $this->batchId = $batchId;
        } elseif ($this->batches->isEmpty()) {
            $this->batchId = null;
        } else {
            $this->batchId = encrypt($this->batches[0]->id);
        }

        if ($this->exportBatches->isEmpty()) {
            $this->exportBatchId = null;
            $this->currentExportBatch = 'None';
        } else {
            $this->exportBatchId = encrypt($this->exportBatches[0]->id);
            $this->currentExportBatch = $this->exportBatches[0]->batch_num . ' / ' . $this->exportBatches[0]->barangay_name;
        }

        $settings = UserSetting::where('users_id', Auth::id())
            ->pluck('value', 'key');
        $this->batchNumPrefix = $settings->get('batch_num_prefix', config('settings.batch_number_prefix'));
        $this->defaultArchive = intval($settings->get('default_archive', config('settings.default_archive')));

    }

    public function render()
    {
        return view('livewire.coordinator.submissions');
    }
}
