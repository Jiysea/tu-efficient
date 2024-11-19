<?php

namespace App\Services;
use App\Models\Archive;
use App\Models\Assignment;
use App\Models\Batch;
use App\Models\Beneficiary;
use App\Models\Code;
use App\Models\Credential;
use App\Models\Implementation;
use App\Models\SystemsLog;
use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Auth\Authenticatable;

class LogIt
{
    # From Seeder/Users
    public static function set_register_user(User|Authenticatable $user, int $users_id = null)
    {
        SystemsLog::factory()->create([
            'users_id' => $users_id,
            'log_timestamp' => $user->created_at ?? now(),
            'description' => self::full_name($user) . ' has been created as ' . $user->user_type . ' in ' . $user->field_office . ' field office.'
        ]);
    }

    public static function set_change_fullname(User|Authenticatable $user, string $old, string $new)
    {
        SystemsLog::factory()->create([
            'users_id' => $user->id,
            'log_timestamp' => $user->created_at ?? now(),
            'description' => $old . ' changed their name to ' . $old . '. Field office: ' . $user->field_office . '.'
        ]);
    }

    public static function set_initialization_of_user_settings(UserSetting $settings)
    {
        SystemsLog::factory()->create([
            'users_id' => null,
            'log_timestamp' => now(),
            'description' => 'A setting ' . $settings->key . ' has been initialized with ' . $settings->value . ' for ' . self::full_name($settings->users_id) . '.'
        ]);
    }

    public static function set_edit_user(User|Authenticatable $user, int $users_id = null)
    {
        SystemsLog::factory()->create([
            'users_id' => $users_id,
            'log_timestamp' => $user->created_at ?? now(),
            'description' => self::full_name($user) . ' from ' . $user->field_office . ' field office has been modified.'
        ]);
    }

    public static function set_delete_user(User|Authenticatable $user, int $users_id = null)
    {
        SystemsLog::factory()->create([
            'users_id' => $users_id,
            'log_timestamp' => $user->created_at ?? now(),
            'description' => self::full_name($user) . ' from ' . $user->field_office . ' field office has been deleted.'
        ]);
    }

    # ----------------------------------------------------------------------------------------------

    public static function set_settings_password_change(User|Authenticatable $user)
    {
        SystemsLog::factory()->create([
            'users_id' => $user->id,
            'log_timestamp' => now(),
            'description' => self::full_name($user) . ' changed their password.',
        ]);
    }

    public static function set_minimum_wage_settings(User|Authenticatable $user, $old_wage, $new_wage)
    {
        SystemsLog::factory()->create([
            'users_id' => $user->id,
            'log_timestamp' => now(),
            'description' => 'Minimum wage changed from ₱' . $old_wage . ' to ₱' . $new_wage . ' in ' . $user->field_office . ' field office.',
        ]);
    }

    public static function set_project_prefix_settings(User|Authenticatable $user, $old_prefix, $new_prefix)
    {
        SystemsLog::factory()->create([
            'users_id' => $user->id,
            'log_timestamp' => now(),
            'description' => 'Changed the Implementation Project number prefix from \'' . $old_prefix . '\' to \'' . $new_prefix . '\' in ' . $user->field_office . ' field office.',
        ]);
    }

    public static function set_batch_prefix_settings(User|Authenticatable $user, $old_prefix, $new_prefix)
    {
        SystemsLog::factory()->create([
            'users_id' => $user->id,
            'log_timestamp' => now(),
            'description' => 'Changed the Batch number prefix from \'' . $old_prefix . '\' to \'' . $new_prefix . '\' in ' . $user->field_office . ' field office.',
        ]);
    }

    public static function set_maximum_income_settings(User|Authenticatable $user, $old_income, $new_income)
    {
        SystemsLog::factory()->create([
            'users_id' => $user->id,
            'log_timestamp' => now(),
            'description' => 'Maximum average monthly income changed from ₱' . $old_income . ' to ₱' . $new_income . ' in ' . $user->field_office . ' field office.',
        ]);
    }

