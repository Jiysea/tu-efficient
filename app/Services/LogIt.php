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
use DB;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Request;

class LogIt
{
    # From Seeder/Users
    public static function set_register_user(User|Collection $user, int $users_id = null, string $sender = null, string $log_type = 'create', mixed $timestamp = null)
    {
        SystemsLog::create([
            'users_id' => $users_id,
            'alternative_sender' => $sender,
            'description' => self::full_name($user) . ' has been created as ' . $user->user_type . ' in ' . $user->field_office . ' regional office -> ' . $user->field_office . ' field office.',
            'regional_office' => $user->regional_office,
            'field_office' => $user->field_office,
            'log_type' => $log_type,
            'log_timestamp' => $timestamp ?? now(),
        ]);
    }

    public static function set_change_fullname(User|Authenticatable $user, string $old, string $new, mixed $timestamp = null)
    {
        SystemsLog::create([
            'users_id' => $user->id,
            'description' => $old . ' changed their name to ' . $new . '. ' . $user->field_office . ' field office.',
            'regional_office' => $user->regional_office,
            'field_office' => $user->field_office,
            'log_type' => 'update',
            'log_timestamp' => $timestamp ?? now(),
        ]);
    }

    public static function set_initialization_of_user_settings(UserSetting|Collection $settings, string $sender, string $regional_office, string $field_office, mixed $timestamp = null)
    {
        SystemsLog::create([
            'users_id' => null,
            'alternative_sender' => $sender,
            'description' => 'A setting ' . $settings->key . ' has been initialized with ' . $settings->value . ' for ' . self::full_name($settings->users_id) . '.',
            'regional_office' => $regional_office,
            'field_office' => $field_office,
            'log_type' => 'initialize',
            'log_timestamp' => $timestamp ?? now(),
        ]);
    }

    public static function set_edit_user(User|Collection $modifiedUser, User|Authenticatable $user, mixed $timestamp = null)
    {
        SystemsLog::create([
            'users_id' => $user->id,
            'description' => 'Coordinator ' . self::full_name($modifiedUser) . ' from ' . $modifiedUser->field_office . ' field office has been modified.',
            'regional_office' => $user->regional_office,
            'field_office' => $user->field_office,
            'log_type' => 'update',
            'log_timestamp' => $timestamp ?? now(),
        ]);
    }

    public static function set_delete_user(User|Collection $modifiedUser, User|Authenticatable $user, mixed $timestamp = null)
    {
        $stackedData = [
            'users' => $modifiedUser->toArray(),
        ];

        SystemsLog::create([
            'users_id' => $user->id,
            'description' => 'Coordinator ' . self::full_name($modifiedUser) . ' from ' . $modifiedUser->field_office . ' field office has been deleted.',
            'old_data' => json_encode($stackedData),
            'main_table' => 'users',
            'regional_office' => $user->regional_office,
            'field_office' => $user->field_office,
            'log_type' => 'delete',
            'log_timestamp' => $timestamp ?? now(),
        ]);
    }

    public static function set_settings_password_change(User|Authenticatable $user)
    {
        SystemsLog::create([
            'users_id' => $user->id,
            'description' => 'They changed their password. ' . $user->field_office . ' field office.',
            'regional_office' => $user->regional_office,
            'field_office' => $user->field_office,
            'log_type' => 'update',
            'log_timestamp' => now(),
        ]);
    }

    public static function set_minimum_wage_settings(User|Authenticatable $user, $old_wage, $new_wage)
    {
        SystemsLog::create([
            'users_id' => $user->id,
            'description' => 'Changed the \'Minimum Wage\' global settings from ₱' . $old_wage . ' to ₱' . $new_wage . '. ' . $user->field_office . ' field office.',
            'regional_office' => $user->regional_office,
            'field_office' => $user->field_office,
            'log_type' => 'update',
            'log_timestamp' => now(),
        ]);
    }

    public static function set_project_prefix_settings(User|Authenticatable $user, $old_prefix, $new_prefix)
    {
        SystemsLog::create([
            'users_id' => $user->id,
            'description' => 'Changed the \'Project Number Prefix\' global settings from ' . $old_prefix . ' to ' . $new_prefix . '. ' . $user->field_office . ' field office.',
            'regional_office' => $user->regional_office,
            'field_office' => $user->field_office,
            'log_type' => 'update',
            'log_timestamp' => now(),
        ]);
    }

    public static function set_batch_prefix_settings(User|Authenticatable $user, $old_prefix, $new_prefix)
    {
        SystemsLog::create([
            'users_id' => $user->id,
            'description' => 'Changed the \'Batch Number Prefix\' global settings from ' . $old_prefix . ' to ' . $new_prefix . '. ' . $user->field_office . ' field office.',
            'regional_office' => $user->regional_office,
            'field_office' => $user->field_office,
            'log_type' => 'update',
            'log_timestamp' => now(),
        ]);
    }

