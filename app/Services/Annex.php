<?php

namespace App\Services;
use App\Models\Beneficiary;
use App\Models\Implementation;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Annex
{

    public static function export(Spreadsheet $spreadsheet, mixed $batch, array $exportType, string $exportFormat): Spreadsheet
    {
        # Types of Annexes: annex_e1, annex_e2, annex_j2, annex_l, annex_l_sign
        if ($exportType['annex_e1']) {
            $sheet1 = new Worksheet($spreadsheet, 'ANNEX E-1 - COS');
            $spreadsheet->addSheet($sheet1);
            $sheet1->getTabColor()->setRGB('FF0000'); // Red tab color
            $sheet1 = self::annex_e1($sheet1, $batch, $exportFormat);
        }

        if ($exportType['annex_e2']) {
            $sheet2 = new Worksheet($spreadsheet, 'ANNEX E-2 - COS(co-partner)');
            $spreadsheet->addSheet($sheet2);
            $sheet2->getTabColor()->setRGB('FF0000'); // Red tab color
            $sheet2 = self::annex_e2($sheet2, $batch, $exportFormat);
        }

        if ($exportType['annex_j2']) {
            $sheet3 = new Worksheet($spreadsheet, 'ANNEX J-2 - Attendance Sheet');
            $spreadsheet->addSheet($sheet3);
            $sheet3->getTabColor()->setRGB('4472C4'); // Blue tab color
            $sheet3 = self::annex_j2($sheet3, $batch, $exportFormat);
        }

        if ($exportType['annex_l']) {
            $sheet4 = new Worksheet($spreadsheet, 'ANNEX L - Payroll');
            $spreadsheet->addSheet($sheet4);
            $sheet4->getTabColor()->setRGB('70AD47'); // Green tab color
            $sheet4 = self::annex_l($sheet4, $batch, $exportFormat);
        }

        if ($exportType['annex_l_sign']) {
            $sheet5 = new Worksheet($spreadsheet, 'ANNEX L - Payroll with Sign');
            $spreadsheet->addSheet($sheet5);
            $sheet5->getTabColor()->setRGB('70AD47'); // Green tab color
            $sheet5 = self::annex_l_sign($sheet5, $batch, $exportFormat);
        }

        $spreadsheet->removeSheetByIndex(0);
        $spreadsheet->setActiveSheetIndex(0);

        return $spreadsheet;
    }

    protected static function annex_d(Worksheet $sheet, mixed $batch, string $exportFormat)
    {

    }

    protected static function annex_e1(Worksheet $sheet, mixed $batch, string $exportFormat): Worksheet
    {

        ## Set Orientation to PORTRAIT
        ## Set Page Size to A4
        ## Fit to Width
        ## Not Fit to Height
        $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_PORTRAIT);
        $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(0);

        $columnHeaders = [
            'NO',
            'NAME',
            'ADDRESS',
            'SIGNATURE',
        ];

        $beneficiaries = Beneficiary::where('batches_id', $batch->id)
            ->get();

        if ($exportFormat === 'xlsx') {

            # It's basically a global row index and max column that aggregates every row generated
            # Think of it as like an interpreter where it generates rows line by line. 
            # Pretty helpful if you want to move the whole sheet by 1 or more rows.
            $curRow = 1;
            $maxCol = 4;

            # Set Column Widths
            $sheet->getColumnDimension('A')->setWidth(6.86);
            $sheet->getColumnDimension('B')->setWidth(42.43);
            $sheet->getColumnDimension('C')->setWidth(24.57);
            $sheet->getColumnDimension('D')->setWidth(28.29);

            # Set Font Size to A1:A2
            $sheet->getStyle([1, $curRow, 1, $curRow + 1])->getFont()->setSize(12);

            # Second Party: 
            $sheet->setCellValue([1, $curRow], 'Second Party:');
            $sheet->getStyle([1, $curRow])->getFont()->setBold(true);
            $sheet->getStyle([1, $curRow])->getFont()->setName('Arial');
            $sheet->mergeCells([1, $curRow, $maxCol, $curRow]);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $curRow++;

            # (Names of TUPAD Program Beneficiaries)
            $sheet->setCellValue([1, $curRow], '(Names of TUPAD Program Beneficiaries)');
            $sheet->getStyle([1, $curRow])->getFont()->setBold(true);
            $sheet->getStyle([1, $curRow])->getFont()->setItalic(true);
            $sheet->getStyle([1, $curRow])->getFont()->setName('Arial');
            $sheet->mergeCells([1, $curRow, $maxCol, $curRow]);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

            $curRow++;
            $sheet->getRowDimension($curRow)->setRowHeight(13.5);
            $curRow++;

            # Write the Column Headers and set its colors and borders
            $sheet->getRowDimension($curRow)->setRowHeight(21);
            $sheet->fromArray($columnHeaders, null, 'A' . $curRow);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getFont()->setSize(12);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getFont()->setName('Arial');
            $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getFont()->setBold(true);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getAlignment()->setHorizontal(horizontalAlignment: Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB('FFCDD4');
            $curRow++;

            $num = 1;
            foreach ($beneficiaries as $beneficiary) {
                $sheet->getRowDimension($curRow)->setRowHeight(29.25);
                $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getAlignment()->setWrapText(true);
                $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getFont()->setSize(12);
                $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getFont()->setName('Arial');

                # NO
                $sheet->setCellValue([1, $curRow], $num);
                # NAME
                $sheet->setCellValue([2, $curRow], self::full_name($beneficiary));
                # ADDRESS
                $sheet->getStyle([3, $curRow])->getFont()->setSize(sizeInPoints: 9);
                $sheet->setCellValue([3, $curRow], self::address($beneficiary));

                $num++;
                $curRow++;
            }
            $curRow++;

            # Signed in the presence of:
            $sheet->mergeCells([1, $curRow, $maxCol, $curRow]);
            $sheet->getStyle([1, $curRow])->getFont()->setName('Arial');
            $sheet->getStyle([1, $curRow])->getFont()->setSize(12);
            $sheet->getStyle([1, $curRow])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->setCellValue([1, $curRow], 'Signed in the presence of:');
            $curRow++;

            # Signature Lines
            $sheet->getRowDimension($curRow)->setRowHeight(39);
            $sheet->mergeCells([1, $curRow, 2, $curRow]);
            $sheet->getStyle([1, $curRow])->getFont()->setSize(12);
            $sheet->setCellValue([1, $curRow], '____________________________');

            $sheet->mergeCells([3, $curRow, 4, $curRow]);
            $sheet->getStyle([3, $curRow])->getFont()->setSize(12);
            $sheet->setCellValue([3, $curRow], '____________________________');

            $curRow++;

            # (Signature over Printed Name) 
            $sheet->mergeCells([1, $curRow, 2, $curRow]);
            $sheet->getStyle([1, $curRow])->getFont()->setName('Arial');
            $sheet->getStyle([1, $curRow])->getFont()->setSize(10);
            $sheet->setCellValue([1, $curRow], '(Signature over Printed Name)');

            $sheet->mergeCells([3, $curRow, 4, $curRow]);
            $sheet->getStyle([3, $curRow])->getFont()->setName('Arial');
            $sheet->getStyle([3, $curRow])->getFont()->setSize(10);
            $sheet->setCellValue([3, $curRow], '(Signature over Printed Name)');

            $curRow++;

            # Representative
            $sheet->mergeCells([1, $curRow, 2, $curRow]);
            $sheet->getStyle([1, $curRow])->getFont()->setName('Arial');
            $sheet->getStyle([1, $curRow])->getFont()->setSize(10);
            $sheet->setCellValue([1, $curRow], 'Proponent Representative');

            $sheet->mergeCells([3, $curRow, 4, $curRow]);
            $sheet->getStyle([3, $curRow])->getFont()->setName('Arial');
            $sheet->getStyle([3, $curRow])->getFont()->setSize(10);
            $sheet->setCellValue([3, $curRow], 'DOLE Representative');

            $curRow++;

            # Positions 1
            $sheet->mergeCells([1, $curRow, 2, $curRow]);
            $sheet->getStyle([1, $curRow])->getFont()->setName('Arial');
            $sheet->getStyle([1, $curRow])->getFont()->setSize(10);
            $sheet->setCellValue([1, $curRow], '(LGU i.e PESO Manager, LCE');

            $sheet->mergeCells([3, $curRow, 4, $curRow]);
            $sheet->getStyle([3, $curRow])->getFont()->setName('Arial');
            $sheet->getStyle([3, $curRow])->getFont()->setSize(10);
            $sheet->setCellValue([3, $curRow], '(RO/PO/FO Head or');

            $curRow++;

            # Positions 2
            $sheet->mergeCells([1, $curRow, 2, $curRow]);
            $sheet->getStyle([1, $curRow])->getFont()->setName('Arial');
            $sheet->getStyle([1, $curRow])->getFont()->setSize(10);
            $sheet->setCellValue([1, $curRow], 'or Head of PO/CSO) ');

            $sheet->mergeCells([3, $curRow, 4, $curRow]);
            $sheet->getStyle([3, $curRow])->getFont()->setName('Arial');
            $sheet->getStyle([3, $curRow])->getFont()->setSize(10);
            $sheet->setCellValue([3, $curRow], 'DILEEP Focal Person)');

        } elseif ($exportFormat === 'csv') {

        }

        return $sheet;
    }

    protected static function annex_e2(Worksheet $sheet, mixed $batch, string $exportFormat): Worksheet
    {
        ## Set Orientation to PORTRAIT
        ## Set Page Size to A4
        ## Fit to Width
        ## Not Fit to Height
        $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_PORTRAIT);
        $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(0);

        $columnHeaders = [
            'NO',
            'NAME',
            'ADDRESS',
            'SIGNATURE',
        ];

        $beneficiaries = Beneficiary::where('batches_id', $batch->id)
            ->get();

        if ($exportFormat === 'xlsx') {

            # It's basically a global row index and max column that aggregates every row generated
            # Think of it as like an interpreter where it generates rows line by line. 
            # Pretty helpful if you want to move the whole sheet by 1 or more rows.
            $curRow = 1;
            $maxCol = 4;

            # Set Column Widths
            $sheet->getColumnDimension('A')->setWidth(6.86);
            $sheet->getColumnDimension('B')->setWidth(42.43);
            $sheet->getColumnDimension('C')->setWidth(24.57);
            $sheet->getColumnDimension('D')->setWidth(28.29);

            # Set Font Size to A1:A2
            $sheet->getStyle([1, $curRow, 1, $curRow + 1])->getFont()->setSize(12);

            # Second Party: 
            $sheet->setCellValue([1, $curRow], 'Second Party:');
            $sheet->getStyle([1, $curRow])->getFont()->setBold(true);
            $sheet->getStyle([1, $curRow])->getFont()->setName('Arial');
            $sheet->mergeCells([1, $curRow, $maxCol, $curRow]);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $curRow++;

            # (Names of TUPAD Program Beneficiaries)
            $sheet->setCellValue([1, $curRow], '(Names of TUPAD Program Beneficiaries)');
            $sheet->getStyle([1, $curRow])->getFont()->setBold(true);
            $sheet->getStyle([1, $curRow])->getFont()->setItalic(true);
            $sheet->getStyle([1, $curRow])->getFont()->setName('Arial');
            $sheet->mergeCells([1, $curRow, $maxCol, $curRow]);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

            $curRow++;
            $sheet->getRowDimension($curRow)->setRowHeight(13.5);
            $curRow++;

            # Write the Column Headers and set its colors and borders
            $sheet->getRowDimension($curRow)->setRowHeight(21);
            $sheet->fromArray($columnHeaders, null, 'A' . $curRow);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getFont()->setSize(12);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getFont()->setName('Arial');
            $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getFont()->setBold(true);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getAlignment()->setHorizontal(horizontalAlignment: Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB('FFCDD4');
            $curRow++;

            $num = 1;
            foreach ($beneficiaries as $beneficiary) {
                $sheet->getRowDimension($curRow)->setRowHeight(29.25);
                $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getAlignment()->setWrapText(true);
                $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getFont()->setSize(12);
                $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getFont()->setName('Arial');

                # NO
                $sheet->setCellValue([1, $curRow], $num);
                # NAME
                $sheet->setCellValue([2, $curRow], self::full_name($beneficiary));
                # ADDRESS
                $sheet->getStyle([3, $curRow])->getFont()->setSize(sizeInPoints: 9);
                $sheet->setCellValue([3, $curRow], self::address($beneficiary));

                $num++;
                $curRow++;
            }
            $curRow++;

            # Signed in the presence of:
            $sheet->mergeCells([1, $curRow, $maxCol, $curRow]);
            $sheet->getStyle([1, $curRow])->getFont()->setName('Arial');
            $sheet->getStyle([1, $curRow])->getFont()->setSize(12);
            $sheet->getStyle([1, $curRow])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->setCellValue([1, $curRow], 'Signed in the presence of:');
            $curRow++;

            # Signature Lines
            $sheet->getRowDimension($curRow)->setRowHeight(39);
            $sheet->mergeCells([1, $curRow, 2, $curRow]);
            $sheet->getStyle([1, $curRow])->getFont()->setSize(12);
            $sheet->setCellValue([1, $curRow], '____________________________');

            $sheet->mergeCells([3, $curRow, 4, $curRow]);
            $sheet->getStyle([3, $curRow])->getFont()->setSize(12);
            $sheet->setCellValue([3, $curRow], '____________________________');

            $curRow++;

            # Representative Positions
            $sheet->mergeCells([1, $curRow, 2, $curRow]);
            $sheet->getStyle([1, $curRow])->getFont()->setSize(12);
            $sheet->setCellValue([1, $curRow], 'TSSD Head');

            $sheet->mergeCells([3, $curRow, 4, $curRow]);
            $sheet->getStyle([3, $curRow])->getFont()->setSize(12);
            $sheet->setCellValue([3, $curRow], 'IMSD Head');

        } elseif ($exportFormat === 'csv') {

        }

        return $sheet;
    }

    protected static function annex_j2(Worksheet $sheet, mixed $batch, string $exportFormat): Worksheet
    {
        ## Set Orientation to LANDSCAPE
        ## Set Page Size to A4
        ## Fit to Width
        ## Not Fit to Height
        $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(0);

        $beneficiaries = Beneficiary::where('batches_id', $batch->id)
            ->get();
        $implementation = Implementation::whereHas('batch', function ($q) use ($batch) {
            $q->where('batches.implementations_id', $batch->implementations_id);
        })
            ->first();

        # It's basically a global row index and max column that aggregates every row generated
        # Think of it as like an interpreter where it generates rows line by line. 
        # Pretty helpful if you want to move the whole sheet by 1 or more rows.
        $curRow = 1;
        $maxCol = 2;

        $daysHeader = [];

        $maxCol++;

        for ($day = 1; $day <= $implementation->days_of_work; $day++) {
            $daysHeader[] = 'Day ' . $day;
            $maxCol++;
        }

        if ($exportFormat === 'xlsx') {

            # Set Font Size to A1:A5
            $sheet->getStyle([1, $curRow, 1, $curRow + 4])->getFont()->setSize(12);

            # Annex J-2 
            $sheet->setCellValue([1, $curRow], 'Annex J-2');
            $sheet->getStyle([1, $curRow])->getFont()->setBold(true);
            $sheet->mergeCells([1, $curRow, $maxCol, $curRow]);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $curRow++;

            # Daily Attendance Sheet
            $sheet->setCellValue([1, $curRow], 'Daily Attendance Sheet');
            $sheet->getStyle([1, $curRow])->getFont()->setBold(true);
            $sheet->mergeCells([1, $curRow, $maxCol, $curRow]);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $curRow += 2;

            # Stagger Rows -------------------------------------------------

            # Apply to the Whole Row (2)
            $sheet->getRowDimension($curRow)->setRowHeight(21);
            $sheet->getRowDimension($curRow + 1)->setRowHeight(39.75);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow + 1])->getFont()->setBold(true);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow + 1])->getAlignment()->setWrapText(true);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow + 1])->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow + 1])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow + 1])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

            # No.
            $sheet->setCellValue([1, $curRow], 'No.');
            $sheet->getColumnDimensionByColumn(1)->setWidth(4);
            $sheet->mergeCells([1, $curRow, 1, $curRow + 1]);

            # Name
            $sheet->setCellValue([2, $curRow], 'Name');
            $sheet->getColumnDimensionByColumn(2)->setWidth(44);

            # (First, Middle, Last Name, Extension Name)
            $sheet->setCellValue([2, $curRow + 1], '(First, Middle, Last Name, Extension Name)');

            # Days & Date Headers
            for ($col = 1; $col <= sizeof($daysHeader); $col++) {
                $sheet->setCellValue([$col + 2, $curRow + 1], '(Date)');
                $sheet->getColumnDimensionByColumn($col + 2)->setWidth(22);
                $sheet->setCellValue([$col + 2, $curRow], $daysHeader[$col - 1]);
            }

            # Signature of Coordinator
            $sheet->setCellValue([$maxCol, $curRow], 'Signature of Coordinator');
            $sheet->getColumnDimensionByColumn($maxCol)->setWidth(25);
            $sheet->mergeCells([$maxCol, $curRow, $maxCol, $curRow + 1]);

            # END of Stagger Rows -------------------------------------------------
            $curRow += 2;
            $num = 1;
            foreach ($beneficiaries as $beneficiary) {
                $sheet->getRowDimension($curRow)->setRowHeight(38.25);
                $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getAlignment()->setWrapText(true);
                $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getFont()->setSize(12);
                $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

                # NO
                $sheet->setCellValue([1, $curRow], $num);
                # NAME
                $sheet->setCellValue([2, $curRow], self::full_name($beneficiary));

                $num++;
                $curRow++;
            }


        } elseif ($exportFormat === 'csv') {

        }
        return $sheet;
    }

    protected static function annex_l(Worksheet $sheet, mixed $batch, string $exportFormat): Worksheet
    {
        ## Set Orientation to LANDSCAPE
        ## Set Page Size to A4
        ## Fit to Width
        ## Not Fit to Height
        $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(0);

        $beneficiaries = Beneficiary::where('batches_id', $batch->id)
            ->get();
        $implementation = Implementation::whereHas('batch', function ($q) use ($batch) {
            $q->where('batches.implementations_id', $batch->implementations_id);
        })
            ->first();

        # The table header attributes
        $columnHeaders = [
            'No.',
            'TUPAD ID Number',
            'First Name',
            'Middle Name',
            'Last Name',
            'Extension Name',
            'Street',
            'BRGY.',
            'City/Municipality',
            'Province',
            'Contact No.',
            'Sex',
            'Birthdate (YYYY/MM/DD)',
            'No. of Days of Work',
            'Amount of Wages',
            'Total Amount',

        ];

        # It's basically a global row index and max column that aggregates every row generated
        # Think of it as like an interpreter where it generates rows line by line. 
        # Pretty helpful if you want to move the whole sheet by 1 or more rows.
        $curRow = 1;
        $maxCol = sizeof($columnHeaders);

        if ($exportFormat === 'xlsx') {

            $sheet->getColumnDimension('A')->setWidth(5.14);
            $sheet->getColumnDimension('B')->setWidth(17.29);
            $sheet->getColumnDimension('C')->setWidth(10.86);
            $sheet->getColumnDimension('D')->setWidth(10.86);
            $sheet->getColumnDimension('E')->setWidth(10.86);
            $sheet->getColumnDimension('F')->setWidth(7);
            $sheet->getColumnDimension('G')->setWidth(8.86);
            $sheet->getColumnDimension('H')->setWidth(10);
            $sheet->getColumnDimension('I')->setWidth(10);
            $sheet->getColumnDimension('J')->setWidth(10);
            $sheet->getColumnDimension('K')->setWidth(11.29);
            $sheet->getColumnDimension('L')->setWidth(8);
            $sheet->getColumnDimension('M')->setWidth(11.43);
            $sheet->getColumnDimension('N')->setWidth(9);
            $sheet->getColumnDimension('O')->setWidth(10);
            $sheet->getColumnDimension('P')->setWidth(12);

            # Merge Rows on A1:A2
            $sheet->mergeCells([1, $curRow, $maxCol, $curRow]);
            $sheet->mergeCells([1, $curRow + 1, $maxCol, $curRow + 1]);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow + 1])->getFont()->setSize(11);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow + 1])->getFont()->setBold(true);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow + 1])->getFont()->setName('Arial');
            $sheet->getStyle([1, $curRow, $maxCol, $curRow + 1])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow + 1])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

            # Annex D
            $sheet->setCellValue([1, $curRow], 'Annex L');
            $curRow++;

            # TUPAD Payroll
            $sheet->setCellValue([1, $curRow], 'TUPAD Payroll');
            $curRow++;

            # The Thick Border Line
            $sheet->getRowDimension($curRow)->setRowHeight(height: 6.75);
            $sheet->mergeCells([1, $curRow, $maxCol, $curRow]);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);
            $curRow++;

            $sheet->getRowDimension($curRow)->setRowHeight(height: 6);
            $sheet->mergeCells([1, $curRow, $maxCol, $curRow]);
            $curRow++;

            # Header 2 Merging and Defaults
            $sheet->mergeCells([1, $curRow, $maxCol, $curRow]);
            $sheet->mergeCells([1, $curRow + 1, $maxCol, $curRow + 1]);
            $sheet->mergeCells([1, $curRow + 2, $maxCol, $curRow + 2]);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow + 9])->getFont()->setSize(10);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow + 9])->getFont()->setName('Arial');
            $sheet->getStyle([1, $curRow, $maxCol, $curRow + 3])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow + 3])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

            # Republic of the Philippines
            $sheet->setCellValue([1, $curRow], 'Republic of the Philippines');
            $curRow++;
            # DEPARTMENT OF LABOR AND EMPLOYMENT
            $sheet->setCellValue([1, $curRow], 'DEPARTMENT OF LABOR AND EMPLOYMENT');
            $curRow++;
            # Regional Office No. XI
            $sheet->setCellValue([1, $curRow], 'Regional Office No. XI');
            $curRow++;

            $sheet->getRowDimension($curRow)->setRowHeight(height: 6);
            $sheet->mergeCells([1, $curRow, $maxCol, $curRow]);
            $curRow++;

            $sheet->getStyle([1, $curRow, $maxCol, $curRow + 5])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

            # Name of Service Provider:
            $sheet->setCellValue([1, $curRow], 'Name of Service Provider:');
            $sheet->mergeCells([1, $curRow, 2, $curRow]);
            $sheet->getStyle([3, $curRow, 6, $curRow])->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
            $sheet->mergeCells([3, $curRow, 6, $curRow]);
            $curRow++;

            # Address:
            $sheet->setCellValue([1, $curRow], 'Address:');
            $sheet->mergeCells([1, $curRow, 2, $curRow]);
            $sheet->getStyle([3, $curRow, 6, $curRow])->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
            $sheet->mergeCells([3, $curRow, 6, $curRow]);
            $curRow++;

            # Date (mm/dd/yyyy):
            $sheet->setCellValue([1, $curRow], 'Date (mm/dd/yyyy):');
            $sheet->mergeCells([1, $curRow, 2, $curRow]);
            $sheet->getStyle([3, $curRow, 6, $curRow])->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
            $sheet->mergeCells([3, $curRow, 6, $curRow]);
            $curRow++;

            # Batch No.
            $sheet->setCellValue([1, $curRow], 'Batch No.');
            $sheet->mergeCells([1, $curRow, 2, $curRow]);
            $sheet->getStyle([3, $curRow, 6, $curRow])->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
            $sheet->mergeCells([3, $curRow, 6, $curRow]);
            $curRow++;

            # Period of Work:
            $sheet->setCellValue([1, $curRow], 'Period of Work:');
            $sheet->mergeCells([1, $curRow, 2, $curRow]);
            $sheet->getStyle([3, $curRow, 6, $curRow])->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
            $sheet->mergeCells([3, $curRow, 6, $curRow]);
            $curRow++;

            $sheet->getRowDimension($curRow)->setRowHeight(height: 9);
            $sheet->mergeCells([1, $curRow, $maxCol, $curRow]);
            $curRow++;

            # Stagger Rows -----------------------------------------------------

            # Universal
            $sheet->getRowDimension($curRow)->setRowHeight(height: 29.25);
            $sheet->getRowDimension($curRow + 1)->setRowHeight(height: 29.25);
            $sheet->getRowDimension($curRow + 2)->setRowHeight(height: 29.25);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow + 2])->getAlignment()->setWrapText(true);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow + 2])->getFont()->setSize(8);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow + 2])->getFont()->setName('Arial');
            $sheet->getStyle([1, $curRow, $maxCol, $curRow + 2])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow + 2])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow + 2])->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

            ## Merging Cells
            # No.
            $sheet->setCellValue([1, $curRow], 'No.');
            $sheet->mergeCells([1, $curRow, 1, $curRow + 2]);

            # TUPAD ID Number
            $sheet->setCellValue([2, $curRow], 'TUPAD ID Number');
            $sheet->mergeCells([2, $curRow, 2, $curRow + 2]);

            # Receiver
            $sheet->setCellValue([3, $curRow], 'Receiver');
            $sheet->mergeCells([3, $curRow, 13, $curRow]);

            # Address
            $sheet->setCellValue([7, $curRow + 1], 'Address');
            $sheet->mergeCells([7, $curRow + 1, 10, $curRow + 1]);

            # Other Cells...
            $sheet->setCellValue([3, $curRow + 1], 'First Name');
            $sheet->mergeCells([3, $curRow + 1, 3, $curRow + 2]);

            $sheet->setCellValue([4, $curRow + 1], 'Middle Name');
            $sheet->mergeCells([4, $curRow + 1, 4, $curRow + 2]);

            $sheet->setCellValue([5, $curRow + 1], 'Last Name');
            $sheet->mergeCells([5, $curRow + 1, 5, $curRow + 2]);

            $sheet->setCellValue([6, $curRow + 1], 'Extension Name');
            $sheet->mergeCells([6, $curRow + 1, 6, $curRow + 2]);

            $sheet->setCellValue([7, $curRow + 2], 'Street');
            $sheet->setCellValue([8, $curRow + 2], 'Brgy.');
            $sheet->setCellValue([9, $curRow + 2], 'City/ Municipality');
            $sheet->setCellValue([10, $curRow + 2], 'Province');

            $sheet->setCellValue([11, $curRow + 1], 'Contact No.');
            $sheet->mergeCells([11, $curRow + 1, 11, $curRow + 2]);

            $sheet->setCellValue([12, $curRow + 1], 'Sex');
            $sheet->mergeCells([12, $curRow + 1, 12, $curRow + 2]);

            $sheet->setCellValue([13, $curRow + 1], 'Birthdate (YYYY/MM/DD)');
            $sheet->mergeCells([13, $curRow + 1, 13, $curRow + 2]);

            $sheet->setCellValue([14, $curRow], 'No. of Days of Work');
            $sheet->mergeCells([14, $curRow, 14, $curRow + 2]);

            $sheet->setCellValue([15, $curRow], 'Amount of Wages');
            $sheet->mergeCells([15, $curRow, 15, $curRow + 2]);

            $sheet->setCellValue([16, $curRow], 'Total Amount');
            $sheet->mergeCells([16, $curRow, 16, $curRow + 2]);

            ## END of Merging Cells

            # END of Stagger Rows ----------------------------------------------

            $curRow += 3;
            $num = 1;
            $startCurRow = $curRow;
            foreach ($beneficiaries as $beneficiary) {
                $sheet->getRowDimension($curRow)->setRowHeight(25.5);
                $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getAlignment()->setWrapText(true);
                $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getFont()->setSize(9);
                $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getFont()->setName('Arial');
                $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

                # No.
                $sheet->setCellValue([1, $curRow], $num);

                # TUPAD ID Number
                $sheet->setCellValue([2, $curRow], '');

                # First Name
                $sheet->setCellValue([3, $curRow], $beneficiary->first_name);

                # Middle Name
                $sheet->setCellValue([4, $curRow], $beneficiary->middle_name ?? '-');

                # Last Name
                $sheet->setCellValue([5, $curRow], $beneficiary->last_name);

                # Extension Name
                $sheet->setCellValue([6, $curRow], $beneficiary->extension_name ?? '-');

                # Street
                $sheet->setCellValue([7, $curRow], '');

                # Brgy.
                $sheet->setCellValue([8, $curRow], strtoupper($beneficiary->barangay_name));

                # City/Municipality
                $sheet->setCellValue([9, $curRow], strtoupper($beneficiary->city_municipality));

                # Province
                $sheet->setCellValue([10, $curRow], strtoupper($beneficiary->province));

                # Contact No.
                $sheet->setCellValue([11, $curRow], "0" . substr($beneficiary->contact_num, 3));

                # Sex
                $sheet->setCellValue([12, $curRow], strtoupper(substr($beneficiary->sex, 0, 1)));

                # Birthdate
                $sheet->setCellValue([13, $curRow], Carbon::parse($beneficiary->birthdate)->format('Y/m/d'));

                # No. of Days of Work
                $sheet->setCellValue([14, $curRow], $implementation->days_of_work);

                # Amount of Wages
                $sheet->setCellValueExplicit([15, $curRow], MoneyFormat::mask($implementation->minimum_wage), DataType::TYPE_NUMERIC);

                # Total Amount
                $sheet->setCellValueExplicit([16, $curRow], '=O' . $curRow . '*N' . $curRow, DataType::TYPE_FORMULA);

                $num++;
                $curRow++;
            }

            # SUM of Total Amount
            $sheet->getRowDimension($curRow)->setRowHeight(25.5);
            $sheet->mergeCells([1, $curRow, $maxCol, $curRow]);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getAlignment()->setWrapText(true);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getFont()->setSize(8);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getFont()->setName('Arial');
            $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow + 10])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->setCellValueExplicit([1, $curRow], '=SUM(P' . $startCurRow . ':N' . $curRow - 1 . ')', DataType::TYPE_FORMULA);
            $curRow++;

            $sheet->getRowDimension($curRow)->setRowHeight(height: 15);
            $sheet->mergeCells([1, $curRow, $maxCol, $curRow]);
            $curRow++;

            # Notes
            $sheet->setCellValue([1, $curRow], 'Note: A proof that the eligible beneficiary or his/her authorized representative claimed the wages as full compensation for the services rendered, sich as acknowledge,emt reciept, withrawal slip, receive money form, among others from the digital payment service provider with the signature of the claimant shall be submitted to the DOLE Regional/Provincial/Field Offices upon liquidation.');
            $sheet->getStyle([1, $curRow, $maxCol, $curRow + 7])->getAlignment()->setWrapText(true);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getFont()->setSize(9);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow + 7])->getFont()->setName('Arial');
            $sheet->getStyle([1, $curRow, $maxCol, $curRow + 1])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow + 1])->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
            $sheet->getRowDimension($curRow)->setRowHeight(height: 15);
            $sheet->getRowDimension($curRow + 1)->setRowHeight(height: 9);
            $sheet->mergeCells([1, $curRow, $maxCol, $curRow + 1]);
            $curRow += 2;

            $sheet->getRowDimension($curRow)->setRowHeight(height: 15);
            $sheet->mergeCells([1, $curRow, $maxCol, $curRow]);
            $curRow++;

            $sheet->getStyle([1, $curRow, $maxCol, $curRow + 4])->getFont()->setSize(10);
            $sheet->getRowDimension($curRow)->setRowHeight(height: 59.25);
            $sheet->getStyle([2, $curRow])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle([2, $curRow])->getAlignment()->setVertical(Alignment::VERTICAL_BOTTOM);
            $sheet->setCellValue([2, $curRow], 'Funds Available');

            $sheet->mergeCells([3, $curRow, 5, $curRow]);
            $sheet->getStyle([3, $curRow, 5, $curRow])->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);

            $sheet->setCellValue([11, $curRow], 'I CERTIFY on my official oath that the above Payroll is correct and that the service have been duly rendered');
            $sheet->getStyle([11, $curRow, $maxCol, $curRow])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle([11, $curRow, $maxCol, $curRow])->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
            $sheet->mergeCells([11, $curRow, $maxCol, $curRow + 1]);
            $curRow++;

            $sheet->getStyle([3, $curRow])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->mergeCells([3, $curRow, 5, $curRow]);
            $sheet->setCellValue([3, $curRow], 'Acting Accountant III');
            $curRow++;

            $sheet->mergeCells([12, $curRow, 15, $curRow]);
            $sheet->getStyle([12, $curRow, 15, $curRow])->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
            $curRow++;

            $sheet->getStyle([12, $curRow])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->setCellValue([12, $curRow], 'DOLE-DCFO, Director II');
            $sheet->mergeCells([12, $curRow, 15, $curRow]);
            $curRow++;

        } elseif ($exportFormat === 'csv') {

        }
        return $sheet;
    }

    protected static function annex_l_sign(Worksheet $sheet, mixed $batch, string $exportFormat): Worksheet
    {
        ## Set Orientation to LANDSCAPE
        ## Set Page Size to A4
        ## Fit to Width
        ## Not Fit to Height
        $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(0);

        $beneficiaries = Beneficiary::where('batches_id', $batch->id)
            ->get();
        $implementation = Implementation::whereHas('batch', function ($q) use ($batch) {
            $q->where('batches.implementations_id', $batch->implementations_id);
        })
            ->first();

        # The table header attributes
        $columnHeaders = [
            'No.',
            'TUPAD ID Number',
            'First Name',
            'Middle Name',
            'Last Name',
            'Extension Name',
            'Street',
            'BRGY.',
            'City/Municipality',
            'Province',
            'Contact No.',
            'Sex',
            'Birthdate (YYYY/MM/DD)',
            'No. of Days of Work',
            'Amount of Wages',
            'Total Amount',
            'Signature',
        ];

        # It's basically a global row index and max column that aggregates every row generated
        # Think of it as like an interpreter where it generates rows line by line. 
        # Pretty helpful if you want to move the whole sheet by 1 or more rows.
        $curRow = 1;
        $maxCol = sizeof($columnHeaders);

        if ($exportFormat === 'xlsx') {

            $sheet->getColumnDimension('A')->setWidth(5.14);
            $sheet->getColumnDimension('B')->setWidth(17.29);
            $sheet->getColumnDimension('C')->setWidth(10.86);
            $sheet->getColumnDimension('D')->setWidth(10.86);
            $sheet->getColumnDimension('E')->setWidth(10.86);
            $sheet->getColumnDimension('F')->setWidth(7);
            $sheet->getColumnDimension('G')->setWidth(8.86);
            $sheet->getColumnDimension('H')->setWidth(10);
            $sheet->getColumnDimension('I')->setWidth(10);
            $sheet->getColumnDimension('J')->setWidth(10);
            $sheet->getColumnDimension('K')->setWidth(11.29);
            $sheet->getColumnDimension('L')->setWidth(8);
            $sheet->getColumnDimension('M')->setWidth(11.43);
            $sheet->getColumnDimension('N')->setWidth(9);
            $sheet->getColumnDimension('O')->setWidth(10);
            $sheet->getColumnDimension('P')->setWidth(12);
            $sheet->getColumnDimension('Q')->setWidth(31.57);

            # Merge Rows on A1:A2
            $sheet->mergeCells([1, $curRow, $maxCol, $curRow]);
            $sheet->mergeCells([1, $curRow + 1, $maxCol, $curRow + 1]);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow + 1])->getFont()->setSize(11);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow + 1])->getFont()->setBold(true);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow + 1])->getFont()->setName('Arial');
            $sheet->getStyle([1, $curRow, $maxCol, $curRow + 1])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow + 1])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

            # Annex D
            $sheet->setCellValue([1, $curRow], 'Annex L');
            $curRow++;

            # TUPAD Payroll
            $sheet->setCellValue([1, $curRow], 'TUPAD Payroll');
            $curRow++;

            # The Thick Border Line
            $sheet->getRowDimension($curRow)->setRowHeight(height: 6.75);
            $sheet->mergeCells([1, $curRow, $maxCol, $curRow]);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);
            $curRow++;

            $sheet->getRowDimension($curRow)->setRowHeight(height: 6);
            $sheet->mergeCells([1, $curRow, $maxCol, $curRow]);
            $curRow++;

            # Header 2 Merging and Defaults
            $sheet->mergeCells([1, $curRow, $maxCol, $curRow]);
            $sheet->mergeCells([1, $curRow + 1, $maxCol, $curRow + 1]);
            $sheet->mergeCells([1, $curRow + 2, $maxCol, $curRow + 2]);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow + 9])->getFont()->setSize(10);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow + 9])->getFont()->setName('Arial');
            $sheet->getStyle([1, $curRow, $maxCol, $curRow + 3])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow + 3])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

            # Republic of the Philippines
            $sheet->setCellValue([1, $curRow], 'Republic of the Philippines');
            $curRow++;
            # DEPARTMENT OF LABOR AND EMPLOYMENT
            $sheet->setCellValue([1, $curRow], 'DEPARTMENT OF LABOR AND EMPLOYMENT');
            $curRow++;
            # Regional Office No. XI
            $sheet->setCellValue([1, $curRow], 'Regional Office No. XI');
            $curRow++;

            $sheet->getRowDimension($curRow)->setRowHeight(height: 6);
            $sheet->mergeCells([1, $curRow, $maxCol, $curRow]);
            $curRow++;

            $sheet->getStyle([1, $curRow, $maxCol, $curRow + 5])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

            # Name of Service Provider:
            $sheet->setCellValue([1, $curRow], 'Name of Service Provider:');
            $sheet->mergeCells([1, $curRow, 2, $curRow]);
            $sheet->getStyle([3, $curRow, 6, $curRow])->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
            $sheet->mergeCells([3, $curRow, 6, $curRow]);
            $curRow++;

            # Address:
            $sheet->setCellValue([1, $curRow], 'Address:');
            $sheet->mergeCells([1, $curRow, 2, $curRow]);
            $sheet->getStyle([3, $curRow, 6, $curRow])->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
            $sheet->mergeCells([3, $curRow, 6, $curRow]);
            $curRow++;

            # Date (mm/dd/yyyy):
            $sheet->setCellValue([1, $curRow], 'Date (mm/dd/yyyy):');
            $sheet->mergeCells([1, $curRow, 2, $curRow]);
            $sheet->getStyle([3, $curRow, 6, $curRow])->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
            $sheet->mergeCells([3, $curRow, 6, $curRow]);
            $curRow++;

            # Batch No.
            $sheet->setCellValue([1, $curRow], 'Batch No.');
            $sheet->mergeCells([1, $curRow, 2, $curRow]);
            $sheet->getStyle([3, $curRow, 6, $curRow])->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
            $sheet->mergeCells([3, $curRow, 6, $curRow]);
            $curRow++;

            # Period of Work:
            $sheet->setCellValue([1, $curRow], 'Period of Work:');
            $sheet->mergeCells([1, $curRow, 2, $curRow]);
            $sheet->getStyle([3, $curRow, 6, $curRow])->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
            $sheet->mergeCells([3, $curRow, 6, $curRow]);
            $curRow++;

            $sheet->getRowDimension($curRow)->setRowHeight(height: 9);
            $sheet->mergeCells([1, $curRow, $maxCol, $curRow]);
            $curRow++;

            # Stagger Rows -----------------------------------------------------

            # Universal
            $sheet->getRowDimension($curRow)->setRowHeight(height: 29.25);
            $sheet->getRowDimension($curRow + 1)->setRowHeight(height: 29.25);
            $sheet->getRowDimension($curRow + 2)->setRowHeight(height: 29.25);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow + 2])->getAlignment()->setWrapText(true);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow + 2])->getFont()->setSize(8);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow + 2])->getFont()->setName('Arial');
            $sheet->getStyle([1, $curRow, $maxCol, $curRow + 2])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow + 2])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow + 2])->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

            ## Merging Cells
            # No.
            $sheet->setCellValue([1, $curRow], 'No.');
            $sheet->mergeCells([1, $curRow, 1, $curRow + 2]);

            # TUPAD ID Number
            $sheet->setCellValue([2, $curRow], 'TUPAD ID Number');
            $sheet->mergeCells([2, $curRow, 2, $curRow + 2]);

            # Receiver
            $sheet->setCellValue([3, $curRow], 'Receiver');
            $sheet->mergeCells([3, $curRow, 13, $curRow]);

            # Address
            $sheet->setCellValue([7, $curRow + 1], 'Address');
            $sheet->mergeCells([7, $curRow + 1, 10, $curRow + 1]);

            # Other Cells...
            $sheet->setCellValue([3, $curRow + 1], 'First Name');
            $sheet->mergeCells([3, $curRow + 1, 3, $curRow + 2]);

            $sheet->setCellValue([4, $curRow + 1], 'Middle Name');
            $sheet->mergeCells([4, $curRow + 1, 4, $curRow + 2]);

            $sheet->setCellValue([5, $curRow + 1], 'Last Name');
            $sheet->mergeCells([5, $curRow + 1, 5, $curRow + 2]);

            $sheet->setCellValue([6, $curRow + 1], 'Extension Name');
            $sheet->mergeCells([6, $curRow + 1, 6, $curRow + 2]);

            $sheet->setCellValue([7, $curRow + 2], 'Street');
            $sheet->setCellValue([8, $curRow + 2], 'Brgy.');
            $sheet->setCellValue([9, $curRow + 2], 'City/ Municipality');
            $sheet->setCellValue([10, $curRow + 2], 'Province');

            $sheet->setCellValue([11, $curRow + 1], 'Contact No.');
            $sheet->mergeCells([11, $curRow + 1, 11, $curRow + 2]);

            $sheet->setCellValue([12, $curRow + 1], 'Sex');
            $sheet->mergeCells([12, $curRow + 1, 12, $curRow + 2]);

            $sheet->setCellValue([13, $curRow + 1], 'Birthdate (YYYY/MM/DD)');
            $sheet->mergeCells([13, $curRow + 1, 13, $curRow + 2]);

            $sheet->setCellValue([14, $curRow], 'No. of Days of Work');
            $sheet->mergeCells([14, $curRow, 14, $curRow + 2]);

            $sheet->setCellValue([15, $curRow], 'Amount of Wages');
            $sheet->mergeCells([15, $curRow, 15, $curRow + 2]);

            $sheet->setCellValue([16, $curRow], 'Total Amount');
            $sheet->mergeCells([16, $curRow, 16, $curRow + 2]);

            $sheet->setCellValue([17, $curRow], 'Signature');
            $sheet->mergeCells([17, $curRow, 17, $curRow + 2]);

            ## END of Merging Cells

            # END of Stagger Rows ----------------------------------------------

            $curRow += 3;
            $num = 1;
            $startCurRow = $curRow;
            foreach ($beneficiaries as $beneficiary) {
                $sheet->getRowDimension($curRow)->setRowHeight(25.5);
                $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getAlignment()->setWrapText(true);
                $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getFont()->setSize(9);
                $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getFont()->setName('Arial');
                $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

                # No.
                $sheet->setCellValue([1, $curRow], $num);

                # TUPAD ID Number
                $sheet->setCellValue([2, $curRow], '');

                # First Name
                $sheet->setCellValue([3, $curRow], $beneficiary->first_name);

                # Middle Name
                $sheet->setCellValue([4, $curRow], $beneficiary->middle_name ?? '-');

                # Last Name
                $sheet->setCellValue([5, $curRow], $beneficiary->last_name);

                # Extension Name
                $sheet->setCellValue([6, $curRow], $beneficiary->extension_name ?? '-');

                # Street
                $sheet->setCellValue([7, $curRow], '');

                # Brgy.
                $sheet->setCellValue([8, $curRow], strtoupper($beneficiary->barangay_name));

                # City/Municipality
                $sheet->setCellValue([9, $curRow], strtoupper($beneficiary->city_municipality));

                # Province
                $sheet->setCellValue([10, $curRow], strtoupper($beneficiary->province));

                # Contact No.
                $sheet->setCellValue([11, $curRow], "0" . substr($beneficiary->contact_num, 3));

                # Sex
                $sheet->setCellValue([12, $curRow], strtoupper(substr($beneficiary->sex, 0, 1)));

                # Birthdate
                $sheet->setCellValue([13, $curRow], Carbon::parse($beneficiary->birthdate)->format('Y/m/d'));

                # No. of Days of Work
                $sheet->setCellValue([14, $curRow], $implementation->days_of_work);

                # Amount of Wages
                $sheet->setCellValueExplicit([15, $curRow], MoneyFormat::mask($implementation->minimum_wage), DataType::TYPE_NUMERIC);

                # Total Amount
                $sheet->setCellValueExplicit([16, $curRow], '=O' . $curRow . '*N' . $curRow, DataType::TYPE_FORMULA);

                $num++;
                $curRow++;
            }

            # SUM of Total Amount
            $sheet->getRowDimension($curRow)->setRowHeight(25.5);
            $sheet->mergeCells([1, $curRow, $maxCol - 1, $curRow]);
            $sheet->getStyle([1, $curRow, $maxCol - 1, $curRow])->getAlignment()->setWrapText(true);
            $sheet->getStyle([1, $curRow, $maxCol - 1, $curRow])->getFont()->setSize(8);
            $sheet->getStyle([1, $curRow, $maxCol - 1, $curRow])->getFont()->setName('Arial');
            $sheet->getStyle([1, $curRow, $maxCol - 1, $curRow])->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $sheet->getStyle([1, $curRow, $maxCol - 1, $curRow])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle([1, $curRow, $maxCol - 1, $curRow + 10])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->setCellValueExplicit([1, $curRow], '=SUM(P' . $startCurRow . ':N' . $curRow - 1 . ')', DataType::TYPE_FORMULA);
            $curRow++;

            $sheet->getRowDimension($curRow)->setRowHeight(height: 15);
            $sheet->mergeCells([1, $curRow, $maxCol, $curRow]);
            $curRow++;

            # Notes
            $sheet->setCellValue([1, $curRow], 'Note: A proof that the eligible beneficiary or his/her authorized representative claimed the wages as full compensation for the services rendered, sich as acknowledge,emt reciept, withrawal slip, receive money form, among others from the digital payment service provider with the signature of the claimant shall be submitted to the DOLE Regional/Provincial/Field Offices upon liquidation.');
            $sheet->getStyle([1, $curRow, $maxCol - 1, $curRow + 7])->getAlignment()->setWrapText(true);
            $sheet->getStyle([1, $curRow, $maxCol - 1, $curRow])->getFont()->setSize(9);
            $sheet->getStyle([1, $curRow, $maxCol - 1, $curRow + 7])->getFont()->setName('Arial');
            $sheet->getStyle([1, $curRow, $maxCol - 1, $curRow + 1])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle([1, $curRow, $maxCol - 1, $curRow + 1])->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
            $sheet->getRowDimension($curRow)->setRowHeight(height: 15);
            $sheet->getRowDimension($curRow + 1)->setRowHeight(height: 9);
            $sheet->mergeCells([1, $curRow, $maxCol - 1, $curRow + 1]);
            $curRow += 2;

            $sheet->getRowDimension($curRow)->setRowHeight(height: 15);
            $sheet->mergeCells([1, $curRow, $maxCol - 1, $curRow]);
            $curRow++;

            $sheet->getStyle([1, $curRow, $maxCol - 1, $curRow + 4])->getFont()->setSize(10);
            $sheet->getRowDimension($curRow)->setRowHeight(height: 59.25);
            $sheet->getStyle([2, $curRow])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle([2, $curRow])->getAlignment()->setVertical(Alignment::VERTICAL_BOTTOM);
            $sheet->setCellValue([2, $curRow], 'Funds Available');

            $sheet->mergeCells([3, $curRow, 5, $curRow]);
            $sheet->getStyle([3, $curRow, 5, $curRow])->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);

            $sheet->setCellValue([11, $curRow], 'I CERTIFY on my official oath that the above Payroll is correct and that the service have been duly rendered');
            $sheet->getStyle([11, $curRow, $maxCol - 1, $curRow])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle([11, $curRow, $maxCol - 1, $curRow])->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
            $sheet->mergeCells([11, $curRow, $maxCol - 1, $curRow + 1]);
            $curRow++;

            $sheet->getStyle([3, $curRow])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->mergeCells([3, $curRow, 5, $curRow]);
            $sheet->setCellValue([3, $curRow], 'Acting Accountant III');
            $curRow++;

            $sheet->mergeCells([12, $curRow, 15, $curRow]);
            $sheet->getStyle([12, $curRow, 15, $curRow])->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
            $curRow++;

            $sheet->getStyle([12, $curRow])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->setCellValue([12, $curRow], 'DOLE-DCFO, Director II');
            $sheet->mergeCells([12, $curRow, 15, $curRow]);
            $curRow++;

        } elseif ($exportFormat === 'csv') {

        }
        return $sheet;
    }

    protected static function full_name($person)
    {
        $full_name = $person->first_name;

        if ($person->middle_name) {
            $full_name .= ' ' . $person->middle_name;
        }

        $full_name .= ' ' . $person->last_name;

        if ($person->extension_name) {
            $full_name .= ' ' . $person->extension_name;
        }

        return $full_name;
    }

    protected static function address($person)
    {
        $address = 'Brgy. ' . $person->barangay_name;
        $address .= ', ' . $person->district;
        $address .= ', ' . $person->city_municipality;
        $address .= ', ' . $person->province;

        return $address;
    }
}