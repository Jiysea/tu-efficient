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

    public static function set_create_project_log(int $users_id, )
    {

    }

    public static function set_assign_batches_log(int $users_id, )
    {

    }

    public static function set_add_beneficiary_log(int $users_id, )
    {

    }

    public static function set_edit_project_log(int $users_id, )
    {

    }

    public static function set_edit_batches_log(int $users_id, )
    {

    }

    public static function set_edit_beneficiary_log(int $users_id, )
    {

    }

    public static function set_delete_project_log(int $users_id, )
    {

    }

    public static function set_delete_batches_log(int $users_id, )
    {

    }

    public static function set_delete_beneficiary_log(int $users_id, )
    {

    }

    public static function set_archive_beneficiary_log(int $users_id, )
    {

    }
}