    public static function set_maximum_income_settings(User|Authenticatable $user, $old_income, $new_income)
    {
        SystemsLog::create([
            'users_id' => $user->id,
            'description' => 'Changed the \'Maximum Income\' global settings from ₱' . $old_income . ' to ₱' . $new_income . '. ' . $user->field_office . ' field office.',
            'regional_office' => $user->regional_office,
            'field_office' => $user->field_office,
            'log_type' => 'update',
            'log_timestamp' => now(),
        ]);
    }

    public static function set_duplication_threshold_settings(User|Authenticatable $user, $old_threshold, $new_threshold)
    {
        SystemsLog::create([
            'users_id' => $user->id,
            'description' => 'Changed their \'Duplication Threshold\' personal settings from ' . $old_threshold . '% to ' . $new_threshold . '%. ' . $user->field_office . ' field office.',
            'regional_office' => $user->regional_office,
            'field_office' => $user->field_office,
            'log_type' => 'update',
            'log_timestamp' => now(),
        ]);
    }

    public static function set_default_archive_settings(User|Authenticatable $user, $old_value, $new_value)
    {
        SystemsLog::create([
            'users_id' => $user->id,
            'description' => ($new_value ? 'Enabled' : 'Disabled') . ' their \'Default Archive\' personal settings. ' . $user->field_office . ' field office.',
            'regional_office' => $user->regional_office,
            'field_office' => $user->field_office,
            'log_type' => 'update',
            'log_timestamp' => now(),
        ]);
    }

    public static function set_default_show_duplicates_settings(User|Authenticatable $user, $old_value, $new_value)
    {
        SystemsLog::create([
            'users_id' => $user->id,
            'description' => ($new_value ? 'Enabled' : 'Disabled') . ' their \'Show Duplicates by Default\' personal settings. ' . $user->field_office . ' field office.',
            'regional_office' => $user->regional_office,
            'field_office' => $user->field_office,
            'log_type' => 'update',
            'log_timestamp' => now(),
        ]);
    }

    # ----------------------------------------------------------------------------------------------

    public static function set_create_project(Implementation|Collection $implementation, User|Authenticatable $user, mixed $timestamp = null)
    {
        SystemsLog::create([
            'users_id' => $user->id,
            'description' => 'Created an implementation project \'' . $implementation->project_num . '\'.',
            'regional_office' => $user->regional_office,
            'field_office' => $user->field_office,
            'log_type' => 'create',
            'log_timestamp' => $timestamp ?? now(),
        ]);
    }

    public static function set_create_batches(Implementation|Collection $implementation, Batch|Collection $batch, User|Authenticatable $user, mixed $timestamp = null)
    {
        SystemsLog::create([
            'users_id' => $user->id,
            'description' => 'Created a batch \'' . $batch->batch_num . '\' -> implementation project \'' . $implementation->project_num . '\'.',
            'regional_office' => $user->regional_office,
            'field_office' => $user->field_office,
            'log_type' => 'create',
            'log_timestamp' => $timestamp ?? now(),
        ]);
    }

    public static function set_assign_coordinator_to_batch(Assignment|Collection $assignment, User|Authenticatable $user, string $log_type = 'create', mixed $timestamp = null)
    {
        $batch = Batch::find($assignment->batches_id);
        SystemsLog::create([
            'users_id' => $user->id,
            'description' => 'Assigned ' . self::full_name($assignment->users_id) . ' to batch \'' . $batch->batch_num . '\'.',
            'regional_office' => $user->regional_office,
            'field_office' => $user->field_office,
            'log_type' => $log_type,
            'log_timestamp' => $timestamp ?? now(),
        ]);
    }

    public static function set_pend_batch(Batch|Collection $batch, User|Authenticatable $user, mixed $timestamp = null)
    {
        SystemsLog::create([
            'users_id' => $user->id,
            'description' => 'Batch \' ' . $batch->batch_num . '\' has been set to pending.',
            'regional_office' => $user->regional_office,
            'field_office' => $user->field_office,
            'log_type' => 'update',
            'log_timestamp' => $timestamp ?? now(),
        ]);
    }

    public static function set_force_approve(Batch|Collection $batch, User|Authenticatable $user, mixed $timestamp = null)
    {
        SystemsLog::create([
            'users_id' => $user->id,
            'description' => 'Batch \' ' . $batch->batch_num . '\' has been approved by force.',
            'regional_office' => $user->regional_office,
            'field_office' => $user->field_office,
            'log_type' => 'update',
            'log_timestamp' => $timestamp ?? now(),
        ]);
    }

