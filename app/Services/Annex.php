<?php

namespace App\Services;
use App\Models\Batch;
use App\Models\Beneficiary;
use App\Models\Implementation;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\NamedRange;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Protection;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Annex
{
    public static function sampleImport(Spreadsheet $spreadsheet, int $number_of_rows, mixed $batch, mixed $implementation)
    {
        ## Retrieve the active sheet
        $sheet = $spreadsheet->getActiveSheet();

        $sheet1 = self::initializeList($spreadsheet, $batch, $implementation);

        $spreadsheet->setActiveSheetIndex(0);

        $implementation = Implementation::find($batch->implementations_id);
        $province = $implementation->province;
        $city = $implementation->city_municipality;
        $district = $implementation->district;

        ## Set Orientation to LANDSCAPE
        ## Set Page Size to A4
        ## Fit to Width
        ## Not Fit to Height
        $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(0);

        # Set Worksheet Name & Color
        $sheet->setTitle('Beneficiaries');
        $sheet->getTabColor()->setRGB('FEFD0D'); // Green tab color

        ## The table header attributes
        $columnHeaders = [
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
            'Spouse First Name',
            'Spouse Middle Name',
            'Spouse Last Name',
            'Spouse Extension Name',
            'Person with Disability',
        ];

        ## Size of the table columns depending on the amount of header attributes
        $maxCol = sizeof($columnHeaders);

        ## It's basically a global row index that aggregates every row generated
        ## Think of it as like an interpreter where it generates rows line by line. 
        ## Pretty helpful if you want to move the whole sheet by 1 or more rows.
        $curRow = 1;

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
        $sheet->getColumnDimension('AB')->setWidth(7.43);

        # TOP HEADER (A1:A3)
        $sheet->getStyle([1, $curRow])->getFont()->setSize(16);
        $sheet->setCellValue([1, $curRow], 'Standard TU-Efficient Importing Format (STIF)');
        $sheet->getStyle([1, $curRow])->getFont()->setBold(true);
        $sheet->getStyle([1, $curRow])->getFont()->setName('Arial');
        $sheet->mergeCells([1, $curRow, $maxCol, $curRow + 2]);
        $sheet->getStyle([1, $curRow])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle([1, $curRow])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $curRow += 3;

        # LEGEND SECTION ------------------------------------------------------------------------------------------

        $sheet->getStyle([1, $curRow, $maxCol, $curRow + 5])->getFont()->setSize(10);
        $sheet->getStyle([1, $curRow, $maxCol, $curRow + 5])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle([1, $curRow, $maxCol, $curRow + 5])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle([1, $curRow, $maxCol, $curRow + 5])->getFont()->setName('Arial');

        # 
        $sheet->mergeCells([1, $curRow, $maxCol, $curRow]);
        $sheet->setCellValue(
            [1, $curRow],
            'This is a standard format for the sole purpose of easing the importing process of the focal and coordinators.'
        );
        $curRow += 2;

        $sheet->mergeCells([1, $curRow, 2, $curRow + 3]);
        $sheet->getStyle([1, $curRow])->getFont()->setBold(true);
        $sheet->getStyle([1, $curRow])->getFont()->setSize(20);
        $sheet->getStyle([1, $curRow])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle([1, $curRow])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->setCellValue([1, $curRow], 'Legend:');

        # Light Lime
        $sheet->mergeCells([3, $curRow, 6, $curRow]);
        $sheet->getStyle([3, $curRow, 6, $curRow])->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('F0FEC3');
        $sheet->setCellValue([3, $curRow], 'Required input fields / Should follow strict format.');

        # Light Indigo
        $sheet->mergeCells([3, $curRow + 1, 6, $curRow + 1]);
        $sheet->getStyle([3, $curRow + 1, 6, $curRow + 1])->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('7B85C7');
        $sheet->setCellValue([3, $curRow + 1], 'Dropdown List / Also required and should not be blank.');

        # Light Maroon
        $sheet->mergeCells([3, $curRow + 2, 6, $curRow + 2]);
        $sheet->getStyle([3, $curRow + 2, 6, $curRow + 2])->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('E3C2BD');
        $sheet->setCellValue([3, $curRow + 2], 'Do not modify or delete as they are important.');

        # Grey
        $sheet->mergeCells([3, $curRow + 3, 6, $curRow + 3]);
        $sheet->getStyle([3, $curRow + 3, 6, $curRow + 3])->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('E2E2E3');
        $sheet->setCellValue([3, $curRow + 3], '(optional) Can be left empty, N/A, "-", or NONE.');

        $curRow += 5;

        # LEGEND SECTION ------------------------------------------------------------------------------------------

        # INSTRUCTIONS SECTION ------------------------------------------------------------------------------------------

        $sheet->mergeCells('H6:J9');
        $sheet->getStyle('H6')->getAlignment()->setWrapText(true);
        $sheet->getStyle('H6')->getFont()->setBold(true);
        $sheet->getStyle('H6')->getFont()->setSize(16);
        $sheet->getStyle('H6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('H6')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('H6', 'ADDITIONAL INFO:');

        # Guide to Adding barangays
        $sheet->mergeCells('K6:P7');
        $sheet->getStyle('K6')->getAlignment()->setWrapText(true);
        $sheet->getStyle('K6:P7')->getFont()->getColor()->setRGB('D2CFAA');
        $sheet->getStyle('K6:P7')->getFill()->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('7A7100');
        $sheet->setCellValue('K6', 'For the \'BRGY.\' field, you can check the list of valid barangays on \'Lists (do not modify\' worksheet based on its district (F1 to H1).');

        # Guide to Optional fields
        $sheet->mergeCells('K8:P9');
        $sheet->getStyle('K8')->getAlignment()->setWrapText(true);
        $sheet->getStyle('K8:P9')->getFont()->getColor()->setRGB('B7DBFF');
        $sheet->getStyle('K8:P9')->getFill()->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('1A62AA');
        $sheet->setCellValue('K8', 'For optional fields, you can choose to leave it blank or type \'-\' and it will pick up a default value.');
        # INSTRUCTIONS SECTION ------------------------------------------------------------------------------------------

        # Write the Column Headers and set its colors and borders
        $sheet->getRowDimension($curRow)->setRowHeight(75);
        $sheet->fromArray($columnHeaders, null, 'A' . $curRow);
        $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getFont()->setSize(7);
        $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getFont()->setName('Arial');
        $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getFont()->setBold(true);
        $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getAlignment()->setHorizontal(horizontalAlignment: Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('E3C2BD');
        $curRow++;

        for ($num = 1; $num <= $number_of_rows; $num++) {

            $sheet->getRowDimension($curRow)->setRowHeight(29.25);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getAlignment()->setWrapText(true);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getFont()->setName('Arial');
            $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
            $sheet->getStyle([1, $curRow])->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB('E3C2BD');

            # No. 
            $sheet->setCellValue([1, $curRow], $num);

            # City
            $sheet->setCellValue([8, $curRow], mb_strtoupper($city, 'UTF-8'));

            # Province
            $sheet->setCellValue([9, $curRow], mb_strtoupper(substr($province, 0, 5) === 'DAVAO' ? substr($province, 6) : $province, 'UTF-8'));

            if (!$implementation->is_sectoral) {
                # District
                $sheet->setCellValue([10, $curRow], mb_strtoupper($district, 'UTF-8'));

                # Barangay
                $sheet->setCellValue([7, $curRow], mb_strtoupper($batch->barangay_name, 'UTF-8'));
            }

            # Beneficiary Type
            $sheet->setCellValue([15, $curRow], 'UNDEREMPLOYED');

            # Self-Employment
            $sheet->setCellValue([22, $curRow], 'NO');

            # is PWD
            $sheet->setCellValue([28, $curRow], 'NO');

            foreach ($columnHeaders as $key => $header) {

                # First to Extension
                if (in_array($key + 1, ['2', '4', '6', '7', '12', '13', '15', '16', '19', '20', '21'])) {
                    $sheet->getStyle([$key + 1, $curRow])->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setRGB('F0FEC3');
                }

                # Dropdown Lists
                if (in_array($key + 1, ['10', '11', '17', '18', '22', '28'])) {
                    $sheet->getStyle([$key + 1, $curRow])->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setRGB('7B85C7');
                }

                # Shouldn't modify or delete
                if (in_array($key + 1, ['8', '9',])) {
                    $sheet->getStyle([$key + 1, $curRow])->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setRGB('E3C2BD');
                }

                # Nullable
                if (in_array($key + 1, ['3', '5', '14', '23', '24', '25', '26', '27'])) {
                    $sheet->getStyle([$key + 1, $curRow])->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setRGB('E2E2E3');
                }

                if (!$implementation->is_sectoral) {
                    if ((1 + $key) === 7 || (1 + $key) === 10) {
                        $sheet->getStyle([$key + 1, $curRow])->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('E3C2BD');
                    }

                } elseif ($implementation->is_sectoral) {
                    # Districts
                    if ((1 + $key) === 10) {
                        $dataValidation = $sheet->getCell([1 + $key, $curRow])->getDataValidation();
                        $dataValidation->setType(DataValidation::TYPE_LIST);
                        $dataValidation->setErrorStyle(DataValidation::STYLE_STOP);
                        $dataValidation->setAllowBlank(false);
                        $dataValidation->setShowInputMessage(true);
                        $dataValidation->setShowErrorMessage(true);
                        $dataValidation->setShowDropDown(true);
                        $dataValidation->setFormula1('\'Lists (do not modify)\'!$E$2:$E$4');
                    }
                }

                # Type of ID
                if ((1 + $key) === 11) {
                    $dataValidation = $sheet->getCell([1 + $key, $curRow])->getDataValidation();
                    $dataValidation->setType(DataValidation::TYPE_LIST);
                    $dataValidation->setErrorStyle(DataValidation::STYLE_STOP);
                    $dataValidation->setAllowBlank(false);
                    $dataValidation->setShowInputMessage(true);
                    $dataValidation->setShowErrorMessage(true);
                    $dataValidation->setShowDropDown(true);
                    $dataValidation->setFormula1('\'Lists (do not modify)\'!$A$2:$A$19');
                }

                # Sex
                elseif ((1 + $key) === 17) {
                    $dataValidation = $sheet->getCell([1 + $key, $curRow])->getDataValidation();
                    $dataValidation->setType(DataValidation::TYPE_LIST);
                    $dataValidation->setErrorStyle(DataValidation::STYLE_STOP);
                    $dataValidation->setAllowBlank(false);
                    $dataValidation->setShowInputMessage(true);
                    $dataValidation->setShowErrorMessage(true);
                    $dataValidation->setShowDropDown(true);
                    $dataValidation->setFormula1('\'Lists (do not modify)\'!$B$2:$B$3');
                }

                # Civil Status
                elseif ((1 + $key) === 18) {
                    $dataValidation = $sheet->getCell([1 + $key, $curRow])->getDataValidation();
                    $dataValidation->setType(DataValidation::TYPE_LIST);
                    $dataValidation->setErrorStyle(DataValidation::STYLE_STOP);
                    $dataValidation->setAllowBlank(false);
                    $dataValidation->setShowInputMessage(true);
                    $dataValidation->setShowErrorMessage(true);
                    $dataValidation->setShowDropDown(true);
                    $dataValidation->setFormula1('\'Lists (do not modify)\'!$C$2:$C$5');
                }

                # Interested in Self-Employment or Wage Employment && is PWD
                elseif ((1 + $key) === 22 || (1 + $key) === 28) {
                    $dataValidation = $sheet->getCell([1 + $key, $curRow])->getDataValidation();
                    $dataValidation->setType(DataValidation::TYPE_LIST);
                    $dataValidation->setErrorStyle(DataValidation::STYLE_STOP);
                    $dataValidation->setAllowBlank(false);
                    $dataValidation->setShowInputMessage(true);
                    $dataValidation->setShowErrorMessage(true);
                    $dataValidation->setShowDropDown(true);
                    $dataValidation->setFormula1('\'Lists (do not modify)\'!$D$2:$D$3');
                }

                # Barangays
                // elseif ((1 + $key) === 7) {
                //     $dataValidation = $sheet->getCell([1 + $key, $curRow])->getDataValidation();
                //     $dataValidation->setType(DataValidation::TYPE_LIST);
                //     $dataValidation->setErrorStyle(DataValidation::STYLE_STOP);
                //     $dataValidation->setAllowBlank(false);
                //     $dataValidation->setShowInputMessage(true);
                //     $dataValidation->setShowErrorMessage(true);
                //     $dataValidation->setShowDropDown(true);
                //     $dataValidation->setFormula1("=INDIRECT(J{$curRow})");
                // }

            }

            $curRow++;
        }
        # Globally set font sizes
        $sheet->getStyle([1, $curRow - $number_of_rows, $maxCol, $curRow])->getFont()->setSize(10);
        # Sets the Wrap Text function to true
        $sheet->getStyle([1, $curRow - $number_of_rows - 1, $maxCol, $curRow])->getAlignment()->setWrapText(true);

        return $spreadsheet;
    }

    public static function export(Spreadsheet $spreadsheet, mixed $batch, array|string $exportType, string $exportFormat): Spreadsheet
    {
        # Types of Annexes: annex_e1, annex_e2, annex_j2, annex_l, annex_l_sign
        if ((is_array($exportType) && !empty($exportType['annex_e1'])) || $exportType === 'annex_e1') {
            $sheet1 = new Worksheet($spreadsheet, 'ANNEX E-1 - COS');
            $spreadsheet->addSheet($sheet1);
            $sheet1->getTabColor()->setRGB('FF0000'); // Red tab color
            $sheet1 = self::annex_e1($sheet1, $batch, $exportFormat);
        }

        if ((is_array($exportType) && !empty($exportType['annex_e2'])) || $exportType === 'annex_e2') {
            $sheet2 = new Worksheet($spreadsheet, 'ANNEX E-2 - COS(co-partner)');
            $spreadsheet->addSheet($sheet2);
            $sheet2->getTabColor()->setRGB('FF0000'); // Red tab color
            $sheet2 = self::annex_e2($sheet2, $batch, $exportFormat);
        }

        if ((is_array($exportType) && !empty($exportType['annex_j2'])) || $exportType === 'annex_j2') {
            $sheet3 = new Worksheet($spreadsheet, 'ANNEX J-2 - Attendance Sheet');
            $spreadsheet->addSheet($sheet3);
            $sheet3->getTabColor()->setRGB('4472C4'); // Blue tab color
            $sheet3 = self::annex_j2($sheet3, $batch, $exportFormat);
        }

        if ((is_array($exportType) && !empty($exportType['annex_l'])) || $exportType === 'annex_l') {
            $sheet4 = new Worksheet($spreadsheet, 'ANNEX L - Payroll');
            $spreadsheet->addSheet($sheet4);
            $sheet4->getTabColor()->setRGB('70AD47'); // Green tab color
            $sheet4 = self::annex_l($sheet4, $batch, $exportFormat);
        }

        if ((is_array($exportType) && !empty($exportType['annex_l_sign'])) || $exportType === 'annex_l_sign') {
            $sheet5 = new Worksheet($spreadsheet, 'ANNEX L - Payroll with Sign');
            $spreadsheet->addSheet($sheet5);
            $sheet5->getTabColor()->setRGB('70AD47'); // Green tab color
            $sheet5 = self::annex_l_sign($sheet5, $batch, $exportFormat);
        }

        $spreadsheet->removeSheetByIndex(0);
        $spreadsheet->setActiveSheetIndex(0);

        return $spreadsheet;
    }

    protected static function fetchBeneficiaries(Batch $batch)
    {
        $beneficiaries = Beneficiary::where('batches_id', $batch->id)
            ->orderBy('last_name', 'asc')
            ->get();
        return $beneficiaries;
    }

    protected static function initializeList(Spreadsheet $spreadsheet, mixed $batch, mixed $implementation)
    {
        $sheet = new Worksheet($spreadsheet, 'Lists (do not modify)');
        $spreadsheet->addSheet($sheet);
        $sheet->getTabColor()->setRGB('141619'); // Near-Black color

        $type_of_id = [
            'Barangay ID',
            'Barangay Certificate',
            'e-Card / UMID',
            "Driver's License",
            'Passport',
            'Phil-health ID',
            'Philippine Postal ID',
            'SSS ID',
            "COMELEC / Voter's ID / COMELEC Registration Form",
            'Philippine Identification (PhilID / ePhilID)',
            'NBI Clearance',
            'Pantawid Pamilya Pilipino Program (4Ps) ID',
            'Integrated Bar of the Philippines (IBP) ID',
            'BIR (TIN)',
            'Pag-ibig ID',
            'Solo Parent ID',
            'Senior Citizen ID',
            'Person\'s With Disability (PWD) ID',
        ];

        $sex = [
            'M',
            'F',
        ];

        $civil_status = [
            'S',
            'M',
            'SP',
            'W'
        ];

        $yes_no = [
            'YES',
            'NO'
        ];

        $initDistricts = Districts::getDistricts($implementation?->city_municipality, $implementation?->province);
        $districts = [];
        foreach ($initDistricts as $district) {
            $districts[] = mb_strtoupper(substr($district, 0, 3), "UTF-8");
        }
        $b1 = [];
        $b2 = [];
        $b3 = [];
        foreach (Barangays::getBarangays($implementation?->city_municipality, '1st District') as $b) {
            $b1[] = mb_strtoupper($b, "UTF-8");
        }
        foreach (Barangays::getBarangays($implementation?->city_municipality, '2nd District') as $b) {
            $b2[] = mb_strtoupper($b, "UTF-8");
        }
        foreach (Barangays::getBarangays($implementation?->city_municipality, '3rd District') as $b) {
            $b3[] = mb_strtoupper($b, "UTF-8");
        }

        $barangays = [
            '1ST' => $b1,
            '2ND' => $b2,
            '3RD' => $b3,
        ];

        # Type of ID
        $sheet->setCellValue([1, 1], 'Type of ID');
        $sheet->getStyle([1, 1])->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('71717A');
        foreach ($type_of_id as $key => $row) {
            $sheet->setCellValue([1, $key + 2], $row);
        }

        # Sex
        $sheet->setCellValue([2, 1], 'Sex');
        $sheet->getStyle([2, 1])->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('71717A');
        foreach ($sex as $key => $row) {
            $sheet->setCellValue([2, $key + 2], $row);
        }

        # Civil Status
        $sheet->setCellValue([3, 1], 'Civil Status');
        $sheet->getStyle([3, 1])->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('71717A');
        foreach ($civil_status as $key => $row) {
            $sheet->setCellValue([3, $key + 2], $row);
        }

        # Yes No
        $sheet->setCellValue([4, 1], 'IS Questions');
        $sheet->getStyle([4, 1])->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('71717A');
        foreach ($yes_no as $key => $row) {
            $sheet->setCellValue([4, $key + 2], $row);
        }

        # Districts
        $sheet->setCellValue([5, 1], 'Districts');
        $sheet->getStyle([5, 1])->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('71717A');
        foreach ($districts as $key => $row) {
            $sheet->setCellValue([5, $key + 2], $row);
        }

        # Barangays
        $col = 6;
        foreach ($barangays as $district => $subBarangays) {
            $sheet->setCellValue([$col, 1], $district);
            $sheet->getStyle([$col, 1])->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB('71717A');
            $row = 2; // Data starts at row 2
            foreach ($subBarangays as $barangay) {
                $sheet->setCellValue([$col, $row], $barangay);
                $row++;
            }
            $col++;
        }

        // Create Named Ranges for Subcategories
        // $spreadsheet->addNamedRange(new NamedRange('1ST', $sheet, '=\'Lists (do not modify)\'!F2:F55'));
        // $spreadsheet->addNamedRange(new NamedRange('2ND', $sheet, '=\'Lists (do not modify)\'!G2:G47'));
        // $spreadsheet->addNamedRange(new NamedRange('3RD', $sheet, '=\'Lists (do not modify)\'!H2:H83'));

        // # Barangays2
        // $sheet->setCellValue([7, 1], 'Barangays2');
        // $sheet->getStyle([7, 1])->getFill()
        //     ->setFillType(Fill::FILL_SOLID)
        //     ->getStartColor()->setRGB('71717A');
        // foreach ($barangays2 as $key => $row) {
        //     $sheet->setCellValue([7, $key + 2], $row);
        // }

        // # Barangays3
        // $sheet->setCellValue([8, 1], 'Barangays3');
        // $sheet->getStyle([8, 1])->getFill()
        //     ->setFillType(Fill::FILL_SOLID)
        //     ->getStartColor()->setRGB('71717A');
        // foreach ($barangays3 as $key => $row) {
        //     $sheet->setCellValue([8, $key + 2], $row);
        // }

        # OR: Auto-size all columns (for example, columns A to Z)
        foreach (range('A', 'H') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        return $sheet;
    }

    // protected static function annex_d(Worksheet $sheet, mixed $batch, string $exportFormat)
    // {
    //     ## Retrieve the active sheet
    //     $sheet = $spreadsheet->getActiveSheet();
    //     $number_of_rows = $number_of_rows;

    //     ## Set Orientation to LANDSCAPE
    //     ## Set Page Size to A4
    //     ## Fit to Width
    //     ## Not Fit to Height
    //     $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
    //     $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
    //     $sheet->getPageSetup()->setFitToWidth(1);
    //     $sheet->getPageSetup()->setFitToHeight(0);

    //     ## The table header attributes
    //     $headers = [
    //         [
    //             'No.',
    //             'First Name',
    //             'Middle Name',
    //             'Last Name',
    //             'Extension Name',
    //             'Birthdate (YYYY/MM/DD)',
    //             'BRGY.',
    //             'City/Municipality',
    //             'Province',
    //             'District',
    //             'Type of ID',
    //             'ID No.',
    //             'Contact No.',
    //             'E-payment/Account No.',
    //             'Type of Beneficiaries',
    //             'Occupation',
    //             'Sex',
    //             'Civil Status',
    //             'Age',
    //             'Average monthly income',
    //             'Person with Disability',
    //             'Dependent',
    //             'Interested in wage employment or self-employment? (Yes or No)',
    //             'Skills Training Needed',
    //             'First Name',
    //             'Middle Name',
    //             'Last Name',
    //             'Extension Name'
    //         ],
    //     ];

    //     ## Size of the table columns depending on the amount of header attributes
    //     $number_of_cols = sizeof($headers[0]);

    //     ## It's basically a global row index that aggregates every row generated
    //     ## Think of it as like an interpreter where it generates rows line by line. 
    //     ## Pretty helpful if you want to move the whole sheet by 1 or more rows.
    //     $i = 1;

    //     ## It's set for ignoring double row attributes on the header
    //     $excludedColumns = [1, 2, 3, 4, 6, 7, 8, 9, 24, 25, 26, 27];

    //     ## Set default Column widths
    //     $sheet->getColumnDimension('A')->setWidth(4);
    //     $sheet->getColumnDimension('B')->setWidth(18.43);
    //     $sheet->getColumnDimension('C')->setWidth(18.43);
    //     $sheet->getColumnDimension('D')->setWidth(18.43);
    //     $sheet->getColumnDimension('E')->setWidth(8.86);
    //     $sheet->getColumnDimension('F')->setWidth(14);
    //     $sheet->getColumnDimension('G')->setWidth(10.57);
    //     $sheet->getColumnDimension('H')->setWidth(8.71);
    //     $sheet->getColumnDimension('I')->setWidth(8.71);
    //     $sheet->getColumnDimension('J')->setWidth(6.86);
    //     $sheet->getColumnDimension('K')->setWidth(13.14);
    //     $sheet->getColumnDimension('L')->setWidth(17.29);
    //     $sheet->getColumnDimension('M')->setWidth(13.43);
    //     $sheet->getColumnDimension('N')->setWidth(8.86);
    //     $sheet->getColumnDimension('O')->setWidth(10.57);
    //     $sheet->getColumnDimension('P')->setWidth(10.57);
    //     $sheet->getColumnDimension('Q')->setWidth(5.14);
    //     $sheet->getColumnDimension('R')->setWidth(5.14);
    //     $sheet->getColumnDimension('S')->setWidth(5.14);
    //     $sheet->getColumnDimension('T')->setWidth(7.43);
    //     $sheet->getColumnDimension('U')->setWidth(5.14);
    //     $sheet->getColumnDimension('V')->setWidth(19.43);
    //     $sheet->getColumnDimension('W')->setWidth(7.86);
    //     $sheet->getColumnDimension('X')->setWidth(9.43);
    //     $sheet->getColumnDimension('Y')->setWidth(18.43);
    //     $sheet->getColumnDimension('Z')->setWidth(18.43);
    //     $sheet->getColumnDimension('AA')->setWidth(18.43);
    //     $sheet->getColumnDimension('AB')->setWidth(8.86);

    //     # Resize the Column (CANCELLED)
    //     // $columnID = Coordinate::stringFromColumnIndex($colIndex + 1);
    //     // $sheet->getColumnDimension($columnID)->setAutoSize(true);

    //     # A1
    //     $sheet->getStyle([1, $i, 1, $i + 1])->getFont()->setSize(10); # A1:A2
    //     $sheet->setCellValue([1, $i], 'Annex D');
    //     $sheet->getStyle([1, $i])->getFont()->setBold(true);
    //     $sheet->getStyle([1, $i])->getFont()->setName('Arial');
    //     $sheet->mergeCells([1, $i, $number_of_cols, $i]);
    //     $sheet->getStyle([1, $i, $number_of_cols, $i])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    //     $sheet->getStyle([1, $i, $number_of_cols, $i])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

    //     $i++; #2
    //     # A2
    //     $sheet->setCellValue([1, $i, $number_of_cols, $i], 'Profile of TUPAD Beneficiaries');
    //     $sheet->getStyle([1, $i])->getFont()->setBold(true);
    //     $sheet->getStyle([1, $i])->getFont()->setName('Arial');
    //     $sheet->mergeCells([1, $i, $number_of_cols, $i]);
    //     $sheet->getStyle([1, $i, $number_of_cols, $i])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    //     $sheet->getStyle([1, $i, $number_of_cols, $i])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

    //     $i++; #3
    //     # A3
    //     $sheet->setCellValue([1, $i, $number_of_cols, $i], '_____________________________________________________________________________________________________________________________________________________');
    //     $sheet->mergeCells([1, $i, $number_of_cols, $i]);
    //     $sheet->getStyle([1, $i, $number_of_cols, $i])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    //     $sheet->getRowDimension($i)->setRowHeight(6.75);

    //     $i++; #4
    //     #A4
    //     $sheet->getRowDimension($i)->setRowHeight(6);

    //     $i++; #5
    //     # A5:B9 | Set font size to 10
    //     $sheet->getStyle([1, $i, 2, $i + 4])->getFont()->setSize(10); # A5:B9

    //     # A5:B5 | C5:E5
    //     $sheet->setCellValue([1, $i], 'Nature of Project:');
    //     $sheet->getStyle([1, $i])->getFont()->setName('Arial');
    //     $sheet->mergeCells([1, $i, 2, $i]);
    //     $sheet->mergeCells([3, $i, 5, $i]);
    //     $sheet->getStyle([3, $i, 5, $i])->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);

    //     $i++; #6
    //     # A6
    //     $sheet->setCellValue([1, $i], 'DOLE Regional Office:');
    //     $sheet->getStyle([1, $i])->getFont()->setName('Arial');
    //     $sheet->mergeCells([1, $i, 2, $i]);
    //     $sheet->mergeCells([3, $i, 5, $i]);
    //     $sheet->getStyle([3, $i, 5, $i])->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);

    //     $i++; #7
    //     # A7
    //     $sheet->setCellValue([1, $i], 'Province:');
    //     $sheet->getStyle([1, $i])->getFont()->setName('Arial');
    //     $sheet->mergeCells([1, $i, 2, $i]);
    //     $sheet->mergeCells([3, $i, 5, $i]);
    //     $sheet->getStyle([3, $i, 5, $i])->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);

    //     $i++; #8
    //     # A8
    //     $sheet->setCellValue([1, $i], 'Municipality:');
    //     $sheet->getStyle([1, $i])->getFont()->setName('Arial');
    //     $sheet->mergeCells([1, $i, 2, $i]);
    //     $sheet->mergeCells([3, $i, 5, $i]);
    //     $sheet->getStyle([3, $i, 5, $i])->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);

    //     $i++; #9
    //     # A9
    //     $sheet->setCellValue([1, $i], 'Barangay:');
    //     $sheet->getStyle([1, $i])->getFont()->setName('Arial');
    //     $sheet->mergeCells([1, $i, 2, $i]);
    //     $sheet->mergeCells([3, $i, 5, $i]);
    //     $sheet->getStyle([3, $i, 5, $i])->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);

    //     $i++; #10
    //     # A10
    //     $sheet->getRowDimension($i)->setRowHeight(9);

    //     $i++; #11
    //     # A11
    //     $sheet->getRowDimension($i)->setRowHeight(18.75);

    //     ## Headers
    //     # B11:E11
    //     $sheet->setCellValue([2, $i], 'Name of Beneficiary');
    //     $sheet->mergeCells([2, $i, 5, $i]);
    //     $sheet->getStyle([2, $i, 5, $i])->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    //     $sheet->getStyle([2, $i, 5, $i])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    //     $sheet->getStyle([2, $i, 5, $i])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

    //     # G11:J11
    //     $sheet->setCellValue([7, $i], 'Project Location');
    //     $sheet->mergeCells([7, $i, 10, $i]);
    //     $sheet->getStyle([7, $i, 10, $i])->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    //     $sheet->getStyle([7, $i, 10, $i])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    //     $sheet->getStyle([7, $i, 10, $i])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

    //     # Y11:AB11
    //     $sheet->setCellValue([25, $i], 'Spouse');
    //     $sheet->mergeCells([25, $i, $number_of_cols, $i]);
    //     $sheet->getStyle([25, $i, $number_of_cols, $i])->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    //     $sheet->getStyle([25, $i, $number_of_cols, $i])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    //     $sheet->getStyle([25, $i, $number_of_cols, $i])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

    //     ## Set Text & Fill Colors on table header attributes
    //     # A11:AB12
    //     $sheet->getStyle([1, $i, $number_of_cols, $i + 1])->getFill()
    //         ->setFillType(Fill::FILL_SOLID)
    //         ->getStartColor()->setRGB('B6C6E7');

    //     ## Set Font Size to 8
    //     # A11:AB12
    //     $sheet->getStyle([1, $i, $number_of_cols, $i + 1])->getFont()->setSize(8);

    //     # A12
    //     $sheet->getRowDimension($i + 1)->setRowHeight(56.25);

    //     # A11:AB12
    //     $j = $i;
    //     $default = false;
    //     # Set the Table attribute headers (first name, middle name, birthdate, etc.)
    //     foreach ($headers as $rowIndex => $row) {
    //         foreach ($row as $colIndex => $value) {
    //             if (!in_array($colIndex, $excludedColumns)) {
    //                 if ($i !== $j && $default === true) {
    //                     $i--;
    //                     $default = false;
    //                 }
    //                 $sheet->setCellValue([$colIndex + 1, $rowIndex + $i], $value);
    //                 $sheet->mergeCells([$colIndex + 1, $rowIndex + $i, $colIndex + 1, $rowIndex + $i + 1]);
    //                 $sheet->getStyle([$colIndex + 1, $rowIndex + $i, $colIndex + 1, $rowIndex + $i + 1])->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    //                 $sheet->getStyle([$colIndex + 1, $rowIndex + $i])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    //                 $sheet->getStyle([$colIndex + 1, $rowIndex + $i])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    //                 $sheet->getStyle([$colIndex + 1, $rowIndex + $i])->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
    //             } else {
    //                 if ($default === false) {
    //                     $i++;
    //                     $default = true;
    //                 }
    //                 $sheet->setCellValue([$colIndex + 1, $rowIndex + $i], $value);
    //                 $sheet->getStyle([$colIndex + 1, $rowIndex + $i])->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    //                 $sheet->getStyle([$colIndex + 1, $rowIndex + $i])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    //                 $sheet->getStyle([$colIndex + 1, $rowIndex + $i])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    //                 $sheet->getStyle([$colIndex + 1, $rowIndex + $i])->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
    //             }

    //         }
    //     }

    //     ## Checks if the value (in the array) is the last one in the `$excludedColumns` array
    //     ## then aggregate the `$i` (or globalRowIndex). Otherwise, it would be the wrong row
    //     ## for the next one.
    //     if ($excludedColumns[sizeof($excludedColumns) - 1] !== $number_of_cols) {
    //         # A13
    //         $i++;
    //     }

    //     # [A13] or A12
    //     for ($row = 0; $row < $number_of_rows; $row++) {
    //         $sheet->getRowDimension($row + $i)->setRowHeight(31.5);
    //         for ($col = 0; $col < $number_of_cols; $col++) {
    //             if ($col === 0) {
    //                 $sheet->setCellValue([$col + 1, $row + $i], $row + 1);
    //             } elseif ($col === 18) {
    //                 $sheet->setCellValueExplicit([$col + 1, $row + $i], '=IF(ISBLANK(F' . $row + $i . '), "", DATEDIF(DATEVALUE(SUBSTITUTE(F' . $row + $i . ',"/","-")), TODAY(), "Y"))', DataType::TYPE_FORMULA);
    //             } else {
    //                 $sheet->getStyle([$col + 1, $row + $i])->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
    //             }
    //             $sheet->getStyle([$col + 1, $row + $i])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    //             $sheet->getStyle([$col + 1, $row + $i])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    //             $sheet->getStyle([$col + 1, $row + $i])->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    //         }
    //     }

    //     ## Sets the Wrap Text function to true for A11 to AB12
    //     $sheet->getStyle([1, $i - 2, $number_of_cols, $i + $number_of_rows - 1])->getAlignment()->setWrapText(true);

    //     $i++;
    //     # Footer Texts
    //     $sheet->setCellValue([2, $number_of_rows + $i], 'Prepared and Certified true and correct by:');
    //     $sheet->mergeCells([2, $number_of_rows + $i, 4, $number_of_rows + $i]);
    //     $i += 4;
    //     $sheet->getStyle([2, $number_of_rows + $i, 3, $number_of_rows + $i])->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
    //     $sheet->mergeCells([2, $number_of_rows + $i, 3, $number_of_rows + $i]);
    //     $i++;
    //     $sheet->setCellValue([2, $number_of_rows + $i], 'DOLE or Co-partner');
    //     $sheet->mergeCells([2, $number_of_rows + $i, 3, $number_of_rows + $i]);
    //     $i++;
    //     $sheet->setCellValue([1, $number_of_rows + $i], 'Notes:');
    //     $sheet->getStyle([1, $number_of_rows + $i])->getFont()->setBold(true);
    //     $i++;

    //     $rich = new RichText();
    //     $first = $rich->createTextRun('Birthdate:');
    //     $first->getFont()->setBold(true);
    //     $first->getFont()->setSize(7);
    //     $second = $rich->createTextRun(' Year/Month/Day (YYYY/MM/DD)');
    //     $second->getFont()->setSize(7);
    //     $sheet->setCellValue([1, $number_of_rows + $i], $rich);
    //     $sheet->mergeCells([1, $number_of_rows + $i, $number_of_cols, $number_of_rows + $i]);
    //     $i++;

    //     $rich = new RichText();
    //     $first = $rich->createTextRun('Project Location:');
    //     $first->getFont()->setBold(true);
    //     $first->getFont()->setSize(7);
    //     $second = $rich->createTextRun(' (Street No. Barangay, City/Municipality, Province, District)');
    //     $second->getFont()->setSize(7);
    //     $sheet->setCellValue([1, $number_of_rows + $i], $rich);
    //     $sheet->mergeCells([1, $number_of_rows + $i, $number_of_cols, $number_of_rows + $i]);
    //     $i++;

    //     $rich = new RichText();
    //     $first = $rich->createTextRun('Type of Beneficiaries:');
    //     $first->getFont()->setBold(true);
    //     $first->getFont()->setSize(7);
    //     $second = $rich->createTextRun(' (a.) Underemployed/Self-Employed; (b.) Minimum wage/below minimum earners that were displaced due to: temporary suspension of business operations, calamity/crisis situation i.e, earthquake, typhoon, volcanic eruption, global/national financial crisis, other (pls. specify), closure of company, retrenchment, (c.) Person with Disability (PWD), (d) Indigenous People, (e.) Former Violent Extremist Groups');
    //     $second->getFont()->setSize(7);
    //     $sheet->setCellValue([1, $number_of_rows + $i], $rich);
    //     $sheet->mergeCells([1, $number_of_rows + $i, $number_of_cols, $number_of_rows + $i]);
    //     $i++;

    //     $rich = new RichText();
    //     $first = $rich->createTextRun('Occupation:');
    //     $first->getFont()->setBold(true);
    //     $first->getFont()->setSize(7);
    //     $second = $rich->createTextRun(' Transport workers, Vendors, Crop growers (please specify, i.e tobacco farmer), Homebased worker (please specify, i.e sewer), Fisherfolks, Livestock/Poultry Raiser, Small Transport drivers, Laborer (please specify); Others (Please specify)');
    //     $second->getFont()->setSize(7);
    //     $sheet->setCellValue([1, $number_of_rows + $i], $rich);
    //     $sheet->mergeCells([1, $number_of_rows + $i, $number_of_cols, $number_of_rows + $i]);
    //     $i++;

    //     $rich = new RichText();
    //     $first = $rich->createTextRun('Civil Status:');
    //     $first->getFont()->setBold(true);
    //     $first->getFont()->setSize(7);
    //     $second = $rich->createTextRun(' S fro single, M for married, D for divoreced, SP for separated, W for Widowed');
    //     $second->getFont()->setSize(7);
    //     $sheet->setCellValue([1, $number_of_rows + $i], $rich);
    //     $sheet->mergeCells([1, $number_of_rows + $i, $number_of_cols, $number_of_rows + $i]);
    //     $i++;

    //     $rich = new RichText();
    //     $first = $rich->createTextRun('Dependent:');
    //     $first->getFont()->setBold(true);
    //     $first->getFont()->setSize(7);
    //     $second = $rich->createTextRun(' Name of the beneficiary of micro-insurance policy holder');
    //     $second->getFont()->setSize(7);
    //     $sheet->setCellValue([1, $number_of_rows + $i], $rich);
    //     $sheet->mergeCells([1, $number_of_rows + $i, $number_of_cols, $number_of_rows + $i]);
    //     $i++;

    //     $rich = new RichText();
    //     $first = $rich->createTextRun('Trainings:');
    //     $first->getFont()->setBold(true);
    //     $first->getFont()->setSize(7);
    //     $second = $rich->createTextRun(' Agriculture crops production, Aquaculture, Automotive, Construction, Weilding, Information and Communication Technology, Electrical and electronics, furniture making, garments and textile. Food processin, cooking, housekeeping, tourism, customer services, others (please specify)');
    //     $second->getFont()->setSize(7);
    //     $sheet->setCellValue([1, $number_of_rows + $i], $rich);
    //     $sheet->mergeCells([1, $number_of_rows + $i, $number_of_cols, $number_of_rows + $i]);
    //     $i++;

    //     # Globally set font sizes
    //     $sheet->getStyle([1, $number_of_rows + 20, $number_of_cols, $number_of_rows + $i])->getFont()->setSize(7);

    //     # Set Worksheet Name & Color
    //     $sheet->setTitle('ANNEX D - Profile');
    //     $sheet->getTabColor()->setRGB('FEFD0D'); // Green tab color

    //     return $spreadsheet;
    // }

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

        $beneficiaries = self::fetchBeneficiaries($batch);

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
                $sheet->getStyle([2, $curRow, $maxCol, $curRow])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $sheet->setCellValue([2, $curRow], self::full_last_first($beneficiary));
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

            $sheet->setCellValue('A1', implode(';', $columnHeaders));

            $curRow = 2;

            $combinedData = [];

            $count = 1;
            foreach ($beneficiaries as $beneficiary) {
                $combinedData = [];
                $combinedData[] = $count;
                $combinedData[] = self::full_last_first($beneficiary);
                $combinedData[] = self::address($beneficiary);
                $combinedData[] = '';

                $sheet->setCellValue([1, $curRow], implode(';', $combinedData));
                $curRow++;
                $count++;
            }

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

        $beneficiaries = self::fetchBeneficiaries($batch);

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
                $sheet->getStyle([2, $curRow, $maxCol, $curRow])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $sheet->setCellValue([2, $curRow], self::full_last_first($beneficiary));
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
            $sheet->setCellValue('A1', implode(';', $columnHeaders));

            $curRow = 2;

            $combinedData = [];

            $count = 1;
            foreach ($beneficiaries as $beneficiary) {
                $combinedData = [];
                $combinedData[] = $count;
                $combinedData[] = self::full_last_first($beneficiary);
                $combinedData[] = self::address($beneficiary);
                $combinedData[] = '';

                $sheet->setCellValue([1, $curRow], implode(';', $combinedData));
                $curRow++;
                $count++;
            }
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

        $beneficiaries = self::fetchBeneficiaries($batch);
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
        $columnHeaders = [
            'No.',
            'Name',
        ];

        $maxCol++;

        for ($day = 1; $day <= $implementation->days_of_work; $day++) {
            $daysHeader[] = 'Day ' . $day;
            $columnHeaders[] = 'Day ' . $day;
            $maxCol++;
        }

        $columnHeaders[] = 'Signature of Coordinator';

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
                $sheet->getStyle([2, $curRow, $maxCol, $curRow])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $sheet->setCellValue([2, $curRow], self::full_last_first($beneficiary));

                $num++;
                $curRow++;
            }

        } elseif ($exportFormat === 'csv') {

            $sheet->setCellValue('A1', implode(';', $columnHeaders));

            $curRow = 2;

            $combinedData = [];

            $count = 1;
            foreach ($beneficiaries as $beneficiary) {
                $combinedData = [];

                $combinedData[] = $count;
                $combinedData[] = self::full_last_first($beneficiary);

                for ($day = 1; $day <= $implementation->days_of_work; $day++) {
                    $combinedData[] = '';
                }

                $combinedData[] = '';

                $sheet->setCellValue([1, $curRow], implode(';', $combinedData));
                $curRow++;
                $count++;
            }
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

        $beneficiaries = self::fetchBeneficiaries($batch);
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
            $sheet->mergeCells([1, $curRow, $maxCol - 1, $curRow]);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getAlignment()->setWrapText(true);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getFont()->setSize(11);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getFont()->setBold(true);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getFont()->setName('Arial');
            $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow + 10])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->setCellValue([1, $curRow], 'Total: ');
            $sheet->setCellValueExplicit([16, $curRow], '=SUM(P' . $startCurRow . ':N' . $curRow - 1 . ')', DataType::TYPE_FORMULA);
            $sheet->getStyle([16, $curRow])->getNumberFormat()->setFormatCode('#,##0.00');
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
            $sheet->setCellValue('A1', implode(';', $columnHeaders));

            $curRow = 2;

            $combinedData = [];

            $count = 1;
            foreach ($beneficiaries as $beneficiary) {
                $combinedData = [];

                $combinedData[] = $count;
                $combinedData[] = $beneficiary->first_name;
                $combinedData[] = $beneficiary->middle_name ?? '-';
                $combinedData[] = $beneficiary->last_name;
                $combinedData[] = $beneficiary->extension_name ?? '-';
                $combinedData[] = '';
                $combinedData[] = strtoupper($beneficiary->barangay_name);
                $combinedData[] = strtoupper($beneficiary->city_municipality);
                $combinedData[] = strtoupper($beneficiary->province);
                $combinedData[] = "0" . substr($beneficiary->contact_num, 3);
                $combinedData[] = strtoupper(substr($beneficiary->sex, 0, 1));
                $combinedData[] = Carbon::parse($beneficiary->birthdate)->format('Y/m/d');
                $combinedData[] = $implementation->days_of_work;
                $combinedData[] = MoneyFormat::mask($implementation->minimum_wage);
                $combinedData[] = MoneyFormat::mask($implementation->days_of_work * $implementation->minimum_wage);

                $sheet->setCellValue([1, $curRow], implode(';', $combinedData));
                $curRow++;
                $count++;
            }
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

        $beneficiaries = self::fetchBeneficiaries($batch);
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
            $sheet->mergeCells([1, $curRow, $maxCol - 2, $curRow]);
            $sheet->getStyle([1, $curRow, $maxCol - 1, $curRow])->getAlignment()->setWrapText(true);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getFont()->setSize(11);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getFont()->setBold(true);
            $sheet->getStyle([1, $curRow, $maxCol - 1, $curRow])->getFont()->setName('Arial');
            $sheet->getStyle([1, $curRow, $maxCol - 1, $curRow])->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $sheet->getStyle([1, $curRow, $maxCol - 1, $curRow])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle([1, $curRow, $maxCol - 1, $curRow + 10])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->setCellValue([1, $curRow], 'Total: ');
            $sheet->setCellValueExplicit([16, $curRow], '=SUM(P' . $startCurRow . ':N' . $curRow - 1 . ')', DataType::TYPE_FORMULA);
            $sheet->getStyle([16, $curRow])->getNumberFormat()->setFormatCode('#,##0.00');
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
            $sheet->setCellValue('A1', implode(';', $columnHeaders));

            $curRow = 2;

            $combinedData = [];

            $count = 1;
            foreach ($beneficiaries as $beneficiary) {
                $combinedData = [];

                $combinedData[] = $count;
                $combinedData[] = $beneficiary->first_name;
                $combinedData[] = $beneficiary->middle_name ?? '-';
                $combinedData[] = $beneficiary->last_name;
                $combinedData[] = $beneficiary->extension_name ?? '-';
                $combinedData[] = '';
                $combinedData[] = strtoupper($beneficiary->barangay_name);
                $combinedData[] = strtoupper($beneficiary->city_municipality);
                $combinedData[] = strtoupper($beneficiary->province);
                $combinedData[] = "0" . substr($beneficiary->contact_num, 3);
                $combinedData[] = strtoupper(substr($beneficiary->sex, 0, 1));
                $combinedData[] = Carbon::parse($beneficiary->birthdate)->format('Y/m/d');
                $combinedData[] = $implementation->days_of_work;
                $combinedData[] = MoneyFormat::mask($implementation->minimum_wage);
                $combinedData[] = MoneyFormat::mask($implementation->days_of_work * $implementation->minimum_wage);

                $combinedData[] = '';

                $sheet->setCellValue([1, $curRow], implode(';', $combinedData));
                $curRow++;
                $count++;
            }
        }
        return $sheet;
    }

    protected static function full_last_first($person)
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