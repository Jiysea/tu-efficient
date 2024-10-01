<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ProcessImportSimilarity implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $filePath;

    /**
     * Create a new job instance.
     */
    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Load the uploaded Excel file using PhpSpreadsheet
        $spreadsheet = IOFactory::load(storage_path('app/' . $this->filePath));
        $worksheet = $spreadsheet->getActiveSheet();
        $maxDataRow = $worksheet->getHighestDataRow();

        foreach ($worksheet->getRowIterator(13, $maxDataRow - 16) as $row) {
            if ($row->isEmpty(startColumn: 'A', endColumn: 'AA')) {
                continue;
            }

            dump($row->getRowIndex());
            foreach ($row->getCellIterator() as $cell) {
                dump($cell->getValue());
            }
        }
    }
}