    public static function set_add_beneficiary(Implementation|Collection $implementation, Batch|Collection $batch, Beneficiary|Collection $beneficiary, User|Authenticatable $user, mixed $timestamp = null)
    {
        SystemsLog::create([
            'users_id' => $user->id,
            'description' => 'Added ' . self::full_name($beneficiary) . ' as beneficiary in batch \'' . $batch->batch_num . '\' -> implementation project \'' . $implementation->project_num . '\'.',
            'regional_office' => $user->regional_office,
            'field_office' => $user->field_office,
            'log_type' => 'create',
            'log_timestamp' => $timestamp ?? now(),
        ]);
    }

    public static function set_add_beneficiary_special_case(Implementation|Collection $implementation, Batch|Collection $batch, Beneficiary|Collection $beneficiary, User|Authenticatable $user, mixed $timestamp = null)
    {
        SystemsLog::create([
            'users_id' => $user->id,
            'description' => 'Added ' . self::full_name($beneficiary) . ' as a special case beneficiary in batch \'' . $batch->batch_num . '\' -> implementation project \'' . $implementation->project_num . '\'.',
            'regional_office' => $user->regional_office,
            'field_office' => $user->field_office,
            'log_type' => 'create',
            'log_timestamp' => $timestamp ?? now(),
        ]);
    }

    public static function set_import_success(Implementation|Collection $implementation, Batch|Collection $batch, User|Collection|Authenticatable $user, int $added_count)
    {
        SystemsLog::create([
            'users_id' => $user->id,
            'description' => 'Imported ' . $added_count . ' beneficiaries. Project: \'' . $implementation->project_num . '\' -> Batch: \'' . $batch->batch_num . ' \'.',
            'regional_office' => $user->regional_office,
            'field_office' => $user->field_office,
            'log_type' => 'create',
            'log_timestamp' => now(),
        ]);
    }

    public static function set_import_special_cases(Batch|Collection $batch, User|Authenticatable $user, int $special_count)
    {
        SystemsLog::create([
            'users_id' => $user->id,
            'description' => 'Imported ' . $special_count . ' special cases in batch \'' . $batch->batch_num . '\'.',
            'regional_office' => $user->regional_office,
            'field_office' => $user->field_office,
            'log_type' => 'create',
            'log_timestamp' => now(),
        ]);
    }

    public static function set_edit_project(Implementation|Collection $implementation, User|Authenticatable $user, mixed $timestamp = null)
    {
        SystemsLog::create([
            'users_id' => $user->id,
            'description' => 'Modified the implementation project \'' . $implementation->project_num . '\'.',
            'regional_office' => $user->regional_office,
            'field_office' => $user->field_office,
            'log_type' => 'update',
            'log_timestamp' => $timestamp ?? now(),
        ]);
    }

    public static function set_edit_batches(Implementation|Collection $implementation, Batch|Collection $batch, User|Authenticatable $user, mixed $timestamp = null)
    {
        SystemsLog::create([
            'users_id' => $user->id,
            'description' => 'Modified a batch \'' . $batch->batch_num . '\' -> in implementation project \'' . $implementation->project_num . '\'.',
            'regional_office' => $user->regional_office,
            'field_office' => $user->field_office,
            'log_type' => 'update',
            'log_timestamp' => $timestamp ?? now(),
        ]);
    }

    public static function set_edit_beneficiary(Implementation|Collection $implementation, Batch|Collection $batch, Beneficiary|Collection $beneficiary, User|Authenticatable $user, mixed $timestamp = null)
    {
        SystemsLog::create([
            'users_id' => $user->id,
            'description' => 'A beneficiary (' . self::full_name($beneficiary) . ') is modified in batch \'' . $batch->batch_num . '\' -> implementation project \'' . $implementation->project_num . '\'.',
            'regional_office' => $user->regional_office,
            'field_office' => $user->field_office,
            'log_type' => 'update',
            'log_timestamp' => $timestamp ?? now(),
        ]);
    }

    public static function set_edit_beneficiary_identity(Implementation|Collection $implementation, Batch|Collection $batch, Beneficiary|Collection $beneficiary, Credential|Collection $credential, User|Authenticatable $user, mixed $timestamp = null)
    {
        SystemsLog::create([
            'users_id' => $user->id,
            'description' => 'Modified a beneficiary\'s (' . self::full_name($beneficiary) . ') proof of identity (ID Picture) from batch \'' . $batch->batch_num . '\' -> implementation project \'' . $implementation->project_num . '\'.',
            'regional_office' => $user->regional_office,
            'field_office' => $user->field_office,
            'log_type' => 'update',
            'log_timestamp' => $timestamp ?? now(),
        ]);
    }

