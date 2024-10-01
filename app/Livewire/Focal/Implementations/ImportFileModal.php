<?php

namespace App\Livewire\Focal\Implementations;

use App\Jobs\ProcessImportSimilarity;
use App\Models\Batch;
use App\Models\Beneficiary;
use App\Models\UserSetting;
use App\Services\AnnexDGenerator;
use App\Services\JaccardSimilarity;
use Auth;
use Carbon\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ImportFileModal extends Component
{
    use WithFileUploads;

    #[Reactive]
    #[Locked]
    public $batchId;
    #[Validate]
    public $file_path;
    #[Validate]
    public $slots_allocated;

    # -----------------------------------

    public $step = 1;
    public $number_of_rows = 10;
    public $downloadSampleModal = false;
    public $duplicationThreshold;
    public $isResult = false;
    public $similarityResults = [];

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
        // $this->validateOnly('file_path');

        // $filePath = $this->file->store('similarities');

        // ProcessImportSimilarity::dispatch($filePath);

        $this->nextStep();
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
            $batch = Batch::find(decrypt($this->batchId));

            return $batch;
        }
    }

    public function nameCheck()
    {
        # clear out any previous similarity results
        $this->similarityResults = [];
        $this->isResults = false;

        # the filtering process won't go through if first_name, last_name, & birthdate are empty fields
        if ($this->first_name && $this->last_name && $this->birthdate) {

            # double checking again before handing over to the algorithm
            # basically we filter the user input along the way
            $this->first_name = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $this->first_name)));
            $filteredInputString = $this->first_name;
            $this->validateOnly('first_name');

            if ($this->middle_name) {
                $this->middle_name = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $this->middle_name)));
                $filteredInputString .= ' ' . $this->middle_name;
                $this->validateOnly('middle_name');
            }

            $this->last_name = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $this->last_name)));
            $filteredInputString .= ' ' . $this->last_name;
            $this->validateOnly('last_name');

            # checks if there's an extension_name input
            if ($this->extension_name) {
                $this->extension_name = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $this->extension_name)));
                $filteredInputString .= ' ' . $this->extension_name;
                $this->validateOnly('extension_name');
            }

            # removes excess whitespaces between words
            $filteredInputString = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $filteredInputString)));

            # initiate the algorithm instance
            $algorithm = new JaccardSimilarity();

            # fetch all the potential duplicating names from the database
            $beneficiariesFromDatabase = $this->prefetchNames($filteredInputString);

            # initialize possible duplicates variable
            $possibleDuplicates = [];

            # this is where it checks the similarities
            foreach ($beneficiariesFromDatabase as $beneficiary) {

                # gets the full name of the beneficiary
                $name = $this->beneficiaryName($beneficiary, $this->middle_name, $this->extension_name);

                # gets the co-efficient/jaccard index of the 2 names (without birthdate by default)
                $coEfficient = $algorithm->calculateSimilarity($name, $filteredInputString);

                # then check if it goes over the Threshold
                if ($coEfficient >= $this->duplicationThreshold) {
                    $this->isResults = true;

                    if (
                        intval($coEfficient * 100) === 100
                        && Carbon::parse($this->birthdate)->format('Y-m-d') == Carbon::parse($beneficiary->birthdate)->format('Y-m-d')
                    ) {
                        // 
                    }

                    # if it does, then do some shit...
                    $possibleDuplicates[] = [
                        'project_num' => $beneficiary->project_num,
                        'batch_num' => $beneficiary->batch_num,
                        'first_name' => $beneficiary->first_name,
                        'middle_name' => $beneficiary->middle_name,
                        'last_name' => $beneficiary->last_name,
                        'extension_name' => $beneficiary->extension_name,
                        'birthdate' => Carbon::parse($beneficiary->birthdate)->format('M d, Y'),
                        'barangay_name' => $beneficiary->barangay_name,
                        'contact_num' => $beneficiary->contact_num,
                        'sex' => $beneficiary->sex,
                        'age' => $beneficiary->age,
                        'beneficiary_type' => $beneficiary->beneficiary_type,
                        'type_of_id' => $beneficiary->type_of_id,
                        'id_number' => $beneficiary->id_number,
                        'is_pwd' => $beneficiary->is_pwd,
                        'dependent' => $beneficiary->dependent,
                        'coEfficient' => $coEfficient * 100,
                    ];
                }
            }

            $this->similarityResults = $possibleDuplicates;

        }
    }

    protected function prefetchNames(string $filteredInputString)
    {
        $beneficiariesFromDatabase = null;

        # only take beneficiaries from the start of the year until today
        $startDate = now()->startOfYear();
        $endDate = now();

        # separate each word from all the name fields
        # and get the first letter of each word
        $namesToLetters = array_map(fn($word) => $word[0], explode(' ', $filteredInputString));

        $beneficiariesFromDatabase = Beneficiary::join('batches', 'beneficiaries.batches_id', '=', 'batches.id')
            ->join('implementations', 'batches.implementations_id', '=', 'implementations.id')
            ->whereBetween('implementations.created_at', [$startDate, $endDate])
            ->where(function ($query) use ($namesToLetters) {
                foreach ($namesToLetters as $letter) {
                    $query->orWhere('beneficiaries.first_name', 'LIKE', $letter . '%');
                }
            })
            ->where(function ($q) use ($namesToLetters) {
                $q->when($this->middle_name, function ($q) use ($namesToLetters) {
                    foreach ($namesToLetters as $letter) {
                        $q->orWhere('beneficiaries.middle_name', 'LIKE', $letter . '%');
                    }
                });
                foreach ($namesToLetters as $letter) {
                    $q->orWhere('beneficiaries.last_name', 'LIKE', $letter . '%');
                }
            })
            ->select([
                'beneficiaries.*',
                'implementations.project_num',
                'batches.batch_num'
            ])
            ->get();

        return $beneficiariesFromDatabase;
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
