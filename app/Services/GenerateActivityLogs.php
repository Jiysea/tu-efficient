<?php

namespace App\Services;
use App\Models\Beneficiary;
use App\Models\SystemsLog;

class GenerateActivityLogs
{
    public static function set_register_coordinator_log(int $users_id, string $full_name)
    {
        
    }

    public static function set_register_partner_log(int $users_id, string $full_name)
    {

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

    public static function set_import_beneficiaries_success_log(int $users_id, string $barangay_name, int $added_count)
    {
        SystemsLog::factory()->create([
            'users_id' => $users_id,
            'log_timestamp' => now(),
            'description' => 'Imported (' . $added_count . ') beneficiaries in Brgy. ' . $barangay_name . ' with no duplications.',
        ]);
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

    public static function set_delete_beneficiary_log(int $users_id, Beneficiary $beneficiary, string $project_num, string $batch_num)
    {
        SystemsLog::factory()->create([
            'users_id' => $users_id,
            'log_timestamp' => now(),
            'description' => 'Deleted ' . self::full_name($beneficiary) . ' from Project: ' . $project_num . ' -> Batch: ' . $batch_num . '',
        ]);
    }

    public static function set_archive_beneficiary_log(int $users_id, )
    {

    }

    public static function set_barangay_submit_log(string $barangay_name, string $project_num, string $batch_num, int $added_count, int $slots_allocated, int $special_cases)
    {
        SystemsLog::factory()->create([
            'users_id' => null,
            'log_timestamp' => now(),
            'description' => 'A barangay official in Brgy. ' . $barangay_name . ' has submitted a list of (' . $added_count . ') / (' . $slots_allocated . ') beneficiaries with (' . $special_cases . ') special cases. (Project: ' . $project_num . ' / Batch: ' . $batch_num . ')',
        ]);
    }

    public static function set_barangay_added_special_case_log(string $barangay_name, string $project_num, string $batch_num, string $full_name)
    {
        SystemsLog::factory()->create([
            'users_id' => null,
            'log_timestamp' => now(),
            'description' => $full_name . ' added as special case from Brgy. ' . $barangay_name . '. (Project: ' . $project_num . ' / Batch: ' . $batch_num . ')',
        ]);
    }

    public static function set_barangay_modify_special_case_log(string $barangay_name, string $project_num, string $batch_num, string $full_name)
    {
        SystemsLog::factory()->create([
            'users_id' => null,
            'log_timestamp' => now(),
            'description' => 'A barangay official modified ' . $full_name . '\'s special case from Brgy. ' . $barangay_name . '. (Project: ' . $project_num . ' / Batch: ' . $batch_num . ')',
        ]);
    }

    public static function set_barangay_delete_beneficiary_log(string $barangay_name, string $project_num, string $batch_num, string $full_name)
    {
        SystemsLog::factory()->create([
            'users_id' => null,
            'log_timestamp' => now(),
            'description' => 'A barangay official deleted ' . $full_name . ' from the Brgy. ' . $barangay_name . ' list. (Project: ' . $project_num . ' / Batch: ' . $batch_num . ')',
        ]);
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
}