    public static function set_remove_beneficiary_identity(Implementation|Collection $implementation, Batch|Collection $batch, Beneficiary|Collection $beneficiary, Credential|Collection $credential, User|Authenticatable $user, mixed $timestamp = null)
    {
        $stackedData = [
            'implementations' => $implementation->toArray(),
            'batches' => $batch->toArray(),
            'beneficiaries' => $beneficiary->toArray(),
            'credentials' => $credential->toArray(),
        ];

        SystemsLog::create([
            'users_id' => $user->id,
            'description' => 'Removed a beneficiary\'s (' . self::full_name($beneficiary) . ') proof of identity (ID Picture) from batch \'' . $batch->batch_num . '\' -> implementation project \'' . $implementation->project_num . '\'.',
            'old_data' => json_encode($stackedData),
            'main_table' => 'credentials',
            'regional_office' => $user->regional_office,
            'field_office' => $user->field_office,
            'log_type' => 'remove',
            'log_timestamp' => $timestamp ?? now(),
        ]);
    }

    public static function set_edit_beneficiary_special_case(Implementation|Collection $implementation, Batch|Collection $batch, Beneficiary|Collection $beneficiary, Credential|Collection $credential, User|Authenticatable $user, mixed $timestamp = null)
    {
        SystemsLog::create([
            'users_id' => $user->id,
            'description' => 'Modified the special case (description: \'' . $credential->image_description . '\') from a beneficiary (' . self::full_name($beneficiary) . ') in batch \'' . $batch->batch_num . '\' -> implementation project \'' . $implementation->project_num . '\'.',
            'regional_office' => $user->regional_office,
            'field_office' => $user->field_office,
            'log_type' => 'update',
            'log_timestamp' => $timestamp ?? now(),
        ]);
    }

    public static function set_remove_beneficiary_special_case(Implementation|Collection $implementation, Batch|Collection $batch, Beneficiary|Collection $beneficiary, Credential|Collection $credential, User|Authenticatable $user, mixed $timestamp = null)
    {
        $stackedData = [
            'implementations' => $implementation->toArray(),
            'batches' => $batch->toArray(),
            'beneficiaries' => $beneficiary->toArray(),
            'credentials' => $credential->toArray(),
        ];

        SystemsLog::create([
            'users_id' => $user->id,
            'description' => 'Removed the special case (description: \'' . $credential->image_description . '\') from a beneficiary (' . self::full_name($beneficiary) . ') in batch \'' . $batch->batch_num . '\' -> implementation project \'' . $implementation->project_num . '\'.',
            'old_data' => json_encode($stackedData),
            'main_table' => 'credentials',
            'regional_office' => $user->regional_office,
            'field_office' => $user->field_office,
            'log_type' => 'remove',
            'log_timestamp' => $timestamp ?? now(),
        ]);
    }

    public static function set_delete_project(Implementation|Collection $implementation, User|Authenticatable $user, mixed $timestamp = null)
    {
        $stackedData = [
            'implementations' => $implementation->toArray(),
        ];

        SystemsLog::create([
            'users_id' => $user->id,
            'description' => 'Deleted the implementation project \'' . $implementation->project_num . '\'.',
            'old_data' => json_encode($stackedData),
            'main_table' => 'implementations',
            'regional_office' => $user->regional_office,
            'field_office' => $user->field_office,
            'log_type' => 'delete',
            'log_timestamp' => $timestamp ?? now(),
        ]);
    }

    public static function set_delete_batches(Implementation|Collection $implementation, Batch|Collection $batch, User|Authenticatable $user, mixed $timestamp = null)
    {
        $stackedData = [
            'implementations' => $implementation->toArray(),
            'batches' => $batch->toArray(),
        ];

        SystemsLog::create([
            'users_id' => $user->id,
            'description' => 'Deleted the batch \'' . $batch->batch_num . '\' -> in implementation project \'' . $implementation->project_num . '\'.',
            'old_data' => json_encode($stackedData),
            'main_table' => 'batches',
            'regional_office' => $user->regional_office,
            'field_office' => $user->field_office,
            'log_type' => 'delete',
            'log_timestamp' => $timestamp ?? now(),
        ]);
    }