    public static function set_duplication_threshold_settings(User|Authenticatable $user, $old_threshold, $new_threshold)
    {
        SystemsLog::factory()->create([
            'users_id' => $user->id,
            'log_timestamp' => now(),
            'description' => 'Duplication threshold changed from ' . $old_threshold . '% to ' . $new_threshold . '% in ' . $user->field_office . ' field office.',
        ]);
    }

    public static function set_default_archive_settings(User|Authenticatable $user, $old_value, $new_value)
    {
        SystemsLog::factory()->create([
            'users_id' => $user->id,
            'log_timestamp' => now(),
            'description' => ($new_value ? 'Enabled' : 'Disabled') . ' default archive setting in ' . $user->field_office . ' field office.',
        ]);
    }

    public static function set_create_project(Implementation $implementation)
    {
        SystemsLog::factory()->create([
            'users_id' => $implementation->users_id,
            'log_timestamp' => now(),
            'description' => 'Created an implementation project \'' . $implementation->project_num . '\'.',
        ]);
    }

    public static function set_create_batches(Batch $batch)
    {
        SystemsLog::factory()->create([
            'users_id' => Implementation::find($batch->implementations_id)->users_id,
            'log_timestamp' => now(),
            'description' => 'Created a batch \'' . $batch->batch_num . '\' -> in implementation project \'' . Implementation::find($batch->implementations_id)->project_num . '\'.',
        ]);
    }

    public static function set_assign_coordinator_to_batch(Assignment $assignment)
    {
        $batch = Batch::find($assignment->batches_id);
        SystemsLog::factory()->create([
            'users_id' => Implementation::find($batch->implementations_id)->users_id,
            'log_timestamp' => now(),
            'description' => 'Assigned ' . self::full_name($assignment->users_id) . ' to batch \'' . $batch->batch_num . '\'.',
        ]);
    }

    public static function set_pend_batch(Batch $batch)
    {
        SystemsLog::factory()->create([
            'users_id' => Implementation::find($batch->implementations_id)->users_id,
            'log_timestamp' => now(),
            'description' => 'Batch \' ' . $batch->batch_num . '\' has been set to pending.',
        ]);
    }

    public static function set_force_approve(Batch $batch)
    {
        SystemsLog::factory()->create([
            'users_id' => Implementation::find($batch->implementations_id)->users_id,
            'log_timestamp' => now(),
            'description' => 'Batch \' ' . $batch->batch_num . '\' has been forced set to approved.',
        ]);
    }

    public static function set_open_access(Code $code, User|Authenticatable $user)
    {
        $batch = Batch::find($code->batches_id);
        SystemsLog::factory()->create([
            'users_id' => $user->id,
            'log_timestamp' => now(),
            'description' => self::full_name($user) . ' opened the batch \' ' . $batch->batch_num . '\' for access.',
        ]);
    }

    public static function set_force_submit_batch(User|Authenticatable $user, Batch $batch)
    {
        SystemsLog::factory()->create([
            'users_id' => Implementation::find($batch->implementations_id)->users_id,
            'log_timestamp' => now(),
            'description' => self::full_name($user) . ' has force submitted a batch \'' . $batch->batch_num . '\'.',
        ]);
    }

    public static function set_add_beneficiary(Beneficiary $beneficiary, int $users_id = null)
    {
        $batch = Batch::find($beneficiary->batches_id);
        SystemsLog::factory()->create([
            'users_id' => $users_id ?? Implementation::find($batch->implementations_id)->users_id,
            'log_timestamp' => now(),
            'description' => 'Added ' . self::full_name($beneficiary) . ' as beneficiary from batch \'' . $batch->batch_num . '\' -> implementation project \'' . Implementation::find($batch->implementations_id)->project_num . '\'.',
        ]);
    }

    public static function set_import_success(User|Authenticatable $user, Batch $batch, int $added_count)
    {
        SystemsLog::factory()->create([
            'users_id' => $user->id,
            'log_timestamp' => now(),
            'description' => self::full_name($user) . ' successfully imported ' . $added_count . ' beneficiaries in batch \'' . $batch->batch_num . '\'.',
        ]);
    }

