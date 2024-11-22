<?php

namespace App\Livewire\Focal\Implementations;

use App\Jobs\ProcessImportSimilarity;
use App\Models\Batch as Batches;
use App\Models\Beneficiary;
use App\Models\Implementation;
use App\Models\UserSetting;
use App\Services\Annex;
use App\Services\AnnexDGenerator;
use App\Services\JaccardSimilarity;
use App\Services\MoneyFormat;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Reactive;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Throwable;

class ImportFileModal extends Component
{
    use WithFileUploads;

    #[Reactive]
    #[Locked]
    public $batchId;
    #[Locked]
    public $errorId;
    public $selectedSheetIndex = null;
    public $duplicationThreshold;
    public $maximumIncome;

    # -----------------------------------

    public $step = 1;
    public $errorPreviewModal = false;
    public $downloadSampleModal = false;

    # ----------------------------------------

    public $cachedResults = [];
    public $cachedExpiration;
    public array $successResults = [];
    public array $perfectResults = [];
    public array $errorResults = [];
    public array $similarityResults = [];
    public array $ineligibleResults = [];

    # -----------------------------------

    #[Locked]
    public $jobsBatchId;
    public $importing = false;
    public $importFinished = false;
    public $importFailed = false;

    # -----------------------------------

    #[Validate]
    public $file_path;
    #[Validate]
    public $slots_allocated;

    # -----------------------------------

    public function rules()
    {
        return [
            'file_path' => 'required|file|mimes:xlsx,csv',
            'slots_allocated' => [
                'required',
                'integer',
                'gte:0',
                'min:1',
                'lte:' . $this->batch->slots_allocated,
            ],
        ];
    }

    public function messages()
    {
        return [
            'file_path.required' => 'You need to upload a .xlsx or .csv file to proceed.',
            'file_path.file' => 'This should be a valid file.',
            'file_path.mimes' => 'The file should be in .xlsx or .csv format.',
            'slots_allocated.required' => 'This field is required.',
            'slots_allocated.integer' => 'This field should be a valid number.',
            'slots_allocated.min' => 'This field should be more than 0.',
            'slots_allocated.gte' => 'This field should be nonnegative.',
            'slots_allocated.lte' => 'This field should be less than total.',
        ];
    }

    public function viewError($encryptedId)
    {
        $this->errorId = $encryptedId;
        $this->errorPreviewModal = true;
    }

    public function checkError()
    {
        if ($this->getErrorBag()->has('file_path')) {
            // Reset the file input if validation fails
            $this->reset('file_path');
        }
    }

    public function backStep()
    {
        $this->step--;
    }

    public function nextStep()
    {
        $this->step++;
    }

    public function finishImport()
    {
        $this->nextStep();
    }

    public function validateFile()
    {
        $this->validateOnly('file_path');

        $filePath = $this->file_path->store('similarities');

        $this->importing = true;
        $this->importFinished = false;
        $jobBatch = Bus::batch([
            new ProcessImportSimilarity((string) $filePath, Auth::id(), $this->batch->id, $this->duplicationThreshold, $this->maximumIncome),
        ])->catch(function ($self, Throwable $e) {
            dump($e);
        })->dispatch();

        $this->jobsBatchId = $jobBatch->id;

        $this->nextStep();
    }

    public function refreshTime()
    {
        $time = now()->addMinutes(10)->addSecond();
        cache(
            [
                "importing_" . auth()->id() => $this->cachedResults,
            ],
            $time
        );

        cache(
            [
                "importing_expiration_" . auth()->id() => ($time->format('Y-m-d H:i:s'))
            ],
            $time
        );

        $this->periodicallyCheckCache();
    }

    public function importProgress()
    {
        $jobBatch = Bus::findBatch($this->jobsBatchId);

        if ($jobBatch->finished() && !$jobBatch->hasFailures()) {
            $this->importing = false;
            $this->importFinished = true;
            $this->importFailed = false;
            $this->reset('successResults', 'perfectResults', 'errorResults', 'similarityResults', 'ineligibleResults');
            $this->cachedResults = cache("importing_" . Auth::id());

            # Queries the project number of this editted beneficiary
            $project_num = Batches::join('implementations', 'implementations.id', '=', 'batches.implementations_id')
                ->where('batches.id', decrypt($this->batchId))
                ->select([
                    'implementations.project_num'
                ])
                ->first();

            if (isset($this->cachedResults) && !empty($this->cachedResults)) {
                $this->periodicallyCheckCache();
                foreach ($this->cachedResults['beneficiaries'] as $beneficiary) {

                    if ($beneficiary['success']) {
                        $this->successResults[] = $beneficiary;
                    }

                    if ($this->checkIfErrors($beneficiary['errors'])) {
                        $this->errorResults[] = $beneficiary;
                    }

                    # This will check if the algorithm has found any possible duplicates based on the given threshold
                    if (isset($beneficiary['similarities']) && is_array($beneficiary['similarities'])) {
                        # counts how many perfect duplicates encountered from the database
                        $perfectCounter = 0;
                        $isPerfectDuplicate = false;
                        $isSameImplementation = false;

                        foreach ($beneficiary['similarities'] as $result) {

                            # Queries the batch if it's pending on the possible duplicate beneficiary
                            $batch_pending = Batches::where('batch_num', $result['batch_num'])
                                ->where('approval_status', 'pending')
                                ->exists();

                            # checks if the result row is a perfect duplicate
                            if ($result['is_perfect'] && !$batch_pending) {
                                $perfectCounter++;
                                $isPerfectDuplicate = true;
                            }

                            # checks if the result row is in the same project implementation as this editted beneficiary
                            if (isset($project_num)) {
                                if ($result['project_num'] === $project_num->project_num && $result['is_perfect']) {
                                    $isSameImplementation = true;
                                }
                            }
                        }

                        # Check if there is a perfect duplicate found
                        if ($isPerfectDuplicate && $perfectCounter < 2 && !$isSameImplementation) {
                            $this->perfectResults[] = $beneficiary;
                        }

                        # check if there are already more than 2 perfect duplicates and mark this editted beneficiary as `ineligible`
                        elseif ($perfectCounter >= 2 || $isSameImplementation || ($batch_pending && $result['is_perfect'])) {
                            $this->ineligibleResults[] = $beneficiary;
                        }

                        # otherwise it'll just be a similarity
                        else {
                            $this->similarityResults[] = $beneficiary;
                        }

                    }
                }
                if (sizeof($this->successResults) > 0) {
                    // $this->dispatch(event: 'import-success-beneficiaries', count: sizeof($this->successResults));
                }
            }

            # Notify the User if it's done processing the Import
            $this->dispatch('finished-importing');
        } elseif ($jobBatch->hasFailures()) {
            $this->importing = false;
            $this->importFinished = true;
            $this->importFailed = true;
            $this->reset('successResults', 'perfectResults', 'errorResults', 'similarityResults', 'ineligibleResults', 'file_path');
        }
    }