    public static function set_remove_coordinator_assignment(Assignment|Collection $assignment, Batch|Collection $batch, User|Authenticatable $user, mixed $timestamp = null)
    {
        $stackedData = [
            'assignments' => $assignment->toArray(),
            'batches' => $batch->toArray(),
        ];

        SystemsLog::create([
            'users_id' => $user->id,
            'description' => 'Removed ' . self::full_name($assignment->users_id) . ' from batch \'' . $batch->batch_num . '\' assignment.',
            'old_data' => json_encode($stackedData),
            'main_table' => 'assignments',
            'regional_office' => $user->regional_office,
            'field_office' => $user->field_office,
            'log_type' => 'remove',
            'log_timestamp' => $timestamp ?? now(),
        ]);
    }

    public static function set_delete_beneficiary(Implementation|Collection $implementation, Batch|Collection $batch, Beneficiary|Collection $beneficiary, User|Authenticatable $user, mixed $timestamp = null)
    {
        $stackedData = [
            'implementations' => $implementation->toArray(),
            'batches' => $batch->toArray(),
            'beneficiaries' => $beneficiary->toArray(),
        ];

        SystemsLog::create([
            'users_id' => $user->id,
            'description' => 'Deleted ' . self::full_name($beneficiary) . ' from Project \'' . $implementation->project_num . '\' -> Batch \'' . $batch->batch_num . '\'.',
            'old_data' => json_encode($stackedData),
            'main_table' => 'beneficiaries',
            'regional_office' => $user->regional_office,
            'field_office' => $user->field_office,
            'log_type' => 'delete',
            'log_timestamp' => $timestamp ?? now(),
        ]);
    }

    public static function set_delete_beneficiary_special_case(Implementation|Collection $implementation, Batch|Collection $batch, Beneficiary|Collection $beneficiary, Credential|Collection $credential, User|Authenticatable $user, mixed $timestamp = null)
    {
        $stackedData = [
            'implementations' => $implementation->toArray(),
            'batches' => $batch->toArray(),
            'beneficiaries' => $beneficiary->toArray(),
            'credentials' => $credential->toArray(),
        ];

        SystemsLog::create([
            'users_id' => $user->id,
            'description' => 'Deleted ' . self::full_name($beneficiary) . ', a special case (description: \'' . $credential->image_description . '\') from Project \'' . $implementation->project_num . '\' -> Batch \'' . $batch->batch_num . '\'.',
            'old_data' => json_encode($stackedData),
            'main_table' => 'beneficiaries',
            'regional_office' => $user->regional_office,
            'field_office' => $user->field_office,
            'log_type' => 'delete',
            'log_timestamp' => $timestamp ?? now(),
        ]);
    }

    public static function set_archive_beneficiary(Implementation|Collection $implementation, Batch|Collection $batch, Beneficiary|Collection $beneficiary, User|Authenticatable $user, mixed $timestamp = null)
    {
        SystemsLog::create([
            'users_id' => $user->id,
            'description' => 'Moved a beneficiary (' . self::full_name($beneficiary) . ') to Archives. Project \'' . $implementation->project_num . '\' -> Batch \'' . $batch->batch_num . '\'',
            'regional_office' => $user->regional_office,
            'field_office' => $user->field_office,
            'log_type' => 'archive',
            'log_timestamp' => $timestamp ?? now(),
        ]);
    }

    public static function set_archive_beneficiary_special_case(Implementation|Collection $implementation, Batch|Collection $batch, Beneficiary|Collection $beneficiary, Credential|Collection $credential, User|Authenticatable $user, mixed $timestamp = null)
    {
        SystemsLog::create([
            'users_id' => $user->id,
            'description' => 'Moved a beneficiary (' . self::full_name($beneficiary) . '), a special case (description: \'' . $credential->image_description . '\') to Archives. Project \'' . $implementation->project_num . '\' -> Batch \'' . $batch->batch_num . '\'',
            'regional_office' => $user->regional_office,
            'field_office' => $user->field_office,
            'log_type' => 'archive',
            'log_timestamp' => $timestamp ?? now(),
        ]);
    }

    public static function set_restore_archive(Implementation|Collection $implementation, Batch|Collection $batch, Archive|Collection $archive, User|Authenticatable $user, mixed $timestamp = null)
    {
        SystemsLog::create([
            'users_id' => $user->id,
            'description' => 'Restored ' . self::full_name($archive->data) . ' back from Archives. Project: \'' . $implementation->project_num . '\' -> Batch: \'' . $batch->batch_num . '\'',
            'regional_office' => $user->regional_office,
            'field_office' => $user->field_office,
            'log_type' => 'restore',
            'log_timestamp' => $timestamp ?? now(),
        ]);
    }

