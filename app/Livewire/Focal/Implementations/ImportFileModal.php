<?php

namespace App\Livewire\Focal\Implementations;

use App\Jobs\ProcessImportSimilarity;
use App\Models\Batch as Batches;
use App\Models\Beneficiary;
use App\Models\UserSetting;
use App\Services\AnnexDGenerator;
use App\Services\JaccardSimilarity;
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
    public $selectedSheetIndex = null;

    # -----------------------------------

    public $step = 1;
    public $number_of_rows = 10;
    public $downloadSampleModal = false;
    public $duplicationThreshold;
    public $isResult = false;
    public $cachedResults = [];
    public array $successResults = [];
    public array $errorResults = [];
    public array $similarityResults = [];
    public array $ineligibleResults = [];

    # -----------------------------------

    #[Locked]
    public $jobsBatchId;
    public $importing = false;
    public $importFinished = false;

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
            'file_path.file' => ':attribute should be a valid file.',
            'file_path.mimes' => ':attribute should be in .xlsx or .csv format.',
            'slots_allocated.required' => 'Invalid :attribute amount.',
            'slots_allocated.integer' => ':attribute should be a valid number.',
            'slots_allocated.min' => ':attribute should be > 0.',
            'slots_allocated.gte' => ':attribute should be nonnegative.',
            'slots_allocated.lte' => ':attribute should be less than total.',
        ];
    }

    # Validation attribute names for human readability purpose
    # for example: The project_num should not be empty.
    # instead of that: The project number should not be empty.
    public function validationAttributes()
    {
        return [
            'file_path' => 'File',
            'slots_allocated' => 'Slots',
        ];
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
            new ProcessImportSimilarity($filePath, Auth::id(), $this->batch->id, $this->duplicationThreshold),
        ])->catch(function (Batch $batch, Throwable $e) {

        })->dispatch();

        $this->jobsBatchId = $jobBatch->id;

        $this->nextStep();
    }

    public function importProgress()
    {
        $jobBatch = Bus::findBatch($this->jobsBatchId);

        if ($jobBatch->finished()) {
            $this->importing = false;
            $this->importFinished = true;
            $this->reset('successResults', 'errorResults', 'similarityResults', 'ineligibleResults');
            $this->cachedResults = cache("similarity_" . Auth::id());

            # Queries the project number of this editted beneficiary
            $project_num = Batches::join('implementations', 'implementations.id', '=', 'batches.implementations_id')
                ->where('batches.id', decrypt($this->batchId))
                ->select([
                    'implementations.project_num'
                ])
                ->first();

            if (isset($this->cachedResults) || !empty($this->cachedResults)) {
                foreach ($this->cachedResults as $beneficiary) {

                    if ($beneficiary['success']) {
                        $this->successResults[] = $beneficiary;
                    }

                    if (array_unique($beneficiary['errors']) !== ["first_name" => null]) {
                        $this->errorResults[] = $beneficiary;
                    }

                    # This will check if the algorithm has found any possible duplicates based on the given threshold
                    if (!is_null($beneficiary['similarities'])) {
                        # counts how many perfect duplicates encountered from the database
                        $perfectCounter = 0;
                        $isSameImplementation = false;
                        foreach ($beneficiary['similarities'] as $result) {

                            # checks if the result row is a perfect duplicate
                            if ($result['is_perfect']) {
                                $perfectCounter++;
                            }

                            # checks if the result row is in the same project implementation as this editted beneficiary
                            if (isset($project_num)) {
                                if ($result['project_num'] === $project_num->project_num && $result['is_perfect']) {
                                    $isSameImplementation = true;
                                }
                            }
                        }

                        # checks if there are already more than 2 perfect duplicates and mark this editted beneficiary as `ineligible`
                        if ($perfectCounter >= 2 || $isSameImplementation) {
                            $this->ineligibleResults[] = $beneficiary;
                        } else {
                            $this->similarityResults[] = $beneficiary;
                        }

                    }
                }
                if (sizeof($this->successResults) > 0) {
                    $this->dispatch(event: 'import-success-beneficiaries', count: sizeof($this->successResults));
                }
            } else {
                $this->backStep();
            }
            $this->reset('file_path');
        }
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

    public function export()
    {
        $spreadsheet = new Spreadsheet();
        $annexD = new AnnexDGenerator();

        $spreadsheet = $annexD->getGeneratedSheet($spreadsheet, $this->slots_allocated);

        $writer = new Xlsx($spreadsheet);
        $fileName = 'Annex D - Profile (Sample Format).xlsx';
        $filePath = storage_path($fileName);

        $writer->save($filePath);
        $this->downloadSampleModal = false;
        # Download the file
        return response()->download($filePath)->deleteFileAfterSend(true);

    }

    #[Computed]
    public function batch()
    {
        if ($this->batchId) {
            $batch = Batches::find(decrypt($this->batchId));

            return $batch;
        }
    }

    public function resetImports()
    {
        if (is_null($this->file_path) || $this->file_path === '') {
            $this->resetExcept('batchId', '');
        }
    }

    public function mount()
    {
        # gets the matching mode settings of the user
        $settings = UserSetting::where('users_id', Auth::id())
            ->pluck('value', 'key');
        $this->duplicationThreshold = intval($settings->get('duplication_threshold', config('settings.duplication_threshold'))) / 100;
    }

    public function render()
    {
        return view('livewire.focal.implementations.import-file-modal');
    }
}