    protected function checkIfErrors($errors)
    {
        $keys = array_keys($errors);

        foreach ($keys as $key) {
            foreach ($errors[$key] as $value) {
                if (!is_null($value)) {
                    return true; # Found a non-null value
                }
            }

        }
        return false;
    }

    protected function setCheckers(?array $results)
    {
        # Queries the project number of this editted beneficiary
        $project_num = Batches::join('implementations', 'implementations.id', '=', 'batches.implementations_id')
            ->where('batches.id', decrypt($this->batchId))
            ->select([
                'implementations.project_num'
            ])
            ->first();

        # Checks if there are any results
        if ($results) {

            # counts how many perfect duplicates encountered from the database
            $perfectCounter = 0;
            foreach ($results as $result) {

                # checks if the result row is a perfect duplicate
                if ($result['is_perfect'] === true) {
                    $perfectCounter++;
                }

                # checks if the result row is in the same project implementation as this editted beneficiary
                if (isset($project_num)) {
                    if ($result['project_num'] === $project_num->project_num && $result['is_perfect']) {
                        $this->isSameImplementation = true;
                    }
                }
            }

            # checks if there are already more than 2 perfect duplicates and mark this editted beneficiary as `ineligible`
            if ($perfectCounter >= 2) {
                $this->isIneligible = true;
            }
        }
    }

    public function clearFiles()
    {
        $this->reset('file_path');
    }

    public function exportSample()
    {
        $this->validateOnly('slots_allocated');
        $spreadsheet = new Spreadsheet();

        $spreadsheet = Annex::sampleImport($spreadsheet, $this->slots_allocated, $this->batch, $this->implementation);

        $writer = new Xlsx($spreadsheet);
        $fileName = 'STIF.xlsx';
        $filePath = storage_path($fileName);

        $writer->save($filePath);
        $this->downloadSampleModal = false;
        # Download the file
        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    #[Computed]
    public function implementation()
    {
        $implementation = Implementation::find($this->batch?->implementations_id);

        return $implementation;
    }

    #[Computed]
    public function batch()
    {
        $batch = Batches::find($this->batchId ? decrypt($this->batchId) : null);

        return $batch;
    }

    #[Computed]
    public function origBatch()
    {
        $batch = Batches::find($this->cachedResults ? decrypt($this->cachedResults['batches_id']) : null);

        return $batch;
    }

    public function checkValidAvgIncome($value)
    {
        if ($value) {
            if (!ctype_digit((string) $value)) {
                return false;
            } elseif (!MoneyFormat::isMaskInt($value)) {
                return false;
            } elseif (MoneyFormat::isNegative($value)) {
                return false;
            }

            return true;
        }
        return false;
    }

    # wire:poll by 1 minute
    public function periodicallyCheckCache()
    {
        $expiration = cache("importing_expiration_" . auth()->id());
        $this->cachedExpiration = now()->diffAsCarbonInterval(Carbon::parse($expiration))->format('%I:%S');
        if (!isset($this->cachedResults) && $this->step === 2) {
            $this->resetImports();
        }
    }

    public function resetImports()
    {
        if ($this->step === 3 || !isset($this->cachedResults) || $this->importFinished && (!isset($this->file_path) || empty($this->file_path) || $this->importFailed)) {
            $this->resetExcept('batchId', 'duplicationThreshold', 'maximumIncome');
        }
    }

    public function mount()
    {
        # gets the matching mode settings of the user
        $settings = UserSetting::where('users_id', Auth::id())
            ->pluck('value', 'key');
        $this->duplicationThreshold = intval($settings->get('duplication_threshold', config('settings.duplication_threshold'))) / 100;
        $this->maximumIncome = $settings->get('maximum_income', config('settings.maximum_income'));
    }

    public function render()
    {
        return view('livewire.focal.implementations.import-file-modal');
    }
}