    public static function set_permanently_delete_archive_beneficiary(Implementation|Collection $implementation, Batch|Collection $batch, Archive|Collection $beneficiary, User|Authenticatable $user, mixed $timestamp = null)
    {
        $stackedData = [
            'implementations' => $implementation->toArray(),
            'batches' => $batch->toArray(),
            'beneficiaries' => $beneficiary->data,
        ];

        SystemsLog::create([
            'users_id' => $user->id,
            'description' => 'Permanently deleted ' . self::full_name($beneficiary->data) . '. Project: \'' . $implementation->project_num . '\' -> Batch: \'' . $batch->batch_num . '\'.',
            'old_data' => json_encode($stackedData),
            'main_table' => 'beneficiaries',
            'regional_office' => $user->regional_office,
            'field_office' => $user->field_office,
            'log_type' => 'delete',
            'log_timestamp' => $timestamp ?? now(),
        ]);
    }

    public static function set_permanently_delete_archive_beneficiary_special_case(Implementation|Collection $implementation, Batch|Collection $batch, Archive|Collection $beneficiary, Archive|Collection $credential, User|Authenticatable $user, mixed $timestamp = null)
    {
        $stackedData = [
            'implementations' => $implementation->toArray(),
            'batches' => $batch->toArray(),
            'credentials' => $credential->data,
            'beneficiaries' => $beneficiary->data,
        ];

        SystemsLog::create([
            'users_id' => $user->id,
            'description' => 'Permanently deleted ' . self::full_name($beneficiary->data) . ', a special case (description: ' . $credential->data['image_description'] . '). Project: \'' . $implementation->project_num . '\' -> Batch: \'' . $batch->batch_num . '\'.',
            'old_data' => json_encode($stackedData),
            'main_table' => 'beneficiaries',
            'regional_office' => $user->regional_office,
            'field_office' => $user->field_office,
            'log_type' => 'delete',
            'log_timestamp' => $timestamp ?? now(),
        ]);
    }

    # -------------------------------------------------------------------------------------------------------------------
    # Coordinators -------------------------------------------------------------------------------------------------------------------
    # -------------------------------------------------------------------------------------------------------------------

    public static function set_approve_batch(Batch|Collection $batch, User|Authenticatable $user, mixed $timestamp = null)
    {
        SystemsLog::create([
            'users_id' => $user->id,
            'description' => 'A batch \'' . $batch->batch_num . '\' has been approved.',
            'regional_office' => $user->regional_office,
            'field_office' => $user->field_office,
            'log_type' => 'update',
            'log_timestamp' => $timestamp ?? now(),
        ]);
    }

    public static function set_open_access(Batch|Collection $batch, Code|Collection $code, User|Authenticatable $user, mixed $timestamp = null)
    {
        SystemsLog::create([
            'users_id' => $user->id,
            'description' => 'Batch \' ' . $batch->batch_num . '\' has been opened for access.',
            'regional_office' => $user->regional_office,
            'field_office' => $user->field_office,
            'log_type' => 'update',
            'log_timestamp' => $timestamp ?? now(),
        ]);
    }

    public static function set_force_submit_batch(Batch|Collection $batch, User|Authenticatable $user, mixed $timestamp = null)
    {
        SystemsLog::create([
            'users_id' => $user->id,
            'description' => 'Batch \'' . $batch->batch_num . '\' has been submitted by force.',
            'regional_office' => $user->regional_office,
            'field_office' => $user->field_office,
            'log_type' => 'update',
            'log_timestamp' => $timestamp ?? now(),
        ]);
    }

    public static function set_revalidate_batch(Batch|Collection $batch, User|Authenticatable $user, mixed $timestamp = null)
    {
        SystemsLog::create([
            'users_id' => $user->id,
            'description' => 'Batch \'' . $batch->batch_num . '\' has been pushed to revalidation.',
            'regional_office' => $user->regional_office,
            'field_office' => $user->field_office,
            'log_type' => 'update',
            'log_timestamp' => $timestamp ?? now(),
        ]);
    }

    # -------------------------------------------------------------------------------------------------------------------
    # Coordinators (END) -------------------------------------------------------------------------------------------------------------------
    # -------------------------------------------------------------------------------------------------------------------

    # ----------------------------------------------------------------------------------------------------------------------
    # Barangays (START) ----------------------------------------------------------------------------------------------------------------------
    # ----------------------------------------------------------------------------------------------------------------------

    public static function set_barangay_add_beneficiary(Implementation|Collection $implementation, Batch|Collection $batch, Beneficiary|Collection $beneficiary, string $access_code, mixed $timestamp = null)
    {
        $user = self::get_user_based_on_beneficiary($beneficiary);

        SystemsLog::create([
            'users_id' => null,
            'alternative_sender' => "Code (" . decrypt($access_code) . ") User",
            'description' => 'Added ' . self::full_name($beneficiary) . ' in batch ' . $batch->batch_num . ' -> implementation project ' . $implementation->project_num . '.',
            'regional_office' => $user->regional_office,
            'field_office' => $user->field_office,
            'log_type' => 'create',
            'log_timestamp' => $timestamp ?? now(),
        ]);
    }

