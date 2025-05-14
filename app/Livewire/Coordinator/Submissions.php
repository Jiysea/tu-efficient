<?php

namespace App\Livewire\Coordinator;

use App\Models\Archive;
use App\Models\Assignment;
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
use DB;
use Hash;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\QueryException;
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
    public array $beneficiaryIds = [];
    #[Locked]
    public $batchId;
    #[Locked]
    public $passedCredentialId;
    #[Locked]
    public $exportBatchId;
    #[Locked]
    public $batchNumPrefix;
    #[Locked]
    public $duplicationThreshold;
    #[Locked]
    public $defaultArchive;
    #[Locked]
    public $defaultShowDuplicates;
    public $alerts = [];
    public $addBeneficiariesModal = false;
    public $editBeneficiaryModal = false;
    public $deleteBeneficiaryModal = false;
    public $promptMultiDeleteModal = false;
    public $viewCredentialsModal = false;
    public $signingBeneficiariesModal = false;
    public $approveSubmissionModal = false;
    public $importFileModal = false;
    public $showExportModal = false;

    # --------------------------------------------------------------------------

    public $defaultBeneficiaries_on_page = 30;
    public $beneficiaries_on_page = 30;
    public $selectedBatchRow = -1;
    public array $selectedBeneficiaryRow = [];
    public $anchorBeneficiaryKey = -1;
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
        'annex_d' => true,
        'annex_e1' => true,
        'annex_e2' => true,
        'annex_j2' => true,
        'annex_l' => true,
        'annex_l_sign' => true,
    ]; # annex_d, annex_e1, annex_e2, annex_j2, annex_l, annex_l_sign
    public $currentExportBatch;
    public $searchExportBatch;

    # ------------------------------------------

    public $start;
    public $end;
    public $calendarStart;
    public $calendarEnd;
    public $defaultStart;
    public $defaultEnd;
    public $approvalStatuses = [
        'approved' => true,
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
            'exportBatchId' => [
                'required'
            ],
        ];
    }

    public function messages()
    {
        return [
            'password_approve.required' => 'This field is required.',
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
            'exportBatchId.required' => 'This field is required.',
        ]);

        $batch = $this->exportBatch;

        $spreadsheet = new Spreadsheet();

        $writer = null;
        $fileName = null;

        if ($this->exportFormat === 'xlsx') {
            # Types of Annexes: annex_d, annex_e1, annex_e2, annex_j2, annex_l, annex_l_sign
            $spreadsheet = Annex::export($spreadsheet, $batch, $this->exportType, $this->exportFormat);
            $writer = new Xlsx($spreadsheet);
            $fileName = 'TUPAD Annex.xlsx';
        } elseif ($this->exportFormat === 'csv') {
            # Types of Annexes: annex_d, annex_e1, annex_e2, annex_j2, annex_l, annex_l_sign
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
            ->join('implementations', 'batches.implementations_id', '=', 'implementations.id')
            ->join('assignments', 'batches.id', '=', 'assignments.batches_id')
            ->where('assignments.users_id', Auth::id())
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
                'batches.*',
                'implementations.status as implementation_status'
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
            $this->currentExportBatch = ($this->exportBatches[0]->sector_title ?? $this->exportBatches[0]->barangay_name) . ' / ' . $this->exportBatches[0]->batch_num;
        }

    }

    # ------------------------------------------------------------------------------------------------------------------

    public function viewSignBeneficiary()
    {
        $this->signingBeneficiariesModal = true;
    }

    public function selectBatchRow($key, $encryptedId)
    {
        $this->selectedBatchRow = $key;
        $this->batchId = $encryptedId;
        $this->resetBeneficiary();
        $this->beneficiaries_on_page = $this->defaultBeneficiaries_on_page;

        $this->dispatch('init-reload')->self();
        $this->dispatch('scroll-to-beneficiaries')->self();
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
        unset($this->batches);
        unset($this->beneficiaries);
        $this->dispatch('init-reload')->self();
    }

    public function deleteBeneficiary()
    {
        DB::transaction(function () {
            try {
                # Eager loading with batches would help with preventing Deadlocks
                $beneficiary = Beneficiary::with([
                    'batch' => function ($query) {
                        $query->lockForUpdate();
                    }
                ])->lockForUpdate()->findOrFail($this->beneficiaryId ? decrypt($this->beneficiaryId) : null);
                $batch = $beneficiary->batch;
                $implementation = Implementation::findOrFail($batch->implementations_id);
                $this->authorize('delete-beneficiary-coordinator', $beneficiary);
                $credentials = Credential::where('beneficiaries_id', decrypt($this->beneficiaryId))
                    ->get();

                if ($this->defaultArchive) {

                    # Archive their credentials first
                    foreach ($credentials as $credential) {
                        Archive::create([
                            'last_id' => $credential->id,
                            'source_table' => 'credentials',
                            'data' => collect($credential->toArray())->map(function ($value, $key) {
                                if (in_array($key, ['created_at', 'updated_at']) && $value) {
                                    return Carbon::parse($value)->setTimezone(config('app.timezone'))->toDateTimeString();
                                }
                                return $value;
                            })->toArray(),
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
                        'data' => collect($beneficiary->makeHidden('batch')->toArray())->map(function ($value, $key) {
                            if (in_array($key, ['created_at', 'updated_at']) && $value) {
                                return Carbon::parse($value)->setTimezone(config('app.timezone'))->toDateTimeString();
                            }
                            return $value;
                        })->toArray(),
                        'archived_at' => now()
                    ]);
                    $beneficiary->deleteOrFail();

                    if (mb_strtolower($beneficiary->beneficiary_type, "UTF-8") === 'underemployed') {
                        LogIt::set_archive_beneficiary($implementation, $batch, $beneficiary, auth()->user());
                    }

                    $this->dispatch('alertNotification', type: 'beneficiary', message: 'Successfully archived a beneficiary', color: 'blue');
                }

                # otherwise, we could just delete it.
                else {
                    foreach ($credentials as $credential) {
                        if (isset($credential->image_file_path) && Storage::exists($credential->image_file_path)) {
                            Storage::delete($credential->image_file_path);
                        }
                        $credential->deleteOrFail();
                        if ($credential->for_duplicates === 'yes') {
                            LogIt::set_delete_beneficiary_special_case($implementation, $batch, $beneficiary, $credential, auth()->user());
                        }
                    }

                    $beneficiary->deleteOrFail();

                    if (mb_strtolower($beneficiary->beneficiary_type, "UTF-8") === 'underemployed') {
                        LogIt::set_delete_beneficiary($implementation, $batch, $beneficiary, auth()->user());
                    }

                    $this->dispatch('alertNotification', type: 'beneficiary', message: 'Successfully deleted a beneficiary', color: 'blue');
                }
            } catch (AuthorizationException $e) {
                DB::rollBack();
                LogIt::set_log_exception('An unauthorized action has been made while removing a beneficiary. Error ' . $e->getCode(), auth()->user(), $e->getTrace());
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
            } finally {
                $this->js('deleteBeneficiaryModal = false;');
                $this->resetBeneficiary();
                $this->dispatch('init-reload')->self();
            }
        }, 5);
    }

    public function removeBeneficiaries()
    {
        $defaultArchive = $this->defaultArchive;
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
                        $this->authorize('delete-beneficiary-coordinator', $beneficiary);

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

                    $this->dispatch('alertNotification', type: 'beneficiary', message: 'Successfully deleted ' . ($count > 1 ? ($count . ' beneficiaries') : 'a beneficiary'), color: 'blue');

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
                        $this->authorize('delete-beneficiary-coordinator', $beneficiary);

                        $credentials = Credential::where('beneficiaries_id', $id ? decrypt($id) : null)
                            ->lockForUpdate()
                            ->get();

                        # Archive their credentials first
                        foreach ($credentials as $credential) {

                            Archive::create([
                                'last_id' => $credential->id,
                                'source_table' => 'credentials',
                                'data' => collect($credential->toArray())->map(function ($value, $key) {
                                    if (in_array($key, ['created_at', 'updated_at']) && $value) {
                                        return Carbon::parse($value)->setTimezone(config('app.timezone'))->toDateTimeString();
                                    }
                                    return $value;
                                })->toArray(),
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
                            'data' => collect($beneficiary->makeHidden('batch')->toArray())->map(function ($value, $key) {
                                if (in_array($key, ['created_at', 'updated_at']) && $value) {
                                    return Carbon::parse($value)->setTimezone(config('app.timezone'))->toDateTimeString();
                                }
                                return $value;
                            })->toArray(),
                            'archived_at' => now()
                        ]);
                        $beneficiary->deleteOrFail();

                        if (mb_strtolower($beneficiary->beneficiary_type, "UTF-8") === 'underemployed') {
                            LogIt::set_archive_beneficiary($implementation, $batch, $beneficiary, auth()->user());
                        }
                    }

                    $this->dispatch('alertNotification', type: 'beneficiary', message: 'Successfully archived ' . ($count > 1 ? ($count . ' beneficiaries') : 'a beneficiary'), color: 'blue');

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
    public function identity()
    {
        if ($this->credentials->isNotEmpty()) {
            foreach ($this->credentials as $credential) {
                if ($credential->for_duplicates === 'no') {
                    return $credential->image_file_path;
                }
            }
        }

        return null;
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

    #[Computed]
    public function getIdType()
    {
        $type_of_id = null;

        if ($this->beneficiaryId) {

            if (str_contains($this->beneficiary?->type_of_id, 'PWD')) {
                $type_of_id = 'PWD ID';
            } else if (str_contains($this->beneficiary?->type_of_id, 'COMELEC')) {
                $type_of_id = 'Voter\'s ID';
            } else if (str_contains($this->beneficiary?->type_of_id, 'PhilID')) {
                $type_of_id = 'PhilID';
            } else if (str_contains($this->beneficiary?->type_of_id, '4Ps')) {
                $type_of_id = '4Ps ID';
            } else if (str_contains($this->beneficiary?->type_of_id, 'IBP')) {
                $type_of_id = 'IBP ID';
            } else {
                $type_of_id = $this->beneficiary?->type_of_id;
            }

        }

        return $type_of_id;
    }

    #[Computed]
    public function implementation()
    {
        return Implementation::find($this->batch?->implementations_id);
    }

    #[Computed]
    public function batch()
    {
        return Batch::find($this->batchId ? decrypt($this->batchId) : null);
    }

    #[Computed]
    public function batches()
    {
        $approvalStatuses = array_keys(array_filter($this->filter['approval_status']));
        $submissionStatuses = array_keys(array_filter($this->filter['submission_status']));

        $batches = Batch::join('implementations', 'implementations.id', '=', 'batches.implementations_id')
            ->join('assignments', 'batches.id', '=', 'assignments.batches_id')
            ->where('assignments.users_id', Auth::id())
            ->whereBetween('batches.created_at', [$this->start, $this->end])
            ->when(!empty($approvalStatuses), function ($q) use ($approvalStatuses) {
                $q->whereIn('batches.approval_status', $approvalStatuses);
            })
            ->when(!empty($submissionStatuses), function ($q) use ($submissionStatuses) {
                $q->whereIn('batches.submission_status', $submissionStatuses);
            })
            ->when(isset($this->searchBatches) && !empty($this->searchBatches), function ($query) {
                $query->where(function ($q) {
                    $q->where('batches.batch_num', 'LIKE', '%' . $this->searchBatches . '%')
                        ->orWhere('batches.sector_title', 'LIKE', '%' . $this->searchBatches . '%')
                        ->orWhere('batches.barangay_name', 'LIKE', '%' . $this->searchBatches . '%');
                });
            })
            ->select(
                [
                    'batches.id',
                    'batches.batch_num',
                    'batches.is_sectoral',
                    'batches.sector_title',
                    'batches.barangay_name',
                    'batches.approval_status',
                    'batches.submission_status',
                    'implementations.status as implementation_status'
                ]
            )
            ->groupBy([
                'batches.id',
                'batches.batch_num',
                'batches.is_sectoral',
                'batches.sector_title',
                'batches.barangay_name',
                'batches.approval_status',
                'batches.submission_status',
                'implementation_status'
            ])
            ->latest('batches.updated_at')
            ->orderBy('batches.id', 'desc')
            ->get();

        return $batches;
    }

    #[Computed]
    public function batchesNothing()
    {
        return Batch::join('implementations', 'implementations.id', '=', 'batches.implementations_id')
            ->join('assignments', 'batches.id', '=', 'assignments.batches_id')
            ->where('assignments.users_id', auth()->id())
            ->whereBetween('batches.created_at', [$this->start, $this->end])
            ->get();
    }

    #[Computed]
    public function beneficiary()
    {
        return Beneficiary::find($this->beneficiaryId ? decrypt($this->beneficiaryId) : null);
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
    public function rowColorIndicator($beneficiary, $key)
    {
        # This will be the returned value if the other "if" statements are false
        # "default" means it has neither a possible nor perfect duplicate
        $indicator = 'default';
        if (in_array($key, $this->selectedBeneficiaryRow)) {
            $indicator .= '-selected';
        }

        # Turning on show duplicates setting requires extensive memory usage
        if ($this->defaultShowDuplicates) {

            $thresholdResult = $this->isOverThreshold($beneficiary);

            # If the $thresholdResult returns an array, this will basically satisfy the condition
            if ($thresholdResult) {
                foreach ($thresholdResult as $result) {
                    $databaseBeneficiary = Beneficiary::find(decrypt($result['id']));

                    # If all the results are only possible duplicates...
                    if (!$result['is_perfect'] && $beneficiary->created_at > $databaseBeneficiary->created_at) {
                        $indicator = 'possible';
                        if (in_array($key, $this->selectedBeneficiaryRow)) {
                            $indicator .= '-selected';
                        }
                    }

                    # If one of the results is a perfect duplicate...
                    if ($result['is_perfect'] && $beneficiary->beneficiary_type === 'special case') {
                        $indicator = 'perfect';
                        if (in_array($key, $this->selectedBeneficiaryRow)) {
                            $indicator .= '-selected';
                        }
                        break; # break the loop since having a perfect duplicate has more priority than a possible one
                    }
                }
            }

        }

        # If show duplicates setting is off and the beneficiary is a special case...
        if ($beneficiary->beneficiary_type === 'special case') {
            $indicator = 'perfect';
            if (in_array($key, $this->selectedBeneficiaryRow)) {
                $indicator .= '-selected';
            }
        }

        return $indicator;
    }

    #[Computed]
    public function isOverThreshold($person)
    {
        $results = null;

        if ($this->beneficiaries?->isNotEmpty()) {
            $results = JaccardSimilarity::isOverThreshold($person, $this->duplicationThreshold / 100);
        }

        return $results;
    }

    #[Computed]
    public function credentials()
    {
        return Credential::where('beneficiaries_id', $this->beneficiaryId ? decrypt($this->beneficiaryId) : null)
            ->get();
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
            ->when(isset($this->searchBatches) && !empty($this->searchBatches), function ($query) {
                $query->where(function ($q) {
                    $q->where('batches.batch_num', 'LIKE', '%' . $this->searchBatches . '%')
                        ->orWhere('batches.sector_title', 'LIKE', '%' . $this->searchBatches . '%')
                        ->orWhere('batches.barangay_name', 'LIKE', '%' . $this->searchBatches . '%');
                });
            })
            ->count();

        return $batchesCount;
    }

    #[Computed]
    public function checkBeneficiaryCount()
    {
        return Beneficiary::where('beneficiaries.batches_id', $this->batchId ? decrypt($this->batchId) : null)
            ->count();
    }

    #[Computed]
    public function beneficiarySlots()
    {
        $batch = Batch::where('id', $this->batchId ? decrypt($this->batchId) : null)
            ->first();

        $totalSlots = $batch?->slots_allocated ?? 0;

        $totalBeneficiaries = Beneficiary::where('beneficiaries.batches_id', $batch?->id)
            ->count();

        return [
            'slots_allocated' => $totalSlots,
            'num_of_beneficiaries' => $totalBeneficiaries,
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

        DB::transaction(function () {
            try {
                $batch = Batch::lockForUpdate()->findOrFail($this->batchId ? decrypt($this->batchId) : null);
                $this->authorize('check-coordinator', $batch);

                $checkSlots = Batch::whereHas('beneficiary')
                    ->where('batches.id', $batch->id)
                    ->exists();

                if (!$checkSlots) {
                    DB::rollBack();
                    $this->dispatch('alertNotification', type: null, message: 'This batch should have at least one beneficiary', color: 'red');
                    return;
                }

                if ($batch->approval_status === 'approved') {
                    DB::rollBack();
                    $this->dispatch('alertNotification', type: null, message: 'This batch is already approved', color: 'red');
                    return;
                }

                if ($batch->submission_status === 'revalidate' || $batch->submission_status === 'encoding') {
                    DB::rollBack();
                    $this->dispatch('alertNotification', type: null, message: 'This batch should be submitted first', color: 'red');
                    return;
                }

                $batch->approval_status = 'approved';
                $batch->submission_status = 'submitted';
                $batch->save();
                LogIt::set_approve_batch($batch, auth()->user());
                $this->dispatch('alertNotification', type: null, message: 'Successfully approved the batch submission', color: 'blue');
            } catch (AuthorizationException $e) {
                DB::rollBack();
                LogIt::set_log_exception('An unauthorized action has been made while adding a beneficiary. Error ' . $e->getCode(), auth()->user(), $e->getTrace());
                $this->dispatch('alertNotification', type: null, message: $e->getMessage(), color: 'red');
            } catch (QueryException $e) {
                DB::rollBack();

                # Deadlock & LockWaitTimeoutException
                if ($e->getCode() === '1213' || $e->getCode() === '1205') {
                    $this->dispatch('alertNotification', type: null, message: 'Another user is modifying the record. Please try again. Error ' . $e->getCode(), color: 'red');
                } else {
                    LogIt::set_log_exception('An error has occured during execution. Error ' . $e->getCode(), auth()->user(), $e->getTrace());
                    $this->dispatch('alertNotification', type: null, message: 'An error has occured during execution. Error ' . $e->getCode(), color: 'red');
                }
            } catch (\Throwable $e) {
                DB::rollBack();
                LogIt::set_log_exception('An error has occured during execution. Error ' . $e->getCode(), auth()->user(), $e->getTrace());
                $this->dispatch('alertNotification', type: null, message: 'An error has occured during execution. Error ' . $e->getCode(), color: 'red');
            } finally {
                $this->resetPassword();
                $this->approveSubmissionModal = false;
                unset($this->batches);
            }
        }, 5);

    }

    #[Computed]
    public function globalSettings()
    {
        return UserSetting::join('users', 'users.id', '=', 'user_settings.users_id')
            ->where('users.user_type', 'focal')
            ->pluck('user_settings.value', 'user_settings.key');
    }

    #[Computed]
    public function personalSettings()
    {
        return UserSetting::where('users_id', auth()->id())
            ->pluck('value', 'key');
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

            $this->beneficiaries_on_page = $this->defaultBeneficiaries_on_page;

            if ($this->batches->isNotEmpty()) {
                $this->batchId = encrypt($this->batches[0]->id);
            } else {
                $this->batchId = null;
                $this->searchBatches = null;
                $this->searchBeneficiaries = null;
            }

            $this->resetBatch();
            $this->resetBeneficiary();

            $this->dispatch('init-reload')->self();
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

            $this->beneficiaries_on_page = $this->defaultBeneficiaries_on_page;

            if ($this->batches->isNotEmpty()) {
                $this->batchId = encrypt($this->batches[0]->id);
            } else {
                $this->batchId = null;
                $this->searchBatches = null;
                $this->searchBeneficiaries = null;
            }

            $this->resetBatch();
            $this->resetBeneficiary();

            $this->dispatch('init-reload')->self();
            $this->dispatch('scroll-top-beneficiaries')->self();
        }
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
            $this->alerts[] = [
                'message' => 'Finished processing your import. Head to the Import tab.',
                'id' => uniqid(),
                'color' => 'blue'
            ];
        }
        $this->dispatch('init-reload')->self();
    }

    #[On('alertNotification')]
    public function alertNotification($type = 'beneficiary', $message, $color)
    {
        $dateTimeFromEnd = $this->end;
        $value = substr($dateTimeFromEnd, 0, 10);

        $choosenDate = date('Y-m-d', strtotime($value));
        $currentTime = date('H:i:s', strtotime(now()));
        $this->end = $choosenDate . ' ' . $currentTime;

        if ($type === 'beneficiary') {
            $this->resetBeneficiary();
        }

        $this->alerts[] = [
            'message' => $message,
            'id' => uniqid(),
            'color' => $color
        ];

        $this->dispatch('init-reload')->self();
    }

    public function removeAlert($id)
    {
        $this->alerts = array_filter($this->alerts, function ($alert) use ($id) {
            return $alert['id'] !== $id;
        });
    }

    public function resetPassword()
    {
        $this->reset('password_approve');
        $this->resetValidation(['password_approve']);
    }

    public function resetBatch()
    {
        $this->dispatch('scroll-to-beneficiaries')->self();
        $this->reset('selectedBatchRow');
        unset($this->batches);
    }

    public function resetBeneficiary()
    {
        $this->dispatch('scroll-to-beneficiaries')->self();
        $this->reset('beneficiaryId', 'beneficiaryIds', 'selectedBeneficiaryRow', 'anchorBeneficiaryKey', 'searchBeneficiaries');
        unset($this->beneficiaries);
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

        $this->defaultBeneficiaries_on_page = 30;

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
            $this->currentExportBatch = ($this->exportBatches[0]->sector_title ?? $this->exportBatches[0]->barangay_name) . ' / ' . $this->exportBatches[0]->batch_num;
        }

    }

    public function render()
    {
        $this->batchNumPrefix = $this->globalSettings->get('batch_num_prefix', config('settings.batch_number_prefix'));
        $this->duplicationThreshold = intval($this->globalSettings->get('duplication_threshold', config('settings.duplication_threshold')));
        $this->defaultArchive = intval($this->personalSettings->get('default_archive', config('settings.default_archive')));
        $this->defaultShowDuplicates = intval($this->personalSettings->get('default_show_duplicates', config('settings.default_show_duplicates')));
        return view('livewire.coordinator.submissions');
    }
}
