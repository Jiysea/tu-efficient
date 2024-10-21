<?php

namespace App\Services;
use App\Models\Batch;
use App\Models\Implementation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
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
            'District',
            'Budget',
            'Minimum Wage',
            'Total Slots',
            'Days of Work',
            'Purpose',
            'Date Created',
            'Last Updated',
        ];

        $summary = [
            'Overall',
            'People with Disability',
            'Senior Citizens',
        ];

        $summary_nested = [
            'overall',
            'pwds',
            'seniors',
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
        $sheet->getRowDimension($curRow)->setRowHeight(15);

        # Start to End Date Range (A4) ONLY APPLICABLE TO DATE RANGE EXPORT OPTION
        $curRow += 2;
        if (isset($data['date_range'])) {

            $sheet->getStyle([1, $curRow, 1, $curRow + 1])->getFont()->setSize(10); # A1:A2
            $sheet->setCellValue([1, $curRow], 'Implementations spanning from ' . Carbon::parse($data['date_range']['start'])->format('F d, Y') . ' to ' . Carbon::parse($data['date_range']['end'])->format('F d, Y'));
            $sheet->getStyle([1, $curRow])->getFont()->setName('Arial');
            $sheet->mergeCells([1, $curRow, $maxCol, $curRow]);
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
                $sheet->setCellValue([8, $curRow], $info['implementationInformation'][$i + 1]);
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
            }

            $curRow += 2;

            if (isset($data['selected_project'])) {
                # Big Line (A:L)
                $sheet->getRowDimension($curRow)->setRowHeight(20);
                $sheet->setCellValue([1, $curRow], '________________________________________________________________________________________________________________________________________');
                $sheet->mergeCells([1, $curRow, $maxCol, $curRow]);
                $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $curRow += 2;
            }
            # Barangay / Batches Area
            $sheet->getStyle([1, $curRow])->getFont()->setSize(16); # A5:A5
            $sheet->getStyle([1, $curRow])->getFont()->setBold(true);
            $sheet->getRowDimension($curRow)->setRowHeight(30);
            $sheet->setCellValue([1, $curRow], '• Total By Barangay');
            $sheet->getStyle([1, $curRow])->getFont()->setName('Arial');
            $sheet->mergeCells([1, $curRow, $maxCol, $curRow]);
            $sheet->getStyle([1, $curRow])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $curRow++;

            # Loop the barangays in a 4-column merged cells
            $sheet->getRowDimension($curRow)->setRowHeight(20);
            $i = 1;
            foreach ($info['barangays'] as $barangay) {
                $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

                # Barangay Name
                $sheet->getStyle([1, $curRow])->getFont()->setSize(16); # A5:A5
                $sheet->getRowDimension($curRow)->setRowHeight(30);
                $sheet->setCellValue([1, $curRow], '#' . $i . ' • Brgy. ' . $barangay['barangay_name']);
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
                $sheet->setCellValue([2, $curRow], $barangay['total_male']);
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
                $sheet->setCellValue([8, $curRow], $barangay['total_female']);
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
                $sheet->setCellValue([2, $curRow], $barangay['total_pwd_male']);
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
                $sheet->setCellValue([8, $curRow], $barangay['total_pwd_female']);
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
                $sheet->setCellValue([2, $curRow], $barangay['total_senior_male']);
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
                $sheet->setCellValue([8, $curRow], $barangay['total_senior_female']);
                $sheet->getStyle([8, $curRow])->getFont()->setName('Arial');
                $sheet->getStyle([8, $curRow])->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                $sheet->mergeCells([8, $curRow, 12, $curRow]);
                $curRow++;
                $i++;
            }

            if (isset($data['date_range'])) {
                # Last Big Line (A:L)
                $sheet->setCellValue([1, $curRow, $maxCol, $curRow], '__________________________________________________________________________________________________');
                $sheet->mergeCells([1, $curRow, $maxCol, $curRow]);
                $sheet->getStyle([1, $curRow, $maxCol, $curRow])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getRowDimension($curRow)->setRowHeight(15);
            }

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
            'District',
            'Budget',
            'Minimum Wage',
            'Total Slots',
            'Days of Work',
            'Purpose',
            'Date Created',
            'Last Updated',
            'Overall (Male)',
            'Overall (Female)',
            'PWDs (Male)',
            'PWDs (Female)',
            'Senior Citizens (Male)',
            'Senior Citizens (Female)',
            'Barangay Name',
            'Barangay Overall (Male)',
            'Barangay Overall (Female)',
            'Barangay PWDs (Male)',
            'Barangay PWDs (Female)',
            'Barangay Senior Citizens (Male)',
            'Barangay Senior Citizens (Female)',
        ];

        $sheet->setCellValue('A1', implode(';', $columnHeaders));

        $curRow = 2;

        $combinedData = [];
        foreach ($information as $info) {


            foreach ($info['barangays'] as $barangay) {

                $combinedData = [];

                foreach ($info['implementationInformation'] as $implementation) {
                    $combinedData[] = $implementation;
                }

                foreach ($info['summaryCount'] as $value) {
                    $combinedData[] = $value['male'];
                    $combinedData[] = $value['female'];
                }

                $combinedData[] = $barangay['barangay_name'];
                $combinedData[] = $barangay['total_male'];
                $combinedData[] = $barangay['total_female'];
                $combinedData[] = $barangay['total_pwd_male'];
                $combinedData[] = $barangay['total_pwd_female'];
                $combinedData[] = $barangay['total_senior_male'];
                $combinedData[] = $barangay['total_senior_female'];

                $sheet->setCellValue([1, $curRow], implode(';', $combinedData));
                $curRow++;
            }
        }

        return $sheet;
    }

    protected static function compileData(array $data): array
    {
        # Reassign the Implementations so that it will loop whichever option that's selected.
        $implementations = null;
        if (isset($data['date_range'])) {
            $implementations = $data['date_range']['implementations'];
        } elseif (isset($data['selected_project'])) {
            $implementations = $data['selected_project']['implementations'];
        }

        $information = [];

        # Starts looping the implementation projects whether it's based on Date Range or Selected Project option
        foreach ($implementations as $implementation) {

            # Implementation Information
            $implementationInformation = [
                $implementation->project_num,
                $implementation->project_title ?? '-',
                $implementation->province,
                $implementation->city_municipality,
                $implementation->district,
                '₱' . \App\Services\MoneyFormat::mask($implementation->budget_amount),
                '₱' . \App\Services\MoneyFormat::mask($implementation->minimum_wage),
                $implementation->total_slots,
                $implementation->days_of_work,
                $implementation->purpose,
                Carbon::parse($implementation->created_at)->format('F d, Y @ h:i:sa'),
                Carbon::parse($implementation->updated_at)->format('F d, Y @ h:i:sa'),
            ];

            $impCount = Implementation::join('batches', 'batches.implementations_id', '=', 'implementations.id')
                ->join('beneficiaries', 'beneficiaries.batches_id', '=', 'batches.id')
                ->where('implementations.id', $implementation->id)
                ->select([
                    DB::raw('SUM(CASE WHEN beneficiaries.sex = "male" THEN 1 ELSE 0 END) AS total_male'),
                    DB::raw('SUM(CASE WHEN beneficiaries.sex = "female" THEN 1 ELSE 0 END) AS total_female'),
                    DB::raw('SUM(CASE WHEN beneficiaries.is_pwd = "yes" AND beneficiaries.sex = "male" THEN 1 ELSE 0 END) AS total_pwd_male'),
                    DB::raw('SUM(CASE WHEN beneficiaries.is_pwd = "yes" AND beneficiaries.sex = "female" THEN 1 ELSE 0 END) AS total_pwd_female'),
                    DB::raw('SUM(CASE WHEN beneficiaries.is_senior_citizen = "yes" AND beneficiaries.sex = "male" THEN 1 ELSE 0 END) AS total_senior_male'),
                    DB::raw('SUM(CASE WHEN beneficiaries.is_senior_citizen = "yes" AND beneficiaries.sex = "female" THEN 1 ELSE 0 END) AS total_senior_female')
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

            $summaryCount = [
                'overall' => $overall,
                'pwds' => $pwds,
                'seniors' => $seniors,
            ];

            $batches = Batch::join('implementations', 'batches.implementations_id', '=', 'implementations.id')
                ->join('beneficiaries', 'beneficiaries.batches_id', '=', 'batches.id')
                ->select([
                    'batches.barangay_name',
                    DB::raw('SUM(CASE WHEN beneficiaries.sex = "male" THEN 1 ELSE 0 END) AS total_male'),
                    DB::raw('SUM(CASE WHEN beneficiaries.sex = "female" THEN 1 ELSE 0 END) AS total_female'),
                    DB::raw('SUM(CASE WHEN beneficiaries.is_pwd = "yes" AND beneficiaries.sex = "male" THEN 1 ELSE 0 END) AS total_pwd_male'),
                    DB::raw('SUM(CASE WHEN beneficiaries.is_pwd = "yes" AND beneficiaries.sex = "female" THEN 1 ELSE 0 END) AS total_pwd_female'),
                    DB::raw('SUM(CASE WHEN beneficiaries.is_senior_citizen = "yes" AND beneficiaries.sex = "male" THEN 1 ELSE 0 END) AS total_senior_male'),
                    DB::raw('SUM(CASE WHEN beneficiaries.is_senior_citizen = "yes" AND beneficiaries.sex = "female" THEN 1 ELSE 0 END) AS total_senior_female')
                ])
                ->where('implementations.id', $implementation->id)
                ->groupBy(['batches.barangay_name'])
                ->get();

            # Barangay Information
            $barangays = [];

            foreach ($batches as $batch) {
                $barangays[] = [
                    'barangay_name' => $batch->barangay_name,
                    'total_male' => $batch->total_male,
                    'total_female' => $batch->total_female,
                    'total_pwd_male' => $batch->total_pwd_male,
                    'total_pwd_female' => $batch->total_pwd_female,
                    'total_senior_male' => $batch->total_senior_male,
                    'total_senior_female' => $batch->total_senior_female,
                ];
            }

            $information[] = [
                'implementationInformation' => $implementationInformation,
                'summaryCount' => $summaryCount,
                'barangays' => $barangays,
            ];
        }

        return $information;
    }
}