    public static function set_barangay_added_special_case(Implementation|Collection $implementation, Batch|Collection $batch, Beneficiary|Collection $beneficiary, string $access_code, mixed $timestamp = null)
    {
        $user = self::get_user_based_on_beneficiary($beneficiary);

        SystemsLog::create([
            'users_id' => null,
            'alternative_sender' => "Code (" . decrypt($access_code) . ") User",
            'description' => 'Added ' . self::full_name($beneficiary) . ' as a special case beneficiary in batch ' . $batch->batch_num . ' -> implementation project ' . $implementation->project_num . '.',
            'regional_office' => $user->regional_office,
            'field_office' => $user->field_office,
            'log_type' => 'create',
            'log_timestamp' => $timestamp ?? now(),
        ]);
    }

    public static function set_barangay_edit_beneficiary(Implementation|Collection $implementation, Batch|Collection $batch, Beneficiary|Collection $beneficiary, string $access_code, mixed $timestamp = null)
    {
        $user = self::get_user_based_on_beneficiary($beneficiary);

        SystemsLog::create([
            'users_id' => null,
            'alternative_sender' => "Code (" . decrypt($access_code) . ") User",
            'description' => 'Modified ' . self::full_name($beneficiary) . ' in batch ' . $batch->batch_num . ' -> implementation project ' . $implementation->project_num . '.',
            'regional_office' => $user->regional_office,
            'field_office' => $user->field_office,
            'log_type' => 'create',
            'log_timestamp' => $timestamp ?? now(),
        ]);
    }

    public static function set_barangay_edit_beneficiary_special_case(Implementation|Collection $implementation, Batch|Collection $batch, Beneficiary|Collection $beneficiary, Credential|Collection $credential, string $access_code, mixed $timestamp = null)
    {
        $user = self::get_user_based_on_beneficiary($beneficiary);

        SystemsLog::create([
            'users_id' => null,
            'alternative_sender' => "Code (" . decrypt($access_code) . ") User",
            'description' => 'Modified a special case (description: \'' . $credential->image_description . '\') from a beneficiary (' . self::full_name($beneficiary) . ') in batch ' . $batch->batch_num . ' -> implementation project ' . $implementation->project_num . '.',
            'regional_office' => $user->regional_office,
            'field_office' => $user->field_office,
            'log_type' => 'update',
            'log_timestamp' => $timestamp ?? now(),
        ]);
    }

    public static function set_barangay_remove_beneficiary_special_case(Implementation|Collection $implementation, Batch|Collection $batch, Beneficiary|Collection $beneficiary, Credential|Collection $credential, string $access_code, mixed $timestamp = null)
    {
        $stackedData = [
            'beneficiaries' => $beneficiary->toArray(),
            'credentials' => $credential->toArray(),
            'codes' => ['access_code' => $access_code],
        ];

        $user = self::get_user_based_on_beneficiary($beneficiary);

        SystemsLog::create([
            'users_id' => null,
            'alternative_sender' => "Code (" . decrypt($access_code) . ") User",
            'description' => 'Deleted a special case (description: \'' . $credential->image_description . '\') from a beneficiary (' . self::full_name($beneficiary) . ') due to modifying the beneficiary\'s information. Batch \'' . $batch->batch_num . '\' -> Project \'' . $implementation->project_num . '\'.',
            'old_data' => json_encode($stackedData),
            'main_table' => 'credentials-barangay',
            'regional_office' => $user->regional_office,
            'field_office' => $user->field_office,
            'log_type' => 'delete',
            'log_timestamp' => $timestamp ?? now(),
        ]);
    }

    public static function set_barangay_edit_beneficiary_identity(Implementation|Collection $implementation, Batch|Collection $batch, Beneficiary|Collection $beneficiary, string $access_code, mixed $timestamp = null)
    {
        $user = self::get_user_based_on_beneficiary($beneficiary);

        SystemsLog::create([
            'users_id' => null,
            'alternative_sender' => "Code (" . decrypt($access_code) . ") User",
            'description' => 'Modified proof of identity (ID Picture) from a beneficiary (' . self::full_name($beneficiary) . ') in batch \'' . $batch->batch_num . '\' -> implementation project \'' . $implementation->project_num . '\'.',
            'regional_office' => $user->regional_office,
            'field_office' => $user->field_office,
            'log_type' => 'update',
            'log_timestamp' => $timestamp ?? now(),
        ]);
    }

