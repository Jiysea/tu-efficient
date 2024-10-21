<?php

namespace App\Livewire\Coordinator\Submissions;

use App\Jobs\ProcessImportSimilarity;
use App\Models\Batch as Batches;
use App\Models\UserSetting;
use App\Services\AnnexDGenerator;
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
            'file_path.file' => 'This should be a valid file.',
            'file_path.mimes' => 'The file should be in .xlsx or .csv format.',
            'slots_allocated.required' => 'This field is required.',
            'slots_allocated.integer' => 'This field should be a valid number.',
            'slots_allocated.min' => 'This field should be more than 0.',
            'slots_allocated.gte' => 'This field should be nonnegative.',
            'slots_allocated.lte' => 'This field should be less than total.',
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
            dump($e->getMessage());
        })->dispatch();

        $this->jobsBatchId = $jobBatch->id;

        $this->nextStep();
    }

    // public function testingAlgorithm()
    // {
    //     JaccardSimilarity::getResults('Eniggo', 'Sumeragi', 'Lincoln', 'Jr....', '1989-11-20');
    // }

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

                        # check if there are already more than 2 perfect duplicates and mark this editted beneficiary as `ineligible`
                        if ($perfectCounter >= 2 || $isSameImplementation) {
                            $this->ineligibleResults[] = $beneficiary;
                        } else {
                            $this->similarityResults[] = $beneficiary;
                        }

                    }
                }
                if (sizeof($this->successResults) > 0) {
                    // $this->dispatch(event: 'import-success-beneficiaries', count: sizeof($this->successResults));
                }
            } else {
                $this->backStep();
            }
            $this->reset('file_path');

            # Notify the User if it's done processing the Import
            $this->dispatch('finished-importing');
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
        if ($this->step === 3 && $this->importFinished && (!isset($this->file_path) || empty($this->file_path))) {
            $this->resetExcept('batchId', 'duplicationThreshold');
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
        return view('livewire.coordinator.submissions.import-file-modal');
    }
}
