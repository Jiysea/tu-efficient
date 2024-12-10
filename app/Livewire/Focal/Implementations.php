<?php

namespace App\Livewire\Focal;

use App\Models\Batch;
use App\Models\Beneficiary;
use App\Models\Implementation;
use App\Models\UserSetting;
use App\Services\Annex;
use App\Services\Essential;
use App\Services\JaccardSimilarity;
use Carbon\Carbon;
use Crypt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Js;
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
    public $projectNumPrefix;
    public $duplicationThreshold;
    public $allSimilarityResults;

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
    public $implementations_on_page = 15;
    public $beneficiaries_on_page = 15;
    public $selectedImplementationRow = -1;
    public $selectedBatchRow = -1;
    public $selectedBeneficiaryRow = -1;
    public $start;
    public $end;
    public $calendarStart;
    public $calendarEnd;
    public $defaultStart;
    public $defaultEnd;

    # ------------------------------------------

    public function rules()
    {
        return [
            'exportBatchId' => [
                'required'
            ],
        ];
    }

    public function messages()
    {
        return [
            'exportBatchId.required' => 'This field is required.',
        ];
    }

    public function showExport()
    {
        $this->defaultExportStart = $this->calendarStart;
        $this->defaultExportEnd = $this->calendarEnd;

        $this->export_start = $this->start;
        $this->export_end = $this->end;

        # By Selected Batch
        if ($this->batchId) {
            $this->exportBatchId = $this->batchId;
            $this->currentExportBatch = ($this->exportBatch->sector_title ?? $this->exportBatch->barangay_name) . ' / ' . $this->exportBatch->batch_num;
        }

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
        return Batch::find($this->exportBatchId ? decrypt($this->exportBatchId) : null);
    }

    #[Computed]
    public function exportBatches()
    {
        return Batch::whereHas('implementation', function ($q) {
            $q->where('users_id', Auth::id());
        })->whereHas('beneficiary')
            ->when(isset($this->searchExportBatch) && !empty($this->searchExportBatch), function ($q) {
                $q->where(function ($query) {
                    $query->where('batches.batch_num', 'LIKE', '%' . $this->searchExportBatch . '%')
                        ->orWhere('batches.sector_title', 'LIKE', '%' . $this->searchExportBatch . '%')
                        ->orWhere('batches.barangay_name', 'LIKE', '%' . $this->searchExportBatch . '%');
                });
            })
            ->whereBetween('batches.created_at', [$this->export_start, $this->export_end])
            ->latest('batches.updated_at')
            ->select([
                'batches.*'
            ])
            ->distinct()
            ->get();
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
            $this->currentExportBatch = ($this->exportBatches[0]->sector_title ?? $this->exportBatches[0]->barangay_name) . ' / (' . $this->exportBatches[0]->batch_num . ')';
        }

    }

    # ------------------------------------------------------------------------------------------------------------------

    public function viewProject(string $encryptedId)
    {
        $this->passedProjectId = $encryptedId;
        $this->viewProjectModal = true;
    }

    public function viewBatch(string $encryptedId)
    {
        $this->passedBatchId = $encryptedId;
        $this->viewBatchModal = true;
    }

    public function viewBeneficiary(string $encryptedId)
    {
        $this->passedBeneficiaryId = $encryptedId;
        $this->viewBeneficiaryModal = true;
    }

    public function selectImplementationRow($key, $encryptedId)
    {
        if ($key === $this->selectedImplementationRow) {
            $this->selectedImplementationRow = -1;
            $this->implementationId = null;
            unset($this->implementations);
            unset($this->batches);
            unset($this->beneficiaries);
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
            unset($this->implementations);
            unset($this->batches);
            unset($this->beneficiaries);
        } else {
            $this->selectedBatchRow = $key;
            $this->batchId = $encryptedId;
        }

        $this->beneficiaries_on_page = 15;
        $this->selectedBeneficiaryRow = -1;

        $this->dispatch('init-reload')->self();
    }

    #[Computed]
    public function implementations()
    {
        $implementations = Implementation::where('users_id', Auth::id())
            ->whereBetween('created_at', [$this->start, $this->end])
            ->when($this->searchProjects, function ($q) {
                $q->where('project_num', 'LIKE', $this->projectNumPrefix . '%' . $this->searchProjects . '%')
                    ->orWhere('project_title', 'LIKE', '%' . $this->searchProjects . '%');
            })
            ->latest('updated_at')
            ->take($this->implementations_on_page)
            ->get();

        return $implementations;
    }

    #[Computed]
    public function implementation()
    {
        $implementation = Implementation::find($this->implementationId ? decrypt($this->implementationId) : null);
        return $implementation;
    }

    #[Computed]
    public function batches()
    {
        $batches = Implementation::where('implementations.users_id', Auth::id())
            ->join('batches', 'implementations.id', '=', 'batches.implementations_id')
            ->leftJoin('beneficiaries', 'batches.id', '=', 'beneficiaries.batches_id')
            ->where('implementations.id', $this->implementationId ? decrypt($this->implementationId) : null)
            ->select([
                'batches.id',
                'batches.is_sectoral',
                'batches.barangay_name',
                'batches.sector_title',
                'batches.slots_allocated',
                DB::raw('COUNT(DISTINCT beneficiaries.id) AS current_slots'),
                DB::raw('batches.approval_status AS approval_status')
            ])
            ->groupBy('batches.id', 'batches.is_sectoral', 'barangay_name', 'sector_title', 'slots_allocated', 'approval_status')
            ->orderBy('batches.id', 'desc')
            ->get();

        return $batches;

    }

    #[Computed]
    public function beneficiaries()
    {
        $beneficiaries = Implementation::where('implementations.users_id', Auth::id())
            ->join('batches', 'implementations.id', '=', 'batches.implementations_id')
            ->join('beneficiaries', 'batches.id', '=', 'beneficiaries.batches_id')
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
            ->select(
                [
                    'beneficiaries.*'
                ],
            )
            ->take($this->beneficiaries_on_page)
            ->get();

        return $beneficiaries;

    }

    #[Computed]
    public function nameCheck($person)
    {
        $results = null;

        if ($this->beneficiaries->isNotEmpty()) {
            $results = JaccardSimilarity::isOverThreshold($person, $this->duplicationThreshold);
        }

        $this->dispatch('init-reload')->self();
        return $results;
    }

    #[Computed]
    public function specialCasesCount()
    {
        $beneficiaries = Implementation::where('implementations.users_id', Auth::id())
            ->join('batches', 'implementations.id', '=', 'batches.implementations_id')
            ->join('beneficiaries', 'batches.id', '=', 'beneficiaries.batches_id')
            ->where('batches.id', $this->batchId ? decrypt($this->batchId) : null)
            ->where('beneficiary_type', 'special case')
            ->count();
        return $beneficiaries;
    }

    #[Computed]
    public function totalImplementations()
    {
        return Implementation::where('users_id', Auth::id())
            ->whereBetween('created_at', [$this->start, $this->end])
            ->count();
    }

    #[Computed]
    public function remainingBatchSlots()
    {
        $remainingBatchSlots = $this->implementationId ? $this->implementation->total_slots : null;

        $batchesCount = Batch::join('implementations', 'implementations.id', '=', 'batches.implementations_id')
            ->where('implementations.users_id', Auth::id())
            ->where('implementations.id', $this->implementationId ? decrypt($this->implementationId) : null)
            ->select('batches.slots_allocated')
            ->get();

        foreach ($batchesCount as $batch) {
            $remainingBatchSlots -= $batch?->slots_allocated;
        }
        return $remainingBatchSlots;
    }

    #[Computed]
    public function beneficiarySlots()
    {
        $batch = Batch::where('id', $this->batchId ? decrypt($this->batchId) : null)
            ->first();

        $beneficiarySlots = $batch?->slots_allocated;

        $beneficiaryCount = Beneficiary::where('batches_id', $this->batchId ? decrypt($this->batchId) : null)
            ->count();

        $beneficiarySlots = [
            'batch_slots_allocated' => $batch?->slots_allocated,
            'num_of_beneficiaries' => $beneficiaryCount
        ];

        return $beneficiarySlots;
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
            $currentTime = date('H:i:s', strtotime(now()->startOfDay()));
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
            $currentTime = date('H:i:s', strtotime(now()->endOfDay()));
            $this->export_end = $choosenDate . ' ' . $currentTime;

            if ($this->exportBatches->isEmpty()) {
                $this->exportBatchId = null;
                $this->currentExportBatch = 'None';
            } else {
                $this->exportBatchId = encrypt($this->exportBatches[0]->id);
                $this->currentExportBatch = $this->exportBatches[0]->batch_num . ' / ' . $this->exportBatches[0]->barangay_name;
            }
        }

        if ($prop === 'calendarStart') {
            $format = Essential::extract_date($this->calendarStart, false);
            if ($format !== 'm/d/Y') {
                $this->calendarStart = $this->defaultStart;
                return;
            }

            $choosenDate = Carbon::createFromFormat('m/d/Y', $this->calendarStart)->format('Y-m-d');
            $currentTime = now()->startOfDay()->format('H:i:s');

            $this->start = $choosenDate . ' ' . $currentTime;

            if (strtotime($this->start) > strtotime($this->end)) {
                $end = Carbon::parse($this->start)->addMonth()->endOfDay()->format('Y-m-d H:i:s');
                $this->end = $end;
                $this->calendarEnd = Carbon::parse($this->end)->format('m/d/Y');
            }

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
            $this->dispatch('scroll-top-implementations')->self();
            $this->dispatch('scroll-top-batches')->self();
            $this->dispatch('scroll-top-beneficiaries')->self();
        } elseif ($prop === 'calendarEnd') {
            $format = Essential::extract_date($this->calendarEnd, false);
            if ($format !== 'm/d/Y') {
                $this->calendarEnd = $this->defaultEnd;
                return;
            }

            $choosenDate = Carbon::createFromFormat('m/d/Y', $this->calendarEnd)->format('Y-m-d');
            $currentTime = now()->endOfDay()->format('H:i:s');

            $this->end = $choosenDate . ' ' . $currentTime;

            if (strtotime($this->start) > strtotime($this->end)) {
                $start = Carbon::parse($this->end)->subMonth()->startOfDay()->format('Y-m-d H:i:s');
                $this->start = $start;
                $this->calendarStart = Carbon::parse($this->start)->format('m/d/Y');
            }

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
            $this->dispatch('scroll-top-implementations')->self();
            $this->dispatch('scroll-top-batches')->self();
            $this->dispatch('scroll-top-beneficiaries')->self();
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

        $this->selectedImplementationRow = -1;
        $this->selectedBatchRow = -1;
        $this->selectedBeneficiaryRow = -1;

        $this->showAlert = true;
        $this->alertMessage = 'Successfully deleted the project!';
        $this->dispatch('show-alert');
        $this->dispatch('init-reload')->self();
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

        $this->selectedBatchRow = -1;
        $this->selectedBeneficiaryRow = -1;

        $this->showAlert = true;
        $this->alertMessage = 'Batches successfully assigned!';
        $this->dispatch('show-alert');
        $this->dispatch('init-reload')->self();
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

    #[On('refreshAfterOpening')]
    public function refreshAfterOpening($message)
    {
        unset($this->batches);
        unset($this->accessCode);

        $this->showAlert = true;
        $this->alertMessage = $message;
        $this->dispatch('show-alert');
        $this->dispatch('init-reload')->self();
    }

    #[Computed]
    public function settings()
    {
        return UserSetting::where('users_id', Auth::id())
            ->pluck('value', 'key');
    }

    public function mount()
    {
        $user = Auth::user();
        if ($user->user_type !== 'focal') {
            $this->redirectIntended();
        }

        # Setting default dates in the datepicker
        $this->start = now()->startOfYear()->format('Y-m-d H:i:s');
        $this->end = now()->endOfDay()->format('Y-m-d H:i:s');

        $this->calendarStart = Carbon::parse($this->start)->format('m/d/Y');
        $this->calendarEnd = Carbon::parse($this->end)->format('m/d/Y');

        $this->defaultStart = $this->calendarStart;
        $this->defaultEnd = $this->calendarEnd;

        $this->export_start = $this->start;
        $this->export_end = $this->end;

        $this->defaultExportStart = $this->defaultStart;
        $this->defaultExportEnd = $this->defaultEnd;

        if ($this->exportBatches->isEmpty()) {
            $this->exportBatchId = null;
            $this->currentExportBatch = 'None';
        } else {
            $this->exportBatchId = encrypt($this->exportBatches[0]->id);
            $this->currentExportBatch = ($this->exportBatches[0]->sector_title ?? $this->exportBatches[0]->barangay_name) . ' / ' . $this->exportBatches[0]->batch_num;
        }
    }

    public function render()
    {
        $this->projectNumPrefix = $this->settings->get('project_number_prefix', config('settings.project_number_prefix'));
        $this->duplicationThreshold = intval($this->settings->get('duplication_threshold', config('settings.duplication_threshold')));
        return view('livewire.focal.implementations');
    }
}
