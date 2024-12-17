<?php

namespace App\Livewire\Focal;

use App\Models\Archive;
use App\Models\Batch;
use App\Models\Beneficiary;
use App\Models\Credential;
use App\Models\Implementation;
use App\Models\UserSetting;
use App\Services\Annex;
use App\Services\Essential;
use App\Services\JaccardSimilarity;
use App\Services\LogIt;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\QueryException;
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
use Storage;

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
    #[Locked]
    public array $beneficiaryIds = [];
    #[Locked]
    public $exportBatchId;
    public $projectNumPrefix;
    public $duplicationThreshold;
    public $allSimilarityResults;
    public $alerts = [];
    # ------------------------------------------

    public $createProjectModal = false;
    public $viewProjectModal = false;
    public $assignBatchesModal = false;
    public $viewBatchModal = false;
    public $addBeneficiariesModal = false;
    public $viewBeneficiaryModal = false;
    public $importFileModal = false;
    public $showExportModal = false;
    public $promptMultiDeleteModal = false;

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
    public array $selectedBeneficiaryRow = [];
    public $anchorBeneficiaryKey = -1;
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

    public function selectImplementationRow($key, $encryptedId)
    {
        if ($key === $this->selectedImplementationRow) {
            $this->resetImplementation();
            unset($this->implementations);
            unset($this->batches);
            unset($this->beneficiaries);
        } else {
            $this->selectedImplementationRow = $key;
            $this->implementationId = $encryptedId;
        }

        $this->beneficiaries_on_page = 15;
        $this->resetBatch();
        $this->resetBeneficiary();

        $this->dispatch('init-reload')->self();

    }

    public function viewImplementation($key, $encryptedId)
    {
        $this->selectedImplementationRow = $key;
        $this->implementationId = $encryptedId;

        $this->beneficiaries_on_page = 15;
        $this->resetBatch();
        $this->resetBeneficiary();

        $this->dispatch('init-reload')->self();
        $this->viewProjectModal = true;
    }

    public function selectBatchRow($key, $encryptedId)
    {
        if ($key === $this->selectedBatchRow) {
            $this->resetBatch();
            unset($this->implementations);
            unset($this->batches);
            unset($this->beneficiaries);
        } else {
            $this->selectedBatchRow = $key;
            $this->batchId = $encryptedId;
        }

        $this->beneficiaries_on_page = 15;
        $this->resetBeneficiary();

        $this->dispatch('init-reload')->self();
    }

    public function viewBatch($key, $encryptedId)
    {
        $this->selectedBatchRow = $key;
        $this->batchId = $encryptedId;

        $this->beneficiaries_on_page = 15;
        $this->resetBeneficiary();

        $this->dispatch('init-reload')->self();
        $this->viewBatchModal = true;
    }

    public function selectBeneficiaryRow($key, $encryptedId, $type = 'row-based')
    {
        if ($type === 'row-based') {
            if (!in_array($key, $this->selectedBeneficiaryRow) || count($this->selectedBeneficiaryRow) !== 1) {
                $this->selectedBeneficiaryRow = [$key => $key];
                $this->beneficiaryIds = [$key => $encryptedId];
                $this->anchorBeneficiaryKey = $key;
                $this->beneficiaryId = $encryptedId;
            } else {
                $this->resetBeneficiary();
            }
        } elseif ($type === 'checkbox') {
            if (!in_array($key, $this->selectedBeneficiaryRow)) {
                $this->selectedBeneficiaryRow[$key] = $key;
                $this->beneficiaryIds[$key] = $encryptedId;
                $this->anchorBeneficiaryKey = $key;
                $this->beneficiaryId = $encryptedId;
            } else {
                unset($this->selectedBeneficiaryRow[$key], $this->beneficiaryIds[$key]);
            }
        } else {
            $this->resetBeneficiary();
        }
        unset($this->implementations);
        unset($this->batches);
        $this->dispatch('init-reload')->self();
    }

    # Used to Multi-Select rows from the beneficiary table
    public function selectShiftBeneficiary($key, $encryptedId)
    {
        # Checks if there are already selected `keys` to proceed with multi-select
        if (count($this->selectedBeneficiaryRow) > 0) {

            # First, we get the lowest and highest `key` value among the selected rows
            $lowKey = min($this->selectedBeneficiaryRow);
            $highKey = max($this->selectedBeneficiaryRow);
            $centerKey = $this->anchorBeneficiaryKey;

            # Temporarily store an instance of selected rows for later use
            $tempSelectedRows = $this->selectedBeneficiaryRow;

            # Empty the selected rows or basically reset them before we do the multi-select
            # in order to avoid unexpected results
            $this->selectedBeneficiaryRow = [];
            $this->beneficiaryIds = [];

            # When selecting a row in the table, there are 7 possibilities but before that here are some
            # key points to note:
            # - When you select a row, the system will store the `key` and the `id` of that row
            # - The `id` is basically the beneficiary model ID
            # - After storing the `key`, it will be used to indicate which rows were selected on the front-end
            # - But when it comes to multi-selection, there would be more than 1 `key` stored
            # - Basically, the `key`s will be stored in an Array (same goes for the `id`s)
            # - The problem here would be to determine how to select rows closely similar to
            #   the traditional multi-selection
            # - That's why I get the `min` and `max` from the selected rows and
            #   use the `key` as the Center
            #
            # Now for the 7 possibilities, this is based on traditional shift-click selection behavior:

            # 1.) When the selected row `key` is LOWER THAN the lowest selected row
            if ($key < $lowKey) {

                foreach (range($key, $centerKey) as $num) {
                    $this->selectedBeneficiaryRow[$num] = $num;
                    $this->beneficiaryIds[$num] = encrypt($this->beneficiaries[$num]->id);
                }
            }

            # 2.) When the selected row `key` is GREATER THAN the lowest selected row 
            #       but LOWER THAN highest selected row
            elseif ($key > $lowKey && $key < $centerKey && $key < $highKey) {

                foreach (range($key, $centerKey) as $num) {
                    $this->selectedBeneficiaryRow[$num] = $num;
                    $this->beneficiaryIds[$num] = encrypt($this->beneficiaries[$num]->id);
                }
            }

            # 3.) When the selected row `key` is EQUAL TO the lowest selected row 
            #       & LOWER THAN the highest selected row
            elseif ($key > $lowKey && $key > $centerKey && $key < $highKey) {

                foreach (range($centerKey, $key) as $num) {
                    $this->selectedBeneficiaryRow[$num] = $num;
                    $this->beneficiaryIds[$num] = encrypt($this->beneficiaries[$num]->id);
                }
            }

            # 4.) When the selected row `key` is GREATER THAN the lowest selected row 
            #       & highest selected row
            elseif ($key > $highKey) {

                foreach (range($centerKey, $key) as $num) {
                    $this->selectedBeneficiaryRow[$num] = $num;
                    $this->beneficiaryIds[$num] = encrypt($this->beneficiaries[$num]->id);
                }
            }

            # 5.) When the selected row `key` is EQUAL TO the highest selected row
            #       but there are multiple selected rows
            elseif (($key === $centerKey) && count($tempSelectedRows) > 1) {
                $this->selectedBeneficiaryRow = [$key => $key];
                $this->beneficiaryIds = [$key => $encryptedId];
                $this->anchorBeneficiaryKey = $key;
                $this->beneficiaryId = $encryptedId;
            }

            # 6.) When the selected row `key` is EQUAL TO the highest selected row
            #       but there is only one selected row
            elseif (($key === $centerKey) && count($tempSelectedRows) === 1) {
                $this->resetBeneficiary();
            }
        }

        # Otherwise, it will just select the single row if there are no selected rows yet
        else {
            $this->selectedBeneficiaryRow = [$key => $key];
            $this->beneficiaryIds = [$key => $encryptedId];
            $this->anchorBeneficiaryKey = $key;
            $this->beneficiaryId = $encryptedId;
        }

        # Finally, we bust the cache just to make sure the rows were updated
        unset($this->implementations);
        unset($this->batches);
        unset($this->beneficiaries);
        $this->dispatch('init-reload')->self();
    }

    public function viewBeneficiary($key, $encryptedId)
    {
        $this->selectedBeneficiaryRow = [$key => $key];
        $this->beneficiaryIds = [$key => $encryptedId];
        $this->anchorBeneficiaryKey = $key;
        $this->beneficiaryId = $encryptedId;

        $this->dispatch('init-reload')->self();
        $this->viewBeneficiaryModal = true;
    }

    public function removeBeneficiaries($defaultArchive)
    {
        $defaultArchive = decrypt($defaultArchive);
        $count = count($this->selectedBeneficiaryRow);
        DB::transaction(function () use ($defaultArchive, $count) {
            try {
                if (!$defaultArchive) {

                    foreach ($this->beneficiaryIds as $key => $id) {

                        # Eager loading with batches would help with preventing Deadlocks
                        $beneficiary = Beneficiary::with([
                            'batch' => function ($q) {
                                $q->lockForUpdate();
                            }
                        ])->lockForUpdate()->findOrFail($this->beneficiaryId ? decrypt($this->beneficiaryId) : null);
                        $batch = $beneficiary->batch;
                        $implementation = Implementation::findOrFail($batch->implementations_id);
                        $this->authorize('delete-beneficiary-focal', $beneficiary);

                        $credentials = Credential::where('beneficiaries_id', $id ? decrypt($id) : null)
                            ->lockForUpdate()
                            ->get();

                        foreach ($credentials as $credential) {
                            if (isset($credential->image_file_path) && Storage::exists($credential->image_file_path)) {
                                $credential->deleteOrFail();
                            }
                            if ($credential->for_duplicates === 'yes') {
                                LogIt::set_delete_beneficiary_special_case($implementation, $batch, $beneficiary, $credential, auth()->user());
                            }
                        }

                        $beneficiary->deleteOrFail();
                        if (mb_strtolower($beneficiary->beneficiary_type, "UTF-8") === 'underemployed') {
                            LogIt::set_delete_beneficiary($implementation, $batch, $beneficiary, auth()->user());
                        }
                    }

                    $this->dispatch('alertNotification', type: 'beneficiary', message: 'Successfully deleted ' . ($count > 1 ? ($count . ' beneficiaries') : 'a beneficiary'), color: 'indigo');

                } elseif ($defaultArchive) {

                    foreach ($this->beneficiaryIds as $key => $id) {

                        # Eager loading with batches would help with preventing Deadlocks
                        $beneficiary = Beneficiary::with([
                            'batch' => function ($query) {
                                $query->lockForUpdate();
                            }
                        ])->lockForUpdate()->findOrFail($id ? decrypt($id) : null);
                        $batch = $beneficiary->batch;
                        $implementation = Implementation::findOrFail($batch->implementations_id);
                        $this->authorize('delete-beneficiary-focal', $beneficiary);

                        $credentials = Credential::where('beneficiaries_id', $id ? decrypt($id) : null)
                            ->lockForUpdate()
                            ->get();

                        # Archive their credentials first
                        foreach ($credentials as $credential) {

                            Archive::create([
                                'last_id' => $credential->id,
                                'source_table' => 'credentials',
                                'data' => $credential->toArray(),
                                'archived_at' => now()
                            ]);
                            $credential->deleteOrFail();
                            if ($credential->for_duplicates === 'yes') {
                                LogIt::set_archive_beneficiary_special_case($implementation, $batch, $beneficiary, $credential, auth()->user());
                            }
                        }

                        # then archive the Beneficiary record
                        Archive::create([
                            'last_id' => $beneficiary->id,
                            'source_table' => 'beneficiaries',
                            'data' => $beneficiary->makeHidden('batch')->toArray(),
                            'archived_at' => now()
                        ]);
                        $beneficiary->deleteOrFail();

                        if (mb_strtolower($beneficiary->beneficiary_type, "UTF-8") === 'underemployed') {
                            LogIt::set_archive_beneficiary($implementation, $batch, $beneficiary, auth()->user());
                        }
                    }

                    $this->dispatch('alertNotification', type: 'beneficiary', message: 'Successfully archived ' . ($count > 1 ? ($count . ' beneficiaries') : 'a beneficiary'), color: 'indigo');

                }

                $this->resetBeneficiary();
                $this->dispatch('init-reload')->self();

            } catch (AuthorizationException $e) {
                DB::rollBack();
                LogIt::set_log_exception('An unauthorized action has been made while removing beneficiaries. Error ' . $e->getCode(), auth()->user(), $e->getTrace());
                $this->dispatch('alertNotification', type: 'beneficiary', message: $e->getMessage(), color: 'red');
            } catch (QueryException $e) {
                DB::rollBack();

                # Deadlock & LockWaitTimeoutException
                if ($e->getCode() === '1213' || $e->getCode() === '1205') {
                    // LogIt::set_log_exception('Deadlock has occured while deleting a beneficiary', auth()->user(), $e->getTrace());
                    $this->dispatch('alertNotification', type: 'beneficiary', message: 'Another user is modifying the record. Please try again. Error ' . $e->getCode(), color: 'red');
                } else {
                    LogIt::set_log_exception('An error has occured during execution. Error ' . $e->getCode(), auth()->user(), $e->getTrace());
                    $this->dispatch('alertNotification', type: 'beneficiary', message: 'An error has occured during execution. Error ' . $e->getCode(), color: 'red');
                }
            } catch (\Exception $e) {
                DB::rollBack();
                LogIt::set_log_exception('An error has occured during execution. Error ' . $e->getCode(), auth()->user(), $e->getTrace());
                $this->dispatch('alertNotification', type: 'beneficiary', message: 'An error has occured during execution. Error ' . $e->getCode(), color: 'red');
            }
        }, 5);
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

    // #[On('import-success-beneficiaries')]
    // public function importSuccessBeneficiaries($count)
    // {
    //     $dateTimeFromEnd = $this->end;
    //     $value = substr($dateTimeFromEnd, 0, 10);

    //     $choosenDate = date('Y-m-d', strtotime($value));
    //     $currentTime = date('H:i:s', strtotime(now()));
    //     $this->end = $choosenDate . ' ' . $currentTime;

    //     $this->selectedBeneficiaryRow = [];

    //     $this->showAlert = true;
    //     $this->alertMessage = 'Imported ' . $count . ' beneficiaries to the database.';
    //     $this->dispatch('show-alert');
    //     $this->dispatch('init-reload')->self();
    // }

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

    # This will listen to dispatched events that are `Create, Update, Delete, and Error`
    # actions from modals in order to refresh the table rows and also display
    # an alert/notification bar.
    #[On('alertNotification')]
    public function alertNotification($type = null, $message, $color)
    {
        $dateTimeFromEnd = $this->end;
        $value = substr($dateTimeFromEnd, 0, 10);

        $choosenDate = date('Y-m-d', strtotime($value));
        $currentTime = date('H:i:s', strtotime(now()));
        $this->end = $choosenDate . ' ' . $currentTime;

        # These will help refreshing the table rows in order after executing an action.
        # ------------------
        # The 'modify' ones are slightly different because they have to retain its data
        # because the user are 'viewing' them as supposed that they shouldn't `disappear`.
        if ($type === 'implementation') {
            $this->resetImplementation();
            $this->resetBatch();
            $this->resetBeneficiary();
        } elseif ($type === 'batch') {
            $this->resetBatch();
            $this->resetBeneficiary();
        } elseif ($type === 'beneficiary') {
            $this->resetBeneficiary();
        } elseif ($type === 'implementation-modify') {
            $this->resetBatch();
            $this->resetBeneficiary();
        } elseif ($type === 'batch-modify') {
            $this->resetBeneficiary();
        } elseif ($type === 'beneficiary-modify') {
            # Nothing to reset...
        }

        $this->alerts[] = [
            'message' => $message,
            'id' => uniqid(),
            'color' => $color
        ];

        $this->dispatch('init-reload')->self();
    }

    # It's a Livewire `Hook` for properties so the system can take action
    # when a specific property has updated its state. 
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

            $this->resetImplementation();
            $this->resetBatch();
            $this->resetBeneficiary();

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

            $this->resetImplementation();
            $this->resetBatch();
            $this->resetBeneficiary();

            $this->dispatch('init-reload')->self();
            $this->dispatch('scroll-top-implementations')->self();
            $this->dispatch('scroll-top-batches')->self();
            $this->dispatch('scroll-top-beneficiaries')->self();
        }
    }

    public function removeAlert($id)
    {
        $this->alerts = array_filter($this->alerts, function ($alert) use ($id) {
            return $alert['id'] !== $id;
        });
    }

    #[Computed]
    public function settings()
    {
        return UserSetting::where('users_id', Auth::id())
            ->pluck('value', 'key');
    }

    public function resetImplementation()
    {
        $this->reset('selectedImplementationRow', 'implementationId');
        unset($this->implementations);
    }

    public function resetBatch()
    {
        $this->reset('selectedBatchRow', 'batchId');
        unset($this->batches);
    }

    public function resetBeneficiary()
    {
        $this->reset('selectedBeneficiaryRow', 'beneficiaryId', 'beneficiaryIds', 'anchorBeneficiaryKey');
        unset($this->beneficiaries);
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
