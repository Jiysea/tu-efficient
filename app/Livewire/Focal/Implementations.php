<?php

namespace App\Livewire\Focal;

use App\Models\Batch;
use App\Models\Beneficiary;
use App\Models\Implementation;
use App\Models\UserSetting;
use App\Services\Annex;
use Carbon\Carbon;
use Crypt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

#[Layout('layouts.app')]
#[Title('Implementations | TU-Efficient')]
class Implementations extends Component
{
    #[Locked]
    public $implementationId;
    #[Locked]
    public $batchId;
    #[Locked]
    public $exportBatchId;
    #[Locked]
    public $beneficiaryId;

    # ------------------------------------------

    #[Locked]
    public $passedProjectId;
    public $createProjectModal = false;
    public $viewProjectModal = false;
    #[Locked]
    public $passedBatchId;
    public $assignBatchesModal = false;
    public $viewBatchModal = false;
    #[Locked]
    public $passedBeneficiaryId;
    public $addBeneficiariesModal = false;
    public $viewBeneficiaryModal = false;
    public $importFileModal = false;
    public $showExportModal = false;

    # -----------------------------------------
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

        $this->showExportModal = true;
    }

    public function exportAnnex()
    {
        $this->validate([
            'exportBatchId' => [
                'required'
            ],
        ], [
            'exportBatchId.required' => 'This field is required.'
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
        $batches = Batch::whereHas('implementation', function ($q) {
            $q->where('users_id', Auth::id());
        })->whereHas('beneficiary')
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
        $this->implementations_on_page = 15;
        $this->beneficiaries_on_page = 15;

        $this->passedProjectId = null;
        $this->passedBatchId = null;
        $this->passedBeneficiaryId = null;
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
        $this->passedBeneficiaryId = null;
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

    public function viewBeneficiary(string $beneficiaryId)
    {
        $this->passedBeneficiaryId = $beneficiaryId;
        $this->viewBeneficiaryModal = true;
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
        if ($key === $this->selectedBeneficiaryRow) {
            $this->selectedBeneficiaryRow = -1;
            $this->beneficiaryId = null;
        } else {
            $this->selectedBeneficiaryRow = $key;
            $this->beneficiaryId = $encryptedId;
        }

        $this->dispatch('init-reload')->self();
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
    public function implementation()
    {
        if ($this->implementationId) {
            $implementation = Implementation::find(decrypt($this->implementationId));
            return $implementation;
        }
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
                ->select(
                    [
                        'beneficiaries.*'
                    ],
                )
                ->take($this->beneficiaries_on_page)
                ->get();

            return $beneficiaries;
        }
    }

    #[Computed]
    public function specialCasesCount()
    {
        $beneficiaries = Implementation::where('implementations.users_id', Auth::id())
            ->join('batches', 'implementations.id', '=', 'batches.implementations_id')
            ->join('beneficiaries', 'batches.id', '=', 'beneficiaries.batches_id')
            ->where('batches.id', decrypt($this->batchId))
            ->where('beneficiary_type', 'special case')
            ->count();
        return $beneficiaries;
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

    #[On('create-project')]
    public function saveProject()
    {
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

    #[On('edit-project')]
    public function editProject()
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

    #[On('delete-project')]
    public function deleteProject()
    {
        $dateTimeFromEnd = $this->end;
        $value = substr($dateTimeFromEnd, 0, 10);

        $choosenDate = date('Y-m-d', strtotime($value));
        $currentTime = date('H:i:s', strtotime(now()));
        $this->end = $choosenDate . ' ' . $currentTime;

        $this->passedProjectId = null;
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

        $this->viewProjectModal = false;
    }

    #[On('assign-batches')]
    public function saveBatches()
    {
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

    #[On('edit-batch')]
    public function editBatch()
    {
        $dateTimeFromEnd = $this->end;
        $value = substr($dateTimeFromEnd, 0, 10);

        $choosenDate = date('Y-m-d', strtotime($value));
        $currentTime = date('H:i:s', strtotime(now()));
        $this->end = $choosenDate . ' ' . $currentTime;

        $this->showAlert = true;
        $this->alertMessage = 'Batch successfully updated!';
        $this->dispatch('show-alert');
        $this->dispatch('init-reload')->self();
    }

    #[On('delete-batch')]
    public function deleteBatch()
    {
        $dateTimeFromEnd = $this->end;
        $value = substr($dateTimeFromEnd, 0, 10);

        $choosenDate = date('Y-m-d', strtotime($value));
        $currentTime = date('H:i:s', strtotime(now()));
        $this->end = $choosenDate . ' ' . $currentTime;

        $this->passedBatchId = null;
        $this->batchId = null;
        $this->beneficiaryId = null;

        $this->selectedBatchRow = -1;
        $this->selectedBeneficiaryRow = -1;

        $this->showAlert = true;
        $this->alertMessage = 'Successfully removed the batch!';
        $this->dispatch('show-alert');
        $this->dispatch('init-reload')->self();

        $this->viewBatchModal = false;
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

    // #[On('import-success-beneficiaries')]
    // public function importSuccessBeneficiaries($count)
    // {
    //     $dateTimeFromEnd = $this->end;
    //     $value = substr($dateTimeFromEnd, 0, 10);

    //     $choosenDate = date('Y-m-d', strtotime($value));
    //     $currentTime = date('H:i:s', strtotime(now()));
    //     $this->end = $choosenDate . ' ' . $currentTime;

    //     $this->beneficiaryId = null;

    //     $this->selectedBeneficiaryRow = -1;

    //     $this->showAlert = true;
    //     $this->alertMessage = 'Imported ' . $count . ' beneficiaries to the database.';
    //     $this->dispatch('show-alert');
    //     $this->dispatch('init-reload')->self();
    // }

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

    #[On('delete-beneficiary')]
    public function deleteBeneficiary()
    {
        $dateTimeFromEnd = $this->end;
        $value = substr($dateTimeFromEnd, 0, 10);

        $choosenDate = date('Y-m-d', strtotime($value));
        $currentTime = date('H:i:s', strtotime(now()));
        $this->end = $choosenDate . ' ' . $currentTime;

        $this->passedBeneficiaryId = null;
        $this->beneficiaryId = null;

        $this->selectedBeneficiaryRow = -1;

        $this->showAlert = true;
        $this->alertMessage = 'Beneficiary record has been deleted.';
        $this->dispatch('show-alert');
        $this->dispatch('init-reload')->self();

        $this->viewBeneficiaryModal = false;
    }

    #[On('archive-beneficiary')]
    public function archiveBeneficiary()
    {
        $dateTimeFromEnd = $this->end;
        $value = substr($dateTimeFromEnd, 0, 10);

        $choosenDate = date('Y-m-d', strtotime($value));
        $currentTime = date('H:i:s', strtotime(now()));
        $this->end = $choosenDate . ' ' . $currentTime;

        $this->passedBeneficiaryId = null;
        $this->beneficiaryId = null;

        $this->selectedBeneficiaryRow = -1;

        $this->showAlert = true;
        $this->alertMessage = 'Moved record to Archives.';
        $this->dispatch('show-alert');
        $this->dispatch('init-reload')->self();

        $this->viewBeneficiaryModal = false;
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
        $this->beneficiaryId = null;

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

    public function mount()
    {
        $user = Auth::user();
        if ($user->user_type !== 'focal' || $user->isOngoingVerification()) {
            $this->redirectIntended();
        }

        # setting up settings
        $settings = UserSetting::where('users_id', Auth::id())
            ->pluck('value', 'key');
        $this->projectNumPrefix = $settings->get('project_number_prefix', config('settings.project_number_prefix'));

        # Setting default dates in the datepicker
        $this->start = date('Y-m-d H:i:s', strtotime(now()->startOfYear()));
        $this->end = date('Y-m-d H:i:s', strtotime(now()));

        $this->export_start = $this->start;
        $this->export_end = $this->end;

        $this->defaultStart = date('m/d/Y', strtotime($this->start));
        $this->defaultEnd = date('m/d/Y', strtotime($this->end));

        $this->defaultExportStart = $this->defaultStart;
        $this->defaultExportEnd = $this->defaultEnd;

        if ($this->exportBatches->isEmpty()) {
            $this->exportBatchId = null;
            $this->currentExportBatch = 'None';
        } else {
            $this->exportBatchId = encrypt($this->exportBatches[0]->id);
            $this->currentExportBatch = $this->exportBatches[0]->batch_num . ' / ' . $this->exportBatches[0]->barangay_name;
        }
    }

    public function render()
    {
        # Check slots && Empty
        $this->checkImplementationTotalSlots();
        $this->checkBatchRemainingSlots();
        $this->checkBeneficiarySlots();

        return view('livewire.focal.implementations');
    }
}