    public static function set_barangay_submit(Implementation|Collection $implementation, Batch|Collection $batch, int $added_count, int $slots_allocated, int $special_cases, string $access_code, mixed $timestamp = null)
    {
        $user = self::get_user_based_on_implementation($implementation);

        SystemsLog::create([
            'users_id' => null,
            'alternative_sender' => "Code (" . decrypt($access_code) . ") User",
            'description' => 'Submitted a list of (' . $added_count . ') / (' . $slots_allocated . ') beneficiaries with (' . $special_cases . ') special cases in project \'' . $implementation->project_num . '\' -> batch \'' . $batch->batch_num . '\'.',
            'regional_office' => $user->regional_office,
            'field_office' => $user->field_office,
            'log_type' => 'update',
            'log_timestamp' => $timestamp ?? now(),
        ]);
    }

    public static function set_barangay_archive_beneficiary(Implementation|Collection $implementation, Batch|Collection $batch, Beneficiary|Collection $beneficiary, string $access_code, mixed $timestamp = null)
    {
        $stackedData = [
            'implementations' => $implementation->toArray(),
            'batches' => $batch->toArray(),
            'beneficiaries' => $beneficiary->toArray(),
            'codes' => ['access_code' => $access_code],
        ];

        $user = self::get_user_based_on_beneficiary($beneficiary);

        SystemsLog::create([
            'users_id' => null,
            'alternative_sender' => "Code (" . decrypt($access_code) . ") User",
            'description' => 'Archived ' . self::full_name($beneficiary) . ' from batch ' . $batch->batch_num . ' -> project ' . $implementation->project_num . '.',
            'old_data' => json_encode($stackedData),
            'regional_office' => $user->regional_office,
            'field_office' => $user->field_office,
            'log_type' => 'archive',
            'log_timestamp' => $timestamp ?? now(),
        ]);
    }

    public static function set_barangay_archive_beneficiary_special_case(Implementation|Collection $implementation, Batch|Collection $batch, Beneficiary|Collection $beneficiary, Credential|Collection $credential, string $access_code, mixed $timestamp = null)
    {
        $stackedData = [
            'implementations' => $implementation->toArray(),
            'batches' => $batch->toArray(),
            'beneficiaries' => $beneficiary->toArray(),
            'codes' => ['access_code' => $access_code],
        ];

        $user = self::get_user_based_on_beneficiary($beneficiary);

        SystemsLog::create([
            'users_id' => null,
            'alternative_sender' => "Code (" . decrypt($access_code) . ") User",
            'description' => 'Archived ' . self::full_name($beneficiary) . ' from batch ' . $batch->batch_num . ' -> project ' . $implementation->project_num . '.',
            'old_data' => json_encode($stackedData),
            'regional_office' => $user->regional_office,
            'field_office' => $user->field_office,
            'log_type' => 'archive',
            'log_timestamp' => $timestamp ?? now(),
        ]);
    }

    # ----------------------------------------------------------------------------------------------------------------------
    # Barangays (END) ----------------------------------------------------------------------------------------------------------------------
    # ----------------------------------------------------------------------------------------------------------------------

    # Miscellaneous ----------------------------------------------------------------------------------------------------

    public static function set_log_exception(string $message, User|Authenticatable|int $user, array $trace, mixed $timestamp = null)
    {
        if (is_integer($user)) {
            $user = User::find($user);
        }

        SystemsLog::create([
            'users_id' => $user->id,
            'alternative_sender' => self::full_name($user) ?? request()->ip(),
            'description' => $message,
            'old_data' => json_encode($trace),
            'regional_office' => $user->regional_office,
            'field_office' => $user->field_office,
            'log_type' => 'error',
            'log_timestamp' => $timestamp ?? now(),
        ]);
    }

    public static function set_log_barangay_exception(string $message, string|null $access_code, array $trace, array $location, mixed $timestamp = null)
    {
        SystemsLog::create([
            'users_id' => null,
            'alternative_sender' => $access_code ? 'Code (' . decrypt($access_code) . ') User' : request()->ip(),
            'description' => $message,
            'old_data' => json_encode($trace),
            'regional_office' => $location['regional_office'],
            'field_office' => $location['field_office'],
            'log_type' => 'error',
            'log_timestamp' => $timestamp ?? now(),
        ]);
    }

    protected static function get_user_based_on_beneficiary(Beneficiary|Collection $beneficiary)
    {
        return User::find(Assignment::where('batches_id', Batch::find($beneficiary->batches_id, 'id')->id)->first('users_id')->users_id);
    }

    protected static function get_user_based_on_implementation(Implementation|Collection $implementation)
    {
        return User::find(Implementation::find($implementation->id, 'users_id')->users_id);
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