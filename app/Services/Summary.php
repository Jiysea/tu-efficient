<?php

namespace App\Services;
use App\Models\Batch;
use App\Models\Implementation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Summary
{
    public static function exportSummary(Spreadsheet $spreadsheet, array $data, string $format)
    {
        # Turn data into information
        $information = self::compileData($data);

        ## Retrieve the active sheet
        $sheet = $spreadsheet->getActiveSheet();

        ## Set Orientation to LANDSCAPE
        ## Set Page Size to A4
        ## Fit to Width
        ## Not Fit to Height
        $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_PORTRAIT);
        $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(0);

        # Set Worksheet Name & Color
        $sheet->setTitle('Summary of Beneficiaries');
        $sheet->getTabColor()->setRGB('0B0E14'); // Green tab color

        if ($format === 'xlsx') {
            $sheet = self::generate_xlsx($sheet, $information, $data);
        } elseif ($format === 'csv') {
            $sheet = self::generate_csv($sheet, $information);
        }

        return $spreadsheet;
    }

    protected static function generate_xlsx(Worksheet $sheet, array $information, array $data)
    {

        # It's basically a global row index and max column that aggregates every row generated
        # Think of it as like an interpreter where it generates rows line by line. 
        # Pretty helpful if you want to move the whole sheet by 1 or more rows.
        $curRow = 1;
        $maxCol = 12;

        $implementation = [
            'Project Number',
            'Project Title',
            'Province',
            'City/Municipality',
            'Budget',
            'Minimum Wage',
            'Total Slots',
            'Days of Work',
            'Purpose',
            'Status At Generation',
            'Date Created',
            'Last Updated',
        ];

        $summary = [
            'Overall',
            'People with Disability',
            'Senior Citizens',
            'Contract of Service Signed',
            'Payroll Claimed',
        ];

        $summary_nested = [
            'overall',
            'pwds',
            'seniors',
            'contract',
            'payroll'
        ];

        # Summary of Beneficiaries (A1:L1 && A2:L2)
        $sheet->getStyle([1, $curRow, 1, $curRow + 1])->getFont()->setSize(20); # A1:A2
        $sheet->setCellValue([1, $curRow], 'Summary of Beneficiaries');
        $sheet->getStyle([1, $curRow])->getFont()->setBold(true);
        $sheet->getStyle([1, $curRow])->getFont()->setName('Arial');
        $sheet->mergeCells([1, $curRow, $maxCol, $curRow + 1]);
        $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        # Big Line (A:L
        $curRow += 2;
        $sheet->setCellValue([1, $curRow, $maxCol, $curRow], '__________________________________________________________________________________________________');
        $sheet->mergeCells([1, $curRow, $maxCol, $curRow]);
        $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getFont()
            ->getColor()->setRGB('6B7280');
        $sheet->getRowDimension($curRow)->setRowHeight(15);

        # Start to End Date Range (A4) ONLY APPLICABLE TO DATE RANGE EXPORT OPTION
        $curRow += 2;
        if (isset($data['date_range'])) {

            $sheet->getStyle([1, $curRow, 1, $curRow + 1])->getFont()->setSize(10); # A1:A2
            $sheet->setCellValue([1, $curRow], 'Implementations spanning from ' . Carbon::parse($data['date_range']['start'])->format('F d, Y') . ' to ' . Carbon::parse($data['date_range']['end'])->format('F d, Y'));
            $sheet->getStyle([1, $curRow])->getFont()->setName('Arial');
            $sheet->mergeCells([1, $curRow, $maxCol, $curRow]);
            $sheet->getStyle([1, $curRow])->getFont()->setBold(true);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        } elseif (isset($data['selected_project'])) {
            $curRow -= 2;
        }

        #A5
        $curRow++;
        $sheet->getRowDimension($curRow)->setRowHeight(10);

        # BODY -----------------------------------------------------------

        # Set auto size for all columns
        foreach (range('A', $sheet->getHighestColumn()) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        $counter = 1;
        foreach ($information as $info) {

            # Implementation Information (A:L)
            $curRow++;
            $sheet->getStyle([1, $curRow])->getFont()->setSize(16);
            $sheet->getStyle([1, $curRow])->getFont()->setBold(true);
            $sheet->getRowDimension($curRow)->setRowHeight(30);

            if (isset($data['date_range'])) {
                $sheet->setCellValue([1, $curRow], '• Implementation #' . $counter . ' Information');
                $counter++;
            } elseif (isset($data['selected_project'])) {
                $sheet->setCellValue([1, $curRow], '• Implementation Information');
            }

            $sheet->getStyle([1, $curRow])->getFont()->setName('Arial');
            $sheet->mergeCells([1, $curRow, $maxCol, $curRow]);
            $sheet->getStyle([1, $curRow])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $curRow++;

            # Loop the implementations values in a 4-column merged cells in Excel
            for ($i = 0; $i < count($implementation); $i += 2) {
                # Setting Row Height && Vertical Alignment to Center
                $sheet->getRowDimension($curRow)->setRowHeight(20);
                $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

                # A
                $sheet->getStyle([1, $curRow])->getFont()->setSize(10);
                $sheet->setCellValue([1, $curRow], $implementation[$i]);
                $sheet->getStyle([1, $curRow])->getFont()->setName('Arial');

                # B:F
                $sheet->getStyle([2, $curRow])->getFont()->setSize(10);
                $sheet->setCellValue([2, $curRow], $info['implementationInformation'][$i]);
                $sheet->getStyle([2, $curRow])->getFont()->setName('Arial');
                $sheet->getStyle([2, $curRow])->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                $sheet->mergeCells([2, $curRow, 6, $curRow]);

                # G
                $sheet->getStyle([7, $curRow])->getFont()->setSize(10);
                $sheet->setCellValue([7, $curRow], $implementation[$i + 1]);
                $sheet->getStyle([7, $curRow])->getFont()->setName('Arial');

                # H:L
                $sheet->getStyle([8, $curRow])->getFont()->setSize(10);
                $sheet->setCellValue([8, $curRow], $implementation[$i + 1] === 'Status At Generation' ? mb_strtoupper($info['implementationInformation'][$i + 1], "UTF-8") : $info['implementationInformation'][$i + 1]);
                $sheet->getStyle([8, $curRow])->getFont()->setName('Arial');
                $sheet->getStyle([8, $curRow])->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                $sheet->mergeCells([8, $curRow, 12, $curRow]);
                $curRow++;
            }

            # Total Beneficiaries (A:L)
            $curRow++;
            $sheet->getStyle([1, $curRow])->getFont()->setSize(16); # A5:A5
            $sheet->getStyle([1, $curRow])->getFont()->setBold(true);
            $sheet->getRowDimension($curRow)->setRowHeight(30);
            $sheet->setCellValue([1, $curRow], '• Total of Beneficiaries (By Project)');
            $sheet->getStyle([1, $curRow])->getFont()->setName('Arial');
            $sheet->mergeCells([1, $curRow, $maxCol, $curRow]);
            $sheet->getStyle([1, $curRow])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $curRow++;

            # Loop the summary headers in a 3-column merged cells
            $sheet->getRowDimension($curRow)->setRowHeight(20);
            $hitContract = 0;
            for ($c = 0; $c < count($summary); $c++) {
                $sheet->getStyle([($c * 4) + 1, $curRow, ($c * 4) + 4, $curRow])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

                # Title
                $sheet->getStyle([($c * 4) + 1, $curRow])->getFont()->setSize(10);
                $sheet->setCellValue([($c * 4) + 1, $curRow], $summary[$c] . ' (' . $info['summaryCount'][$summary_nested[$c]]['male'] + $info['summaryCount'][$summary_nested[$c]]['female'] . ')');
                $sheet->getStyle([($c * 4) + 1, $curRow])->getFont()->setName('Arial');
                $sheet->mergeCells([($c * 4) + 1, $curRow, ($c * 4) + 4, $curRow]);

                # Male && Female
                $sheet->getStyle([($c * 4) + 1, $curRow + 1])->getFont()->setSize(10);
                $sheet->setCellValue([($c * 4) + 1, $curRow + 1], 'Male: ' . $info['summaryCount'][$summary_nested[$c]]['male']);
                $sheet->getStyle([($c * 4) + 1, $curRow + 1])->getFont()->setName('Arial');
                $sheet->getStyle([($c * 4) + 1, $curRow + 1])->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                $sheet->mergeCells([($c * 4) + 1, $curRow + 1, ($c * 4) + 2, $curRow + 1]);

                $sheet->getStyle([($c * 4) + 3, $curRow + 1])->getFont()->setSize(10);
                $sheet->setCellValue([($c * 4) + 3, $curRow + 1], 'Female: ' . $info['summaryCount'][$summary_nested[$c]]['female']);
                $sheet->getStyle([($c * 4) + 3, $curRow + 1])->getFont()->setName('Arial');
                $sheet->getStyle([($c * 4) + 3, $curRow + 1])->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                $sheet->mergeCells([($c * 4) + 3, $curRow + 1, ($c * 4) + 4, $curRow + 1]);
                if ($c === 1 && !$hitContract) {
                    $c = 0;
                    $hitContract++;
                }
            }

            $curRow += 3;

            # Batches Area
            $sheet->getStyle([1, $curRow])->getFont()->setSize(16); # A5:A5
            $sheet->getStyle([1, $curRow])->getFont()->setBold(true);
            $sheet->getRowDimension($curRow)->setRowHeight(30);
            $sheet->setCellValue([1, $curRow], '• Total By Batches');
            $sheet->getStyle([1, $curRow])->getFont()->setName('Arial');
            $sheet->mergeCells([1, $curRow, $maxCol, $curRow]);
            $sheet->getStyle([1, $curRow])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $curRow++;

            # Loop the batches in a 4-column merged cells
            $sheet->getRowDimension($curRow)->setRowHeight(20);
            $i = 1;
            foreach ($info['batches'] as $batch) {
                $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

                # Barangay Name
                $sheet->getStyle([1, $curRow])->getFont()->setSize(16); # A5:A5
                $sheet->getRowDimension($curRow)->setRowHeight(30);

                if ($batch['is_sectoral']) {
                    $value = ($batch['is_sectoral'] ? 'SECTORAL' : 'NON-SECTORAL') . '] ' . $batch['sector_title'];
                    $sheet->setCellValue([1, $curRow], '#' . $i . ' [' . $value . ' (' . intval($batch['total_male'] + $batch['total_female']) . ')');
                } elseif (!$batch['is_sectoral']) {
                    $value = ($batch['is_sectoral'] ? 'SECTORAL' : 'NON-SECTORAL') . '] ' . 'Brgy. ' . $batch['barangay_name'];
                    $sheet->setCellValue([1, $curRow], '#' . $i . ' [' . $value . ' (' . intval($batch['total_male'] + $batch['total_female']) . ')');
                }

                $sheet->getStyle([1, $curRow])->getFont()->setName('Arial');
                $sheet->mergeCells([1, $curRow, $maxCol, $curRow]);
                $sheet->getStyle([1, $curRow])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                $curRow++;

                # Total Overall (Male)
                # A
                $sheet->getStyle([1, $curRow])->getFont()->setSize(10);
                $sheet->setCellValue([1, $curRow], 'Total Male');
                $sheet->getStyle([1, $curRow])->getFont()->setName('Arial');

                # B:F
                $sheet->getStyle([2, $curRow])->getFont()->setSize(10);
                $sheet->setCellValue([2, $curRow], $batch['total_male']);
                $sheet->getStyle([2, $curRow])->getFont()->setName('Arial');
                $sheet->getStyle([2, $curRow])->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                $sheet->mergeCells([2, $curRow, 6, $curRow]);

                # Total Overall (Male)
                # G
                $sheet->getStyle([7, $curRow])->getFont()->setSize(10);
                $sheet->setCellValue([7, $curRow], 'Total Female');
                $sheet->getStyle([7, $curRow])->getFont()->setName('Arial');

                # H:L
                $sheet->getStyle([8, $curRow])->getFont()->setSize(10);
                $sheet->setCellValue([8, $curRow], $batch['total_female']);
                $sheet->getStyle([8, $curRow])->getFont()->setName('Arial');
                $sheet->getStyle([8, $curRow])->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                $sheet->mergeCells([8, $curRow, 12, $curRow]);
                $curRow++;

                # PWD Total (Male)
                # A
                $sheet->getStyle([1, $curRow])->getFont()->setSize(10);
                $sheet->setCellValue([1, $curRow], 'PWD Male');
                $sheet->getStyle([1, $curRow])->getFont()->setName('Arial');

                # B:F
                $sheet->getStyle([2, $curRow])->getFont()->setSize(10);
                $sheet->setCellValue([2, $curRow], $batch['total_pwd_male']);
                $sheet->getStyle([2, $curRow])->getFont()->setName('Arial');
                $sheet->getStyle([2, $curRow])->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                $sheet->mergeCells([2, $curRow, 6, $curRow]);

                # PWD Total (Female)
                # G
                $sheet->getStyle([7, $curRow])->getFont()->setSize(10);
                $sheet->setCellValue([7, $curRow], 'PWD Female');
                $sheet->getStyle([7, $curRow])->getFont()->setName('Arial');

                # H:L
                $sheet->getStyle([8, $curRow])->getFont()->setSize(10);
                $sheet->setCellValue([8, $curRow], $batch['total_pwd_female']);
                $sheet->getStyle([8, $curRow])->getFont()->setName('Arial');
                $sheet->getStyle([8, $curRow])->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                $sheet->mergeCells([8, $curRow, 12, $curRow]);
                $curRow++;

                # Senior Total (Male)
                # A
                $sheet->getStyle([1, $curRow])->getFont()->setSize(10);
                $sheet->setCellValue([1, $curRow], 'Senior Male');
                $sheet->getStyle([1, $curRow])->getFont()->setName('Arial');

                # B:F
                $sheet->getStyle([2, $curRow])->getFont()->setSize(10);
                $sheet->setCellValue([2, $curRow], $batch['total_senior_male']);
                $sheet->getStyle([2, $curRow])->getFont()->setName('Arial');
                $sheet->getStyle([2, $curRow])->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                $sheet->mergeCells([2, $curRow, 6, $curRow]);

                # Senior Total (Female)
                # G
                $sheet->getStyle([7, $curRow])->getFont()->setSize(10);
                $sheet->setCellValue([7, $curRow], 'Senior Female');
                $sheet->getStyle([7, $curRow])->getFont()->setName('Arial');

                # H:L
                $sheet->getStyle([8, $curRow])->getFont()->setSize(10);
                $sheet->setCellValue([8, $curRow], $batch['total_senior_female']);
                $sheet->getStyle([8, $curRow])->getFont()->setName('Arial');
                $sheet->getStyle([8, $curRow])->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                $sheet->mergeCells([8, $curRow, 12, $curRow]);
                $curRow++;

                # Contract of Service (Male)
                # A
                $sheet->getStyle([1, $curRow])->getFont()->setSize(10);
                $sheet->setCellValue([1, $curRow], 'COS Male');
                $sheet->getStyle([1, $curRow])->getFont()->setName('Arial');

                # B:F
                $sheet->getStyle([2, $curRow])->getFont()->setSize(10);
                $sheet->setCellValue([2, $curRow], $batch['total_contract_male']);
                $sheet->getStyle([2, $curRow])->getFont()->setName('Arial');
                $sheet->getStyle([2, $curRow])->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                $sheet->mergeCells([2, $curRow, 6, $curRow]);

                # Contract of Service (Female)
                # G
                $sheet->getStyle([7, $curRow])->getFont()->setSize(10);
                $sheet->setCellValue([7, $curRow], 'COS Female');
                $sheet->getStyle([7, $curRow])->getFont()->setName('Arial');

                # H:L
                $sheet->getStyle([8, $curRow])->getFont()->setSize(10);
                $sheet->setCellValue([8, $curRow], $batch['total_contract_female']);
                $sheet->getStyle([8, $curRow])->getFont()->setName('Arial');
                $sheet->getStyle([8, $curRow])->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                $sheet->mergeCells([8, $curRow, 12, $curRow]);
                $curRow++;

                # Contract of Service (Male)
                # A
                $sheet->getStyle([1, $curRow])->getFont()->setSize(10);
                $sheet->setCellValue([1, $curRow], 'Payroll Male');
                $sheet->getStyle([1, $curRow])->getFont()->setName('Arial');

                # B:F
                $sheet->getStyle([2, $curRow])->getFont()->setSize(10);
                $sheet->setCellValue([2, $curRow], $batch['total_payroll_male']);
                $sheet->getStyle([2, $curRow])->getFont()->setName('Arial');
                $sheet->getStyle([2, $curRow])->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                $sheet->mergeCells([2, $curRow, 6, $curRow]);

                # Contract of Service (Female)
                # G
                $sheet->getStyle([7, $curRow])->getFont()->setSize(10);
                $sheet->setCellValue([7, $curRow], 'Payroll Female');
                $sheet->getStyle([7, $curRow])->getFont()->setName('Arial');

                # H:L
                $sheet->getStyle([8, $curRow])->getFont()->setSize(10);
                $sheet->setCellValue([8, $curRow], $batch['total_payroll_female']);
                $sheet->getStyle([8, $curRow])->getFont()->setName('Arial');
                $sheet->getStyle([8, $curRow])->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                $sheet->mergeCells([8, $curRow, 12, $curRow]);
                $curRow++;
                $i++;
            }

            # Big Line (A:L)
            $sheet->getRowDimension($curRow)->setRowHeight(20);
            $sheet->setCellValue([1, $curRow], '________________________________________________________________________________________________________________________________________');
            $sheet->mergeCells([1, $curRow, $maxCol, $curRow]);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getFont()
                ->getColor()->setRGB('6B7280');

            $curRow++;
        }

        return $sheet;
    }

    protected static function generate_csv(Worksheet $sheet, array $information)
    {

        $columnHeaders = [
            'Project Number',
            'Project Title',
            'Province',
            'City/Municipality',
            'Budget',
            'Minimum Wage',
            'Total Slots',
            'Days of Work',
            'Purpose',
            'Status At Generation',
            'Date Created',
            'Last Updated',
            'Overall (Male)',
            'Overall (Female)',
            'PWDs (Male)',
            'PWDs (Female)',
            'Senior Citizens (Male)',
            'Senior Citizens (Female)',
            'Contract of Service Signed (Male)',
            'Contract of Service Signed (Female)',
            'Payroll Claimed (Male)',
            'Payroll Claimed (Female)',
            'Barangay/Sector',
            'Type of Batch',
            'Barangay Overall (Male)',
            'Barangay Overall (Female)',
            'Barangay PWDs (Male)',
            'Barangay PWDs (Female)',
            'Barangay Senior Citizens (Male)',
            'Barangay Senior Citizens (Female)',
            'Barangay Contract of Service Signed (Male)',
            'Barangay Contract of Service Signed (Female)',
            'Barangay Payroll Claimed (Male)',
            'Barangay Payroll Claimed (Female)',
        ];

        $sheet->setCellValue('A1', implode(';', $columnHeaders));

        $curRow = 2;

        $combinedData = [];
        foreach ($information as $info) {


            foreach ($info['batches'] as $batch) {

                $combinedData = [];

                foreach ($info['implementationInformation'] as $key => $implementation) {
                    $combinedData[] = $key === 9 ? mb_strtoupper($implementation, "UTF-8") : $implementation;
                }

                foreach ($info['summaryCount'] as $value) {
                    $combinedData[] = $value['male'];
                    $combinedData[] = $value['female'];
                }

                $combinedData[] = $batch['barangay_name'] ?? $batch['sector_title'];
                $combinedData[] = $batch['is_sectoral'] ? 'Sectoral' : 'Non-Sectoral';
                $combinedData[] = $batch['total_male'];
                $combinedData[] = $batch['total_female'];
                $combinedData[] = $batch['total_pwd_male'];
                $combinedData[] = $batch['total_pwd_female'];
                $combinedData[] = $batch['total_senior_male'];
                $combinedData[] = $batch['total_senior_female'];
                $combinedData[] = $batch['total_contract_male'];
                $combinedData[] = $batch['total_contract_female'];
                $combinedData[] = $batch['total_payroll_male'];
                $combinedData[] = $batch['total_payroll_female'];

                $sheet->setCellValue([1, $curRow], implode(';', $combinedData));
                $curRow++;
            }
        }

        return $sheet;
    }

    protected static function compileData(array $data): array
    {
        # Reassign the Implementations so that it will loop whichever export option that's selected.
        $implementations = null;
        if (isset($data['date_range'])) {
            $implementations = $data['date_range']['implementations'];
        } elseif (isset($data['selected_project'])) {
            $implementations = $data['selected_project']['implementations'];
        }

        $information = [];

        # Starts looping the implementation projects whether it's based on Date Range or Selected Project option
        foreach ($implementations as $implementation) {

            # These values are in `strict` order so if you want to reorder them,
            # make sure you also reorder the `$implementation` variable from the generators.
            $implementationInformation = [
                $implementation->project_num,
                $implementation->project_title ?? '-',
                $implementation->province,
                $implementation->city_municipality,
                '₱' . \App\Services\MoneyFormat::mask($implementation->budget_amount),
                '₱' . \App\Services\MoneyFormat::mask($implementation->minimum_wage),
                $implementation->total_slots,
                $implementation->days_of_work,
                $implementation->purpose,
                $implementation->status,
                Carbon::parse($implementation->created_at)->format('F d, Y @ h:i:sa'),
                Carbon::parse($implementation->updated_at)->format('F d, Y @ h:i:sa'),
            ];

            # Query used for Beneficiary Count (overall male, female, senior male, female, pwd male, female)
            $impCount = Implementation::join('batches', 'batches.implementations_id', '=', 'implementations.id')
                ->join('beneficiaries', 'beneficiaries.batches_id', '=', 'batches.id')
                ->where('implementations.id', $implementation->id)
                ->select([
                    DB::raw('SUM(CASE WHEN beneficiaries.sex = "male" THEN 1 ELSE 0 END) AS total_male'),
                    DB::raw('SUM(CASE WHEN beneficiaries.sex = "female" THEN 1 ELSE 0 END) AS total_female'),
                    DB::raw('SUM(CASE WHEN beneficiaries.is_pwd = "yes" AND beneficiaries.sex = "male" THEN 1 ELSE 0 END) AS total_pwd_male'),
                    DB::raw('SUM(CASE WHEN beneficiaries.is_pwd = "yes" AND beneficiaries.sex = "female" THEN 1 ELSE 0 END) AS total_pwd_female'),
                    DB::raw('SUM(CASE WHEN beneficiaries.is_senior_citizen = "yes" AND beneficiaries.sex = "male" THEN 1 ELSE 0 END) AS total_senior_male'),
                    DB::raw('SUM(CASE WHEN beneficiaries.is_senior_citizen = "yes" AND beneficiaries.sex = "female" THEN 1 ELSE 0 END) AS total_senior_female'),
                    DB::raw('SUM(CASE WHEN beneficiaries.is_signed AND beneficiaries.sex = "male" THEN 1 ELSE 0 END) AS total_contract_male'),
                    DB::raw('SUM(CASE WHEN beneficiaries.is_signed AND beneficiaries.sex = "female" THEN 1 ELSE 0 END) AS total_contract_female'),
                    DB::raw('SUM(CASE WHEN beneficiaries.is_paid AND beneficiaries.sex = "male" THEN 1 ELSE 0 END) AS total_payroll_male'),
                    DB::raw('SUM(CASE WHEN beneficiaries.is_paid AND beneficiaries.sex = "female" THEN 1 ELSE 0 END) AS total_payroll_female'),
                ])
                ->first();

            # Current Implementation
            $overall = [
                'male' => $impCount->total_male,
                'female' => $impCount->total_female,
            ];
            $seniors = [
                'male' => $impCount->total_senior_male,
                'female' => $impCount->total_senior_female,
            ];
            $pwds = [
                'male' => $impCount->total_pwd_male,
                'female' => $impCount->total_pwd_female,
            ];
            $contract = [
                'male' => $impCount->total_contract_male,
                'female' => $impCount->total_contract_female,
            ];
            $payroll = [
                'male' => $impCount->total_payroll_male,
                'female' => $impCount->total_payroll_female,
            ];

            $summaryCount = [
                'overall' => $overall,
                'pwds' => $pwds,
                'seniors' => $seniors,
                'contract' => $contract,
                'payroll' => $payroll,
            ];

            # Get all the batches from the selected implementation/s
            $currentBatches = Batch::join('implementations', 'batches.implementations_id', '=', 'implementations.id')
                ->join('beneficiaries', 'beneficiaries.batches_id', '=', 'batches.id')
                ->select([
                    'batches.is_sectoral',
                    'batches.sector_title',
                    'batches.barangay_name',
                    DB::raw('SUM(CASE WHEN beneficiaries.sex = "male" THEN 1 ELSE 0 END) AS total_male'),
                    DB::raw('SUM(CASE WHEN beneficiaries.sex = "female" THEN 1 ELSE 0 END) AS total_female'),
                    DB::raw('SUM(CASE WHEN beneficiaries.is_pwd = "yes" AND beneficiaries.sex = "male" THEN 1 ELSE 0 END) AS total_pwd_male'),
                    DB::raw('SUM(CASE WHEN beneficiaries.is_pwd = "yes" AND beneficiaries.sex = "female" THEN 1 ELSE 0 END) AS total_pwd_female'),
                    DB::raw('SUM(CASE WHEN beneficiaries.is_senior_citizen = "yes" AND beneficiaries.sex = "male" THEN 1 ELSE 0 END) AS total_senior_male'),
                    DB::raw('SUM(CASE WHEN beneficiaries.is_senior_citizen = "yes" AND beneficiaries.sex = "female" THEN 1 ELSE 0 END) AS total_senior_female'),
                    DB::raw('SUM(CASE WHEN beneficiaries.is_signed AND beneficiaries.sex = "male" THEN 1 ELSE 0 END) AS total_contract_male'),
                    DB::raw('SUM(CASE WHEN beneficiaries.is_signed AND beneficiaries.sex = "female" THEN 1 ELSE 0 END) AS total_contract_female'),
                    DB::raw('SUM(CASE WHEN beneficiaries.is_paid AND beneficiaries.sex = "male" THEN 1 ELSE 0 END) AS total_payroll_male'),
                    DB::raw('SUM(CASE WHEN beneficiaries.is_paid AND beneficiaries.sex = "female" THEN 1 ELSE 0 END) AS total_payroll_female'),
                ])
                ->where('implementations.id', $implementation->id)
                ->groupBy(['batches.is_sectoral', 'batches.sector_title', 'batches.barangay_name'])
                ->get();

            # Barangay Information
            $batches = [];

            foreach ($currentBatches as $batch) {
                $batches[] = [
                    'is_sectoral' => $batch->is_sectoral,
                    'sector_title' => $batch->sector_title,
                    'barangay_name' => $batch->barangay_name,
                    'total_male' => $batch->total_male,
                    'total_female' => $batch->total_female,
                    'total_pwd_male' => $batch->total_pwd_male,
                    'total_pwd_female' => $batch->total_pwd_female,
                    'total_senior_male' => $batch->total_senior_male,
                    'total_senior_female' => $batch->total_senior_female,
                    'total_contract_male' => $batch->total_contract_male,
                    'total_contract_female' => $batch->total_contract_female,
                    'total_payroll_male' => $batch->total_payroll_male,
                    'total_payroll_female' => $batch->total_payroll_female,
                ];
            }

            $information[] = [
                'implementationInformation' => $implementationInformation,
                'summaryCount' => $summaryCount,
                'batches' => $batches,
            ];
        }

        return $information;
    }
}