    public static function set_import_special_cases(User|Authenticatable $user, Batch $batch, int $special_count)
    {
        SystemsLog::factory()->create([
            'users_id' => $user->id,
            'log_timestamp' => now(),
            'description' => self::full_name($user) . ' imported ' . $special_count . ' special cases in batch \'' . $batch->batch_num . '\'.',
        ]);
    }

    public static function set_edit_project(Implementation $implementation)
    {
        SystemsLog::factory()->create([
            'users_id' => $implementation->users_id,
            'log_timestamp' => now(),
            'description' => 'Modified the implementation project \'' . $implementation->project_num . '\'.',
        ]);
    }

    public static function set_edit_batches(Batch $batch)
    {
        SystemsLog::factory()->create([
            'users_id' => Implementation::find($batch->implementations_id)->users_id,
            'log_timestamp' => now(),
            'description' => 'Modified a batch \'' . $batch->batch_num . '\' -> in implementation project \'' . Implementation::find($batch->implementations_id)->project_num . '\'.',
        ]);
    }

    public static function set_edit_beneficiary(Beneficiary $beneficiary, int $users_id = null)
    {
        $batch = Batch::find($beneficiary->batches_id);
        SystemsLog::factory()->create([
            'users_id' => $users_id ?? Implementation::find($batch->implementations_id)->users_id,
            'log_timestamp' => now(),
            'description' => 'A beneficiary (' . self::full_name($beneficiary) . ') is modified from batch \'' . $batch->batch_num . '\' -> implementation project \'' . Implementation::find($batch->implementations_id)->project_num . '\'.',
        ]);
    }

    public static function set_edit_beneficiary_special_case(Beneficiary $beneficiary, Credential $credential, int $users_id = null)
    {
        $batch = Batch::find($beneficiary->batches_id);
        SystemsLog::factory()->create([
            'users_id' => $users_id ?? Implementation::find($batch->implementations_id)->users_id,
            'log_timestamp' => now(),
            'description' => 'Modified a beneficiary\'s (' . self::full_name($beneficiary) . ') special case from batch \'' . $batch->batch_num . '\' -> implementation project \'' . Implementation::find($batch->implementations_id)->project_num . '\'.',
        ]);
    }

    public static function set_delete_project(Implementation $implementation)
    {
        SystemsLog::factory()->create([
            'users_id' => $implementation->users_id,
            'log_timestamp' => now(),
            'description' => 'Deleted the implementation project \'' . $implementation->project_num . '\'.',
        ]);
    }

    public static function set_delete_batches(Batch $batch)
    {
        SystemsLog::factory()->create([
            'users_id' => Implementation::find($batch->implementations_id)->users_id,
            'log_timestamp' => now(),
            'description' => 'Deleted the batch \'' . $batch->batch_num . '\' -> in implementation project \'' . Implementation::find($batch->implementations_id)->project_num . '\'.',
        ]);
    }

    public static function set_remove_coordinator_assignment(Assignment $assignment)
    {
        $batch = Batch::find($assignment->batches_id);
        SystemsLog::factory()->create([
            'users_id' => Implementation::find($batch->implementations_id)->users_id,
            'log_timestamp' => now(),
            'description' => 'Removed ' . self::full_name($assignment->users_id) . ' from batch \'' . $batch->batch_num . '\' assignment.',
        ]);
    }

    public static function set_delete_beneficiary(Beneficiary $beneficiary, int $users_id = null)
    {
        $batch = Batch::find($beneficiary->batches_id);
        $implementation = Implementation::find($batch->implementations_id);
        SystemsLog::factory()->create([
            'users_id' => $users_id ?? Implementation::find($batch->implementations_id)->users_id,
            'log_timestamp' => now(),
            'description' => 'Deleted ' . self::full_name($beneficiary) . ' from Project \'' . $implementation->project_num . '\' -> Batch \'' . $batch->batch_num . '\'.',
        ]);
    }

    public static function set_archive_beneficiary(Beneficiary $beneficiary)
    {
        $batch = Batch::find($beneficiary->batches_id);
        $implementation = Implementation::find($batch->implementations_id);
        SystemsLog::factory()->create([
            'users_id' => $implementation->users_id,
            'log_timestamp' => now(),
            'description' => 'Moved a beneficiary (' . self::full_name($beneficiary) . ') to Archives. Project \'' . $implementation->project_num . '\' -> Batch \'' . $batch->batch_num . '\'',
        ]);
    }

