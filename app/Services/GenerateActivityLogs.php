<?php

namespace App\Services;
use App\Models\SystemsLog;

class GenerateActivityLogs
{
    public static function set_import_beneficiaries_success_log(int $users_id, string $barangay_name, int $added_count)
    {
        SystemsLog::factory()->create([
            'users_id' => $users_id,
            'log_timestamp' => now(),
            'description' => 'Imported (' . $added_count . ') beneficiaries in Brgy. ' . $barangay_name . ' with no duplications.',
        ]);
    }
}