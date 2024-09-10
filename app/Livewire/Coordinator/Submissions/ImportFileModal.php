<?php

namespace App\Livewire\Coordinator\Submissions;

use App\Services\AnnexDGenerator;
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

    # -----------------------------------

    public $step = 1;
    public $number_of_rows = 10;

    # -----------------------------------

    public function rules()
    {
        return [
            'file_path' => 'nullable|file|mimes:xlsx,csv',
        ];
    }

    public function checkError()
    {
        if ($this->getErrorBag()->has('file_path')) {
            // Reset the file input if validation fails
            $this->reset('file_path');
        }
    }

    public function messages()
    {
        return [
            'file_path.file' => ':attribute should be a valid file.',
            'file_path.mimes' => ':attribute should be in .xlsx or .csv format.',
        ];
    }

    # Validation attribute names for human readability purpose
    # for example: The project_num should not be empty.
    # instead of that: The project number should not be empty.
    public function validationAttributes()
    {
        return [
            'file_path' => 'File',
        ];
    }

    public function nextStep()
    {
        $this->step++;
    }

    public function prevStep()
    {
        $this->step--;
    }

    public function uploadFile()
    {

    }

    public function validateFile()
    {

    }

    public function clearFiles()
    {
        $this->reset('file_path');
    }

    public function export($slots_allocated)
    {
        $spreadsheet = new Spreadsheet();
        $annexD = new AnnexDGenerator();

        $spreadsheet = $annexD->getGeneratedSheet($spreadsheet, $slots_allocated);

        $writer = new Xlsx($spreadsheet);
        $fileName = 'Annex D - Profile (Sample Format).xlsx';
        $filePath = storage_path($fileName);

        $writer->save($filePath);

        # Download the file
        return response()->download($filePath)->deleteFileAfterSend(true);

    }

    public function mount()
    {

    }

    public function render()
    {
        return view('livewire.coordinator.submissions.import-file-modal');
    }
}