    public static function set_restore_archive(Archive $archive, int $users_id)
    {
        $batch = Batch::find($archive->data['batches_id']);
        $implementation = Implementation::find($batch->implementations_id);
        SystemsLog::factory()->create([
            'users_id' => $users_id,
            'log_timestamp' => now(),
            'description' => 'Restored ' . self::full_name($archive->data) . ' back from Archives. Project \'' . $implementation->project_num . '\' -> Batch \'' . $batch->batch_num . '\'',
        ]);
    }

    public static function set_permanently_delete_archive(Archive $archive, int $users_id)
    {
        $batch = Batch::find($archive->data['batches_id']);
        $implementation = Implementation::find($batch->implementations_id);
        SystemsLog::factory()->create([
            'users_id' => $users_id,
            'log_timestamp' => now(),
            'description' => 'Permanently deleted ' . self::full_name($archive->data) . '. Project \'' . $implementation->project_num . '\' -> Batch \'' . $batch->batch_num . '\'',
        ]);
    }

    # Coordinators -------------------------------------------------------------------------------------------------------------------

    public static function set_approve_batch(User|Authenticatable $user, Batch $batch)
    {
        SystemsLog::factory()->create([
            'users_id' => $user->id,
            'log_timestamp' => now(),
            'description' => self::full_name($user) . ' approved the batch \'' . $batch->batch_num . '\'.',
        ]);
    }

    public static function set_barangay_submit(string $barangay_name, string $project_num, string $batch_num, int $added_count, int $slots_allocated, int $special_cases)
    {
        SystemsLog::factory()->create([
            'users_id' => null,
            'log_timestamp' => now(),
            'description' => 'A barangay official in Brgy. ' . $barangay_name . ' has submitted a list of (' . $added_count . ') / (' . $slots_allocated . ') beneficiaries with (' . $special_cases . ') special cases. (Project: ' . $project_num . ' / Batch: ' . $batch_num . ')',
        ]);
    }

    public static function set_barangay_added_special_case(string $barangay_name, string $project_num, string $batch_num, string $full_name)
    {
        SystemsLog::factory()->create([
            'users_id' => null,
            'log_timestamp' => now(),
            'description' => $full_name . ' added as special case from Brgy. ' . $barangay_name . '. (Project: ' . $project_num . ' / Batch: ' . $batch_num . ')',
        ]);
    }

    public static function set_barangay_modify_special_case(string $barangay_name, string $project_num, string $batch_num, string $full_name)
    {
        SystemsLog::factory()->create([
            'users_id' => null,
            'log_timestamp' => now(),
            'description' => 'A barangay official modified ' . $full_name . '\'s special case from Brgy. ' . $barangay_name . '. (Project: ' . $project_num . ' / Batch: ' . $batch_num . ')',
        ]);
    }

    public static function set_barangay_delete_beneficiary(string $barangay_name, string $project_num, string $batch_num, string $full_name)
    {
        SystemsLog::factory()->create([
            'users_id' => null,
            'log_timestamp' => now(),
            'description' => 'A barangay official deleted ' . $full_name . ' from the Brgy. ' . $barangay_name . ' list. (Project: ' . $project_num . ' / Batch: ' . $batch_num . ')',
        ]);
    }

    public static function set_log_exception(\Exception $exception)
    {
        SystemsLog::factory()->create([
            'users_id' => null,
            'log_timestamp' => now(),
            'description' => $exception->getMessage(),
        ]);
    }

    protected static function full_name(User|Authenticatable|Beneficiary|array|int $person)
    {
        $full_name = null;
        if (gettype($person) === 'integer') {
            $person = User::find($person);
        }

        $full_name = $person['first_name'];

        if ($person['middle_name']) {
            $full_name .= ' ' . $person['middle_name'];
        }

        $full_name .= ' ' . $person['last_name'];

        if ($person['extension_name']) {
            $full_name .= ' ' . $person['extension_name'];
        }

        return $full_name;
    }
}