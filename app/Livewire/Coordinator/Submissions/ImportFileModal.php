<?php

namespace App\Livewire\Coordinator\Submissions;

use Livewire\Attributes\Locked;
use Livewire\Attributes\Reactive;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

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

    public function annexD(Spreadsheet $spreadsheet): Spreadsheet
    {
        ## Retrieve the active sheet
        $sheet = $spreadsheet->getActiveSheet();

        ## Set Orientation to LANDSCAPE
        ## Set Page Size to A4
        ## Fit to Width
        ## Not Fit to Height
        $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(0);

        ## The table header attributes
        $headers = [
            [
                'No.',
                'First Name',
                'Middle Name',
                'Last Name',
                'Extension Name',
                'Birthdate (YYYY/MM/DD)',
                'BRGY.',
                'City/Municipality',
                'Province',
                'District',
                'Type of ID',
                'ID No.',
                'Contact No.',
                'E-payment/Account No.',
                'Type of Beneficiaries',
                'Occupation',
                'Sex',
                'Civil Status',
                'Age',
                'Average monthly income',
                'Dependent',
                'Interested in wage employment or self-employment? (Yes or No)',
                'Skills Training Needed',
                'First Name',
                'Middle Name',
                'Last Name',
                'Extension Name'
            ],
        ];

        ## Size of the table columns depending on the amount of header attributes
        $number_of_cols = sizeof($headers[0]);

        ## It's basically a global row index that aggregates every row generated
        ## Think of it as like an interpreter where it generates rows line by line. 
        ## Pretty helpful if you want to move the whole sheet by 1 or more rows.
        $i = 1;

        ## It's set for ignoring double row attributes on the header
        $excludedColumns = [1, 2, 3, 4, 6, 7, 8, 9, 23, 24, 25, 26];

        ## Set default Column widths
        $sheet->getColumnDimension('A')->setWidth(4);
        $sheet->getColumnDimension('B')->setWidth(18.43);
        $sheet->getColumnDimension('C')->setWidth(18.43);
        $sheet->getColumnDimension('D')->setWidth(18.43);
        $sheet->getColumnDimension('E')->setWidth(8.86);
        $sheet->getColumnDimension('F')->setWidth(14);
        $sheet->getColumnDimension('G')->setWidth(10.57);
        $sheet->getColumnDimension('H')->setWidth(8.71);
        $sheet->getColumnDimension('I')->setWidth(8.71);
        $sheet->getColumnDimension('J')->setWidth(6.86);
        $sheet->getColumnDimension('K')->setWidth(13.14);
        $sheet->getColumnDimension('L')->setWidth(17.29);
        $sheet->getColumnDimension('M')->setWidth(13.43);
        $sheet->getColumnDimension('N')->setWidth(8.86);
        $sheet->getColumnDimension('O')->setWidth(10.57);
        $sheet->getColumnDimension('P')->setWidth(10.57);
        $sheet->getColumnDimension('Q')->setWidth(5.14);
        $sheet->getColumnDimension('R')->setWidth(5.14);
        $sheet->getColumnDimension('S')->setWidth(5.14);
        $sheet->getColumnDimension('T')->setWidth(7.43);
        $sheet->getColumnDimension('U')->setWidth(19.43);
        $sheet->getColumnDimension('V')->setWidth(7.86);
        $sheet->getColumnDimension('W')->setWidth(9.43);
        $sheet->getColumnDimension('X')->setWidth(18.43);
        $sheet->getColumnDimension('Y')->setWidth(18.43);
        $sheet->getColumnDimension('Z')->setWidth(18.43);
        $sheet->getColumnDimension('AA')->setWidth(8.86);

        # Resize the Column (CANCELLED)
        // $columnID = Coordinate::stringFromColumnIndex($colIndex + 1);
        // $sheet->getColumnDimension($columnID)->setAutoSize(true);

        # A1
        $sheet->getStyle([1, $i, 1, $i + 1])->getFont()->setSize(10); # A1:A2
        $sheet->setCellValue([1, $i], 'Annex D');
        $sheet->getStyle([1, $i])->getFont()->setBold(true);
        $sheet->getStyle([1, $i])->getFont()->setName('Arial');
        $sheet->mergeCells([1, $i, $number_of_cols, $i]);
        $sheet->getStyle([1, $i, $number_of_cols, $i])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle([1, $i, $number_of_cols, $i])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        $i++;
        # A2
        $sheet->setCellValue([1, $i, $number_of_cols, $i], 'Profile of TUPAD Beneficiaries');
        $sheet->getStyle([1, $i])->getFont()->setBold(true);
        $sheet->getStyle([1, $i])->getFont()->setName('Arial');
        $sheet->mergeCells([1, $i, $number_of_cols, $i]);
        $sheet->getStyle([1, $i, $number_of_cols, $i])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle([1, $i, $number_of_cols, $i])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        $i++;
        # A3
        $sheet->setCellValue([1, $i, $number_of_cols, $i], '_____________________________________________________________________________________________________________________________________________________');
        $sheet->mergeCells([1, $i, $number_of_cols, $i]);
        $sheet->getStyle([1, $i, $number_of_cols, $i])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension($i)->setRowHeight(6.75);

        $i++;
        #A4
        $sheet->getRowDimension($i)->setRowHeight(6);

        $i++;
        # A5:B9 | Set font size to 10
        $sheet->getStyle([1, $i, 2, $i + 4])->getFont()->setSize(10); # A5:B9

        # A5:B5 | C5:E5
        $sheet->setCellValue([1, $i], 'Nature of Project:');
        $sheet->getStyle([1, $i])->getFont()->setName('Arial');
        $sheet->mergeCells([1, $i, 2, $i]);
        $sheet->mergeCells([3, $i, 5, $i]);
        $sheet->getStyle([3, $i, 5, $i])->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);

        $i++;
        # A6
        $sheet->setCellValue([1, $i], 'DOLE Regional Office:');
        $sheet->getStyle([1, $i])->getFont()->setName('Arial');
        $sheet->mergeCells([1, $i, 2, $i]);
        $sheet->mergeCells([3, $i, 5, $i]);
        $sheet->getStyle([3, $i, 5, $i])->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);

        $i++;
        # A7
        $sheet->setCellValue([1, $i], 'Province:');
        $sheet->getStyle([1, $i])->getFont()->setName('Arial');
        $sheet->mergeCells([1, $i, 2, $i]);
        $sheet->mergeCells([3, $i, 5, $i]);
        $sheet->getStyle([3, $i, 5, $i])->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);

        $i++;
        # A8
        $sheet->setCellValue([1, $i], 'Municipality:');
        $sheet->getStyle([1, $i])->getFont()->setName('Arial');
        $sheet->mergeCells([1, $i, 2, $i]);
        $sheet->mergeCells([3, $i, 5, $i]);
        $sheet->getStyle([3, $i, 5, $i])->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);

        $i++;
        # A9
        $sheet->setCellValue([1, $i], 'Barangay:');
        $sheet->getStyle([1, $i])->getFont()->setName('Arial');
        $sheet->mergeCells([1, $i, 2, $i]);
        $sheet->mergeCells([3, $i, 5, $i]);
        $sheet->getStyle([3, $i, 5, $i])->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);

        $i++;
        # A10
        $sheet->getRowDimension($i)->setRowHeight(9);

        $i++;
        # A11
        $sheet->getRowDimension($i)->setRowHeight(18.75);

        ## Headers
        # B11:E11
        $sheet->setCellValue([2, $i], 'Name of Beneficiary');
        $sheet->mergeCells([2, $i, 5, $i]);
        $sheet->getStyle([2, $i, 5, $i])->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle([2, $i, 5, $i])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle([2, $i, 5, $i])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        # G11:J11
        $sheet->setCellValue([7, $i], 'Project Location');
        $sheet->mergeCells([7, $i, 10, $i]);
        $sheet->getStyle([7, $i, 10, $i])->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle([7, $i, 10, $i])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle([7, $i, 10, $i])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        # X11:AA11
        $sheet->setCellValue([24, $i], 'Spouse');
        $sheet->mergeCells([24, $i, $number_of_cols, $i]);
        $sheet->getStyle([24, $i, $number_of_cols, $i])->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle([24, $i, $number_of_cols, $i])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle([24, $i, $number_of_cols, $i])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        ## Set Text & Fill Colors on table header attributes
        # A11:AA12
        $sheet->getStyle([1, $i, $number_of_cols, $i + 1])->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('B6C6E7');

        ## Set Font Size to 8
        # A11:AA12
        $sheet->getStyle([1, $i, $number_of_cols, $i + 1])->getFont()->setSize(8);

        # A12
        $sheet->getRowDimension($i + 1)->setRowHeight(56.25);

        # A11:AA12
        $j = $i;
        $default = false;
        # Set the Table attribute headers (first name, middle name, birthdate, etc.)
        foreach ($headers as $rowIndex => $row) {
            foreach ($row as $colIndex => $value) {
                if (!in_array($colIndex, $excludedColumns)) {
                    if ($i !== $j && $default === true) {
                        $i--;
                        $default = false;
                    }
                    $sheet->setCellValue([$colIndex + 1, $rowIndex + $i], $value);
                    $sheet->mergeCells([$colIndex + 1, $rowIndex + $i, $colIndex + 1, $rowIndex + $i + 1]);
                    $sheet->getStyle([$colIndex + 1, $rowIndex + $i, $colIndex + 1, $rowIndex + $i + 1])->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                    $sheet->getStyle([$colIndex + 1, $rowIndex + $i])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle([$colIndex + 1, $rowIndex + $i])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                } else {
                    if ($default === false) {
                        $i++;
                        $default = true;
                    }
                    $sheet->setCellValue([$colIndex + 1, $rowIndex + $i], $value);
                    $sheet->getStyle([$colIndex + 1, $rowIndex + $i])->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                    $sheet->getStyle([$colIndex + 1, $rowIndex + $i])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle([$colIndex + 1, $rowIndex + $i])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                }

            }
        }

        ## Checks if the value (in the array) is the last one in the `$excludedColumns` array
        ## then aggregate the `$i` (or globalRowIndex). Otherwise, it would be the wrong row
        ## for the next one.
        if ($excludedColumns[sizeof($excludedColumns) - 1] !== $number_of_cols) {
            # A13
            $i++;
        }

        # [A13] or A12
        for ($row = 0; $row < $this->number_of_rows; $row++) {
            $sheet->getRowDimension($row + $i)->setRowHeight(31.5);
            for ($col = 0; $col < $number_of_cols; $col++) {
                if ($col === 0) {
                    $sheet->setCellValue([$col + 1, $row + $i], $row + 1);
                }
                $sheet->getStyle([$col + 1, $row + $i])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle([$col + 1, $row + $i])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getStyle([$col + 1, $row + $i])->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            }
        }

        ## Sets the Wrap Text function to true for A11 to AA12
        $sheet->getStyle([1, $i - 2, $number_of_cols, $i + $this->number_of_rows - 1])->getAlignment()->setWrapText(true);

        $i++;
        # Footer Texts
        $sheet->setCellValue([2, $this->number_of_rows + $i], 'Prepared and Certified true and correct by:');
        $sheet->mergeCells([2, $this->number_of_rows + $i, 4, $this->number_of_rows + $i]);
        $i += 4;
        $sheet->getStyle([2, $this->number_of_rows + $i, 3, $this->number_of_rows + $i])->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
        $sheet->mergeCells([2, $this->number_of_rows + $i, 3, $this->number_of_rows + $i]);
        $i++;
        $sheet->setCellValue([2, $this->number_of_rows + $i], 'DOLE or Co-partner');
        $sheet->mergeCells([2, $this->number_of_rows + $i, 3, $this->number_of_rows + $i]);
        $i++;
        $sheet->setCellValue([1, $this->number_of_rows + $i], 'Notes:');
        $sheet->getStyle([1, $this->number_of_rows + $i])->getFont()->setBold(true);
        $i++;

        $rich = new RichText();
        $first = $rich->createTextRun('Birthdate:');
        $first->getFont()->setBold(true);
        $first->getFont()->setSize(7);
        $second = $rich->createTextRun(' Year/Month/Day (YYYY/MM/DD)');
        $second->getFont()->setSize(7);
        $sheet->setCellValue([1, $this->number_of_rows + $i], $rich);
        $sheet->mergeCells([1, $this->number_of_rows + $i, $number_of_cols, $this->number_of_rows + $i]);
        $i++;

        $rich = new RichText();
        $first = $rich->createTextRun('Project Location:');
        $first->getFont()->setBold(true);
        $first->getFont()->setSize(7);
        $second = $rich->createTextRun(' (Street No. Barangay, City/Municipality, Province, District)');
        $second->getFont()->setSize(7);
        $sheet->setCellValue([1, $this->number_of_rows + $i], $rich);
        $sheet->mergeCells([1, $this->number_of_rows + $i, $number_of_cols, $this->number_of_rows + $i]);
        $i++;

        $rich = new RichText();
        $first = $rich->createTextRun('Type of Beneficiaries:');
        $first->getFont()->setBold(true);
        $first->getFont()->setSize(7);
        $second = $rich->createTextRun(' (a.) Underemployed/Self-Employed; (b.) Minimum wage/below minimum earners that were displaced due to: temporary suspension of business operations, calamity/crisis situation i.e, earthquake, typhoon, volcanic eruption, global/national financial crisis, other (pls. specify), closure of company, retrenchment, (c.) Person with Disability (PWD), (d) Indigenous People, (e.) Former Violent Extremist Groups');
        $second->getFont()->setSize(7);
        $sheet->setCellValue([1, $this->number_of_rows + $i], $rich);
        $sheet->mergeCells([1, $this->number_of_rows + $i, $number_of_cols, $this->number_of_rows + $i]);
        $i++;

        $rich = new RichText();
        $first = $rich->createTextRun('Occupation:');
        $first->getFont()->setBold(true);
        $first->getFont()->setSize(7);
        $second = $rich->createTextRun(' Transport workers, Vendors, Crop growers (please specify, i.e tobacco farmer), Homebased worker (please specify, i.e sewer), Fisherfolks, Livestock/Poultry Raiser, Small Transport drivers, Laborer (please specify); Others (Please specify)');
        $second->getFont()->setSize(7);
        $sheet->setCellValue([1, $this->number_of_rows + $i], $rich);
        $sheet->mergeCells([1, $this->number_of_rows + $i, $number_of_cols, $this->number_of_rows + $i]);
        $i++;

        $rich = new RichText();
        $first = $rich->createTextRun('Civil Status:');
        $first->getFont()->setBold(true);
        $first->getFont()->setSize(7);
        $second = $rich->createTextRun(' S fro single, M for married, D for divoreced, SP for separated, W for Widowed');
        $second->getFont()->setSize(7);
        $sheet->setCellValue([1, $this->number_of_rows + $i], $rich);
        $sheet->mergeCells([1, $this->number_of_rows + $i, $number_of_cols, $this->number_of_rows + $i]);
        $i++;

        $rich = new RichText();
        $first = $rich->createTextRun('Dependent:');
        $first->getFont()->setBold(true);
        $first->getFont()->setSize(7);
        $second = $rich->createTextRun(' Name of the beneficiary of micro-insurance policy holder');
        $second->getFont()->setSize(7);
        $sheet->setCellValue([1, $this->number_of_rows + $i], $rich);
        $sheet->mergeCells([1, $this->number_of_rows + $i, $number_of_cols, $this->number_of_rows + $i]);
        $i++;

        $rich = new RichText();
        $first = $rich->createTextRun('Trainings:');
        $first->getFont()->setBold(true);
        $first->getFont()->setSize(7);
        $second = $rich->createTextRun(' Agriculture crops production, Aquaculture, Automotive, Construction, Weilding, Information and Communication Technology, Electrical and electronics, furniture making, garments and textile. Food processin, cooking, housekeeping, tourism, customer services, others (please specify)');
        $second->getFont()->setSize(7);
        $sheet->setCellValue([1, $this->number_of_rows + $i], $rich);
        $sheet->mergeCells([1, $this->number_of_rows + $i, $number_of_cols, $this->number_of_rows + $i]);
        $i++;

        # Globally set font sizes
        $sheet->getStyle([1, $this->number_of_rows + 20, $number_of_cols, $this->number_of_rows + $i])->getFont()->setSize(7);

        # Set Worksheet Name & Color
        $sheet->setTitle('ANNEX D - Profile');
        $sheet->getTabColor()->setRGB('FEFD0D'); // Green tab color

        return $spreadsheet;
    }

    public function clearFiles()
    {
        $this->reset('file_path');
    }

    public function export()
    {
        $spreadsheet = new Spreadsheet();

        $spreadsheet = $this->annexD($spreadsheet);

        $writer = new Xlsx($spreadsheet);
        $fileName = 'Annex D - Profie (Sample Format).xlsx';
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
