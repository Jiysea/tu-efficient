<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\Batch;
use App\Models\Beneficiary;
use App\Models\Code;
use App\Models\Credential;
use App\Models\Implementation;
use App\Models\User;
use App\Models\UserSetting;
use App\Services\JaccardSimilarity;
use App\Services\LogIt;
use Carbon\Carbon;
use File;
use Illuminate\Database\Seeder;
use Str;

class DatabaseSeeder extends Seeder
{
    protected $implementationAmount = 71;
    // protected $coordinatorsAmount = 10;
    protected $assignmentAmountMin = 2;
    protected $assignmentAmountMax = 5;
    protected $batchAmountMin = 2;
    protected $batchAmountMax = 6;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $startDate = Carbon::createFromDate(2024, 1, 1);
        $email_verified_at = $startDate->addMinutes(11)->addSeconds(mt_rand(1, 59));
        $mobile_verified_at = $email_verified_at->addMinutes(mt_rand(20, 59))->addSeconds(mt_rand(0, 59));

        $focalUser = User::factory()->create([
            'first_name' => 'TU-EFFICIENT',
            'middle_name' => null,
            'last_name' => 'ADMIN',
            'extension_name' => null,
            'email' => 'tuefficient@gmail.com',
            'contact_num' => '+639774547579',
            'user_type' => 'focal',
            'email_verified_at' => $email_verified_at,
            'mobile_verified_at' => $mobile_verified_at,
            'created_at' => $startDate,
            'updated_at' => $startDate,
        ]);

        LogIt::set_register_user(user: $focalUser, sender: 'System', log_type: 'initialize', timestamp: $startDate);

        $settingsFocal = [
            'minimum_wage' => config('settings.minimum_wage'),
            'duplication_threshold' => config('settings.duplication_threshold'),
            'project_number_prefix' => config('settings.project_number_prefix'),
            'batch_number_prefix' => config('settings.batch_number_prefix'),
            'senior_age_threshold' => config('settings.senior_age_threshold'),
            'maximum_income' => config('settings.maximum_income'),
            'default_archive' => config('settings.default_archive'),
            'default_show_duplicates' => config('settings.default_show_duplicates'),
        ];

        foreach ($settingsFocal as $key => $setting) {
            $initSetting = UserSetting::factory()->create([
                'users_id' => $focalUser->id,
                'key' => $key,
                'value' => $setting,
                'created_at' => $startDate,
                'updated_at' => $startDate,
            ]);

            LogIt::set_initialization_of_user_settings($initSetting, 'System', $focalUser->regional_office, $focalUser->field_office, $startDate);
        }

        $startDate = $mobile_verified_at;
        self::initCoordinators($startDate, $focalUser);
        $coordinatorUsers = User::where('user_type', 'coordinator')->get();

        // # ----------------------------------------------------------------------
        // # Uncomment and use this only when adding additional settings
        // # also remove all existing settings if you're adding new settings
        // # so that it won't overwrite the settings in production
        // // $focalUser = User::where('user_type', 'focal')->get();
        // // $coordinatorUsers = User::where('user_type', 'coordinator')->get();
        // # ----------------------------------------------------------------------

        $project_title = ucwords('implementation number');
        $implementations = Implementation::factory($this->implementationAmount)->create(
            [
                'users_id' => $focalUser->id,
            ]
        );

        # Implementations
        foreach ($implementations as $key => $implementation) {
            Implementation::withoutTimestamps(function () use ($implementation, $project_title, $key) {
                $implementation->project_title = $project_title . ' ' . $key + 1;
                $implementation->save();
            });
            LogIt::set_create_project($implementation, $focalUser, $implementation->created_at);

            $allottedSlots = $this->generateRandomArray($implementation->total_slots);
            $currentDate = null;
            # Batches
            foreach ($allottedSlots as $slots) {
                $currentDate = Carbon::parse($implementation->created_at)->addMinutes(mt_rand(3, 10))->addSeconds(mt_rand(0, 59));
                $district = fake()->randomElement(['1st District', '2nd District', '3rd District',]);

                $batch = Batch::factory()->create([
                    'implementations_id' => $implementation->id,
                    'batch_num' => $this->batchNumberGenerator(Carbon::parse($currentDate)->format('Y-')),
                    'sector_title' => null,
                    'district' => null,
                    'barangay_name' => null,
                    'slots_allocated' => $slots,
                    'created_at' => $currentDate,
                    'updated_at' => $currentDate,
                ]);

                LogIt::set_create_batches($implementation, $batch, $focalUser, $currentDate);

                Batch::withoutTimestamps(function () use ($implementation, $batch, $district) {
                    if ($batch->is_sectoral) {
                        $batch->sector_title = $this->generateSectorTitle();
                    } elseif (!$batch->is_sectoral) {
                        $batch->district = $district;
                        $batch->barangay_name = $this->getBarangayName($implementation->id, $district);
                    }
                    $batch->save();
                });

                $amount = mt_rand($this->assignmentAmountMin, $this->assignmentAmountMax);
                $coordinators = $coordinatorUsers->random($amount);
                $currentDate = Carbon::parse($batch->created_at)->addMinutes(mt_rand(0, 2))->addSeconds(mt_rand(5, 59));

                $coordinators->each(function ($user) use ($batch, $currentDate, $focalUser) {
                    $assignment = Assignment::factory()->create([
                        'batches_id' => $batch->id,
                        'users_id' => $user->id,
                        'created_at' => $currentDate,
                        'updated_at' => $currentDate,
                    ]);

                    LogIt::set_assign_coordinator_to_batch($batch, $assignment, $focalUser, 'create', $currentDate);
                });

                $currentDate = $currentDate->addMinutes(mt_rand(1, 30))->addSeconds(mt_rand(0, 59));
                $code = Code::factory()->create(
                    [
                        'batches_id' => $batch->id,
                        'created_at' => $currentDate,
                        'updated_at' => $currentDate,
                    ]
                );
                $coordinators = User::whereHas('assignment', function ($q) use ($batch) {
                    $q->where('batches_id', $batch->id);
                })->get();

                $currentDate = $currentDate->addMinutes(mt_rand(1, 30))->addSeconds(mt_rand(0, 59));
                $randomCoordinator = $coordinators->random();
                LogIt::set_open_access($batch, $code, $randomCoordinator, $currentDate->addMinutes(mt_rand(1, 30))->addSeconds(mt_rand(0, 59)));

                $currentDate = $currentDate->addMinutes(mt_rand(1, 30))->addSeconds(mt_rand(0, 59));
                $beneficiaries = Beneficiary::factory($batch->slots_allocated)->create([
                    'batches_id' => $batch->id,
                    'district' => $batch->district ?? '.',
                    'barangay_name' => $batch->barangay_name ?? '.',
                    'created_at' => $currentDate,
                    'updated_at' => $currentDate,
                ]);

                $specialCases = 0;

                # Beneficiaries
                foreach ($beneficiaries as $beneficiary) {
                    if ($beneficiary->district === '.') {
                        $individual_district = fake()->randomElement(['1st District', '2nd District', '3rd District',]);
                        $beneficiary->district = $individual_district;
                        $beneficiary->barangay_name = $this->getBarangayName($implementation->id, $individual_district);
                    }

                    if ($batch->is_sectoral === 1 && mb_strtolower(substr($batch->sector_title, 0, 10), "UTF-8") === 'occupation') {
                        $beneficiary->occupation = substr($batch->sector_title, 11);
                    }

                    $beneficiary->save();

                    # check its Jaccard Similarity index
                    $beneficiariesInDatabase = $this->prefetchNames($beneficiary, $this->getFullName($beneficiary), $beneficiary->middle_name);
                    $joiningFrequency = 1;

                    # this is where it checks the similarities
                    foreach ($beneficiariesInDatabase as $key => $existing) {

                        # gets the full name of the beneficiary
                        $existingPerson = $this->getFullName2($existing, $beneficiary->middle_name);
                        $currentPerson = $this->getFullName2($beneficiary, $beneficiary->middle_name);

                        # gets the co-efficient/jaccard index of the 2 names (without birthdate by default)
                        $coEfficient = JaccardSimilarity::calculateSimilarity($existingPerson, $currentPerson);

                        # check if it's a perfect duplicate
                        if (intval($coEfficient * 100) === 100) {

                            # if the exact same person joined more than 2 times...
                            if ($joiningFrequency > 1) {
                                Beneficiary::withoutTimestamps(function () use ($beneficiary) {
                                    $first_name = $this->getFirstName($beneficiary->sex);
                                    $middle_name = $this->getMiddleName();
                                    $last_name = $this->getLastName();
                                    $extension_name = $this->getSuffix();
                                    $s_first_name = $this->checkSpouse($beneficiary->civil_status, $beneficiary->sex, 'first');
                                    $s_middle_name = $this->checkSpouse($beneficiary->civil_status, $beneficiary->sex, 'middle');
                                    $s_last_name = $this->checkSpouse($beneficiary->civil_status, $beneficiary->sex, 'last', $last_name);
                                    $s_extension_name = $this->checkSpouse($beneficiary->civil_status, $beneficiary->sex, 'ext');

                                    $beneficiary->first_name = $first_name;
                                    $beneficiary->middle_name = $middle_name;
                                    $beneficiary->last_name = $last_name;
                                    $beneficiary->extension_name = $extension_name;
                                    $beneficiary->spouse_first_name = $s_first_name;
                                    $beneficiary->spouse_middle_name = $s_middle_name;
                                    $beneficiary->spouse_last_name = $s_last_name;
                                    $beneficiary->spouse_extension_name = $s_extension_name;

                                    if (is_null($beneficiary->barangay_name)) {
                                        $barangay_name = $this->getBarangaysByDistrict($beneficiary->district);
                                        $beneficiary->barangay_name = $barangay_name;
                                    }
                                    $beneficiary->save();
                                });
                            }

                            # otherwise...
                            else {
                                # increment its joining frequency
                                $joiningFrequency++;

                                Beneficiary::withoutTimestamps(function () use ($beneficiary) {
                                    $beneficiary->beneficiary_type = 'special case';
                                    $beneficiary->save();
                                });
                                # then add the reason for joining
                                Credential::factory()->create([
                                    'beneficiaries_id' => $beneficiary->id,
                                    'image_file_path' => self::getImageByIdType('Special Case'),
                                    'image_description' => 'This beneficiary is a calamity victim.',
                                    'for_duplicates' => 'yes',
                                    'created_at' => $currentDate,
                                    'updated_at' => $currentDate,
                                ]);
                                $specialCases++;
                            }
                        }
                    }

                    Credential::factory()->create([
                        'beneficiaries_id' => $beneficiary->id,
                        'image_file_path' => self::getImageByIdType($beneficiary->type_of_id),
                        'created_at' => $currentDate,
                        'updated_at' => $currentDate,
                    ]);
                }

                if ($specialCases > 0) {
                    LogIt::set_import_with_special_cases($implementation, $batch, $randomCoordinator, $batch->slots_allocated, $specialCases, $currentDate);
                } else {
                    LogIt::set_import_success($implementation, $batch, $randomCoordinator, $batch->slots_allocated, $currentDate);
                }

                # Then force submit the batch
                Batch::withoutTimestamps(function () use ($batch) {
                    $batch->submission_status = 'submitted';
                    $batch->approval_status = 'approved';
                    $batch->save();
                });

                $currentDate = $currentDate->addMinutes(mt_rand(1, 30))->addSeconds(mt_rand(0, 59));
                LogIt::set_force_submit_batch($batch, $randomCoordinator, $currentDate);
                $currentDate = $currentDate->addMinutes(mt_rand(1, 30))->addSeconds(mt_rand(0, 59));
                LogIt::set_approve_batch($batch, $randomCoordinator, $currentDate);
            }

            # Change status to `implementing`
            $currentDate = $currentDate->addMinutes(mt_rand(1, 30))->addSeconds(mt_rand(0, 59));
            Implementation::withoutTimestamps(function () use ($implementation, $currentDate) {
                $implementation->status = 'implementing';
                $implementation->save();
            });

            LogIt::set_mark_project_for_implementation($implementation, $focalUser, $currentDate);

            # Mark some beneficiaries for COS and Payroll
            $batches = Batch::with('beneficiary')
                ->where('implementations_id', $implementation->id)->get();

            Batch::withoutTimestamps(function () use ($implementation, $batches, $focalUser, $currentDate) {
                foreach ($batches as $batch) {
                    $currentDate = $currentDate->addMinutes(mt_rand(1, 30))->addSeconds(mt_rand(0, 59));
                    $beneficiaries = Beneficiary::where('batches_id', $batch->id)->get();
                    Beneficiary::withoutTimestamps(function () use ($implementation, $batch, $beneficiaries, $focalUser, $currentDate) {
                        $marked_cos = 0;
                        $marked_payroll = 0;
                        foreach ($beneficiaries as $beneficiary) {
                            if (mt_rand(0, 100) < 95) {
                                $beneficiary->is_signed = 1;
                                $marked_cos++;
                            }

                            if ($beneficiary->is_signed && mt_rand(0, 100) < 95) {
                                $beneficiary->is_paid = 1;
                                $marked_payroll++;
                            }

                            $beneficiary->save();
                        }
                        $batch->save();
                        LogIt::set_check_beneficiaries_for_cos($implementation, $batch, $focalUser, $marked_cos, $currentDate);
                        LogIt::set_check_beneficiaries_for_payroll($implementation, $batch, $focalUser, $marked_payroll, $currentDate);
                    });
                }
            });

            # Then `conclude` the project
            $currentDate = $currentDate->addMinutes(mt_rand(1, 30))->addSeconds(mt_rand(0, 59));
            Implementation::withoutTimestamps(function () use ($implementation, $currentDate) {
                $implementation->status = 'concluded';
                $implementation->save();
            });

            LogIt::set_mark_project_as_concluded($implementation, $focalUser, $currentDate);
        }
    }

    protected static function initCoordinators($startDate, $focalUser)
    {

        # ----------------------------------------------------------------------

        $user = User::factory()->create(
            [
                'first_name' => 'ALLEN CLARC',
                'middle_name' => 'TROCIO',
                'last_name' => 'SALONGA',
                'extension_name' => null,
                'email' => 'allenclarcsalonga@gmail.com',
                'contact_num' => '+639582122891',
                'email_verified_at' => $startDate->addMinutes(mt_rand(5, 10))->addSeconds(mt_rand(1, 59)),
                'mobile_verified_at' => $startDate->addMinutes(15)->addSeconds(mt_rand(1, 59)),
                'created_at' => $startDate,
                'updated_at' => $startDate,
            ],
        );

        LogIt::set_register_user($user, $focalUser->id, timestamp: $user->created_at);

        $settingsCoordinator = [
            'duplication_threshold' => config('settings.duplication_threshold'),
            'default_show_duplicates' => config('settings.default_show_duplicates'),
        ];

        foreach ($settingsCoordinator as $key => $setting) {
            $initSetting = UserSetting::factory()->create([
                'users_id' => $user->id,
                'key' => $key,
                'value' => $setting,
                'created_at' => $user->created_at,
                'updated_at' => $user->created_at,
            ]);

            LogIt::set_initialization_of_user_settings($initSetting, 'System', $user->regional_office, $user->field_office, $user->created_at);
        }

        # ----------------------------------------------------------------------

        $user = User::factory()->create(
            [
                'first_name' => 'JERECHO',
                'middle_name' => 'PASCUAL',
                'last_name' => 'SUICO',
                'extension_name' => null,
                'email' => 'echo.suico09@gmail.com',
                'contact_num' => '+639993738648',
                'email_verified_at' => $startDate->addMinutes(mt_rand(5, 10))->addSeconds(mt_rand(1, 59)),
                'mobile_verified_at' => $startDate->addMinutes(15)->addSeconds(mt_rand(1, 59)),
                'created_at' => $startDate,
                'updated_at' => $startDate,
            ],
        );

        LogIt::set_register_user($user, $focalUser->id, timestamp: $user->created_at);

        $settingsCoordinator = [
            'duplication_threshold' => config('settings.duplication_threshold'),
            'default_show_duplicates' => config('settings.default_show_duplicates'),
        ];

        foreach ($settingsCoordinator as $key => $setting) {
            $initSetting = UserSetting::factory()->create([
                'users_id' => $user->id,
                'key' => $key,
                'value' => $setting,
                'created_at' => $user->created_at,
                'updated_at' => $user->created_at,
            ]);

            LogIt::set_initialization_of_user_settings($initSetting, 'System', $user->regional_office, $user->field_office, $user->created_at);
        }

        $user = User::factory()->create(
            [
                'first_name' => 'LEONILO III',
                'middle_name' => 'CARMEN',
                'last_name' => 'GONZALEZ',
                'extension_name' => null,
                'email' => 'l.gonzalez.476028@umindanao.edu.ph',
                'contact_num' => '+639151733029',
                'email_verified_at' => $startDate->addMinutes(mt_rand(5, 10))->addSeconds(mt_rand(1, 59)),
                'mobile_verified_at' => $startDate->addMinutes(15)->addSeconds(mt_rand(1, 59)),
                'created_at' => $startDate,
                'updated_at' => $startDate,
            ],
        );

        LogIt::set_register_user($user, $focalUser->id, timestamp: $user->created_at);

        $settingsCoordinator = [
            'duplication_threshold' => config('settings.duplication_threshold'),
            'default_show_duplicates' => config('settings.default_show_duplicates'),
        ];

        foreach ($settingsCoordinator as $key => $setting) {
            $initSetting = UserSetting::factory()->create([
                'users_id' => $user->id,
                'key' => $key,
                'value' => $setting,
                'created_at' => $user->created_at,
                'updated_at' => $user->created_at,
            ]);

            LogIt::set_initialization_of_user_settings($initSetting, 'System', $user->regional_office, $user->field_office, $user->created_at);
        }

        $user = User::factory()->create(
            [
                'first_name' => 'AIMEE',
                'middle_name' => 'LINCOLN',
                'last_name' => 'ZACKWIERTZ',
                'extension_name' => null,
                'email' => 'aimeezackwiertz@gmail.com',
                'contact_num' => '+639214819345',
                'email_verified_at' => $startDate->addMinutes(mt_rand(5, 10))->addSeconds(mt_rand(1, 59)),
                'mobile_verified_at' => $startDate->addMinutes(15)->addSeconds(mt_rand(1, 59)),
                'created_at' => $startDate,
                'updated_at' => $startDate,
            ],
        );

        LogIt::set_register_user($user, $focalUser->id, timestamp: $user->created_at);

        $settingsCoordinator = [
            'duplication_threshold' => config('settings.duplication_threshold'),
            'default_show_duplicates' => config('settings.default_show_duplicates'),
        ];

        foreach ($settingsCoordinator as $key => $setting) {
            $initSetting = UserSetting::factory()->create([
                'users_id' => $user->id,
                'key' => $key,
                'value' => $setting,
                'created_at' => $user->created_at,
                'updated_at' => $user->created_at,
            ]);

            LogIt::set_initialization_of_user_settings($initSetting, 'System', $user->regional_office, $user->field_office, $user->created_at);
        }

        $user = User::factory()->create(
            [
                'first_name' => 'RYAN',
                'middle_name' => 'CONNOR',
                'last_name' => 'GOSLING',
                'extension_name' => null,
                'email' => 'ryangoslingijustdrive@gmail.com',
                'contact_num' => '+639856632179',
                'email_verified_at' => $startDate->addMinutes(mt_rand(5, 10))->addSeconds(mt_rand(1, 59)),
                'mobile_verified_at' => $startDate->addMinutes(15)->addSeconds(mt_rand(1, 59)),
                'created_at' => $startDate,
                'updated_at' => $startDate,
            ],
        );

        LogIt::set_register_user($user, $focalUser->id, timestamp: $user->created_at);

        $settingsCoordinator = [
            'duplication_threshold' => config('settings.duplication_threshold'),
            'default_show_duplicates' => config('settings.default_show_duplicates'),
        ];

        foreach ($settingsCoordinator as $key => $setting) {
            $initSetting = UserSetting::factory()->create([
                'users_id' => $user->id,
                'key' => $key,
                'value' => $setting,
                'created_at' => $user->created_at,
                'updated_at' => $user->created_at,
            ]);

            LogIt::set_initialization_of_user_settings($initSetting, 'System', $user->regional_office, $user->field_office, $user->created_at);
        }

        $user = User::factory()->create(
            [
                'first_name' => 'HANNAH',
                'middle_name' => 'LORREN',
                'last_name' => 'ENHANCER',
                'extension_name' => null,
                'email' => 'hannatherenhancer@gmail.com',
                'contact_num' => '+639497723461',
                'email_verified_at' => $startDate->addMinutes(mt_rand(5, 10))->addSeconds(mt_rand(1, 59)),
                'mobile_verified_at' => $startDate->addMinutes(15)->addSeconds(mt_rand(1, 59)),
                'created_at' => $startDate,
                'updated_at' => $startDate,
            ],
        );

        LogIt::set_register_user($user, $focalUser->id, timestamp: $user->created_at);

        $settingsCoordinator = [
            'duplication_threshold' => config('settings.duplication_threshold'),
            'default_show_duplicates' => config('settings.default_show_duplicates'),
        ];

        foreach ($settingsCoordinator as $key => $setting) {
            $initSetting = UserSetting::factory()->create([
                'users_id' => $user->id,
                'key' => $key,
                'value' => $setting,
                'created_at' => $user->created_at,
                'updated_at' => $user->created_at,
            ]);
            LogIt::set_initialization_of_user_settings($initSetting, 'System', $user->regional_office, $user->field_office, $user->created_at);
        }

        $user = User::factory()->create(
            [
                'first_name' => 'JACKIE SANS',
                'middle_name' => 'TUMBALILONG',
                'last_name' => 'LIMEN',
                'extension_name' => 'JR.',
                'email' => 'jackiesanslimen@gmail.com',
                'contact_num' => '+639654472897',
                'email_verified_at' => $startDate->addMinutes(mt_rand(5, 10))->addSeconds(mt_rand(1, 59)),
                'mobile_verified_at' => $startDate->addMinutes(15)->addSeconds(mt_rand(1, 59)),
                'created_at' => $startDate,
                'updated_at' => $startDate,
            ],
        );

        LogIt::set_register_user($user, $focalUser->id, timestamp: $user->created_at);

        $settingsCoordinator = [
            'duplication_threshold' => config('settings.duplication_threshold'),
            'default_show_duplicates' => config('settings.default_show_duplicates'),
        ];

        foreach ($settingsCoordinator as $key => $setting) {
            $initSetting = UserSetting::factory()->create([
                'users_id' => $user->id,
                'key' => $key,
                'value' => $setting,
                'created_at' => $user->created_at,
                'updated_at' => $user->created_at,
            ]);

            LogIt::set_initialization_of_user_settings($initSetting, 'System', $user->regional_office, $user->field_office, $user->created_at);
        }

    }

    protected static function getImageByIdType($userIdType)
    {
        # Map user ID types to image prefixes
        $prefixMap = [
            'Barangay ID' => 'b',
            'e-Card / UMID' => 'u',
            "Driver's License" => 'd',
            'Phil-health ID' => 'ph',
            'Philippine Identification (PhilID / ePhilID)' => 'pi',
            'Special Case' => 's'
            # Add other ID types and their prefixes as needed
        ];

        # Get the prefix based on the user ID type
        $prefix = $prefixMap[$userIdType] ?? null;

        if (!$prefix) {
            throw new \Exception('Invalid user ID type.');
        }

        # Define the public directory path
        $publicPath = public_path('sample_images');

        # Get all files starting with the prefix
        $files = array_filter(File::files($publicPath), function ($file) use ($prefix) {
            return str_starts_with($file->getFilename(), $prefix);
        });

        if (empty($files)) {
            throw new \Exception('No images found for the specified group.');
        }

        # Randomly select a file from the group
        $randomFile = collect($files)->random();

        # Generate a hashed filename
        $hashedFilename = Str::random(40) . '.' . $randomFile->getExtension();

        # Copy the file to the storage directory
        $destinationPath = 'credentials/' . $hashedFilename;
        $sourcePath = $randomFile->getPathname();

        # Ensure the storage directory exists
        $storageDirectory = storage_path('app/' . dirname($destinationPath));
        if (!File::exists($storageDirectory)) {
            File::makeDirectory($storageDirectory, 0755, true);
        }

        # Copy the file
        File::copy($sourcePath, storage_path('app/' . $destinationPath));

        # Return the file path
        return $destinationPath;
    }

    protected function refreshBeneficiary($beneficiary)
    {
        Beneficiary::withoutTimestamps(function () use ($beneficiary) {
            $first_name = $this->getFirstName($beneficiary->sex);
            $middle_name = $this->getMiddleName();
            $last_name = $this->getLastName();
            $extension_name = $this->getSuffix();
            $s_first_name = $this->checkSpouse($beneficiary->civil_status, $beneficiary->sex, 'first');
            $s_middle_name = $this->checkSpouse($beneficiary->civil_status, $beneficiary->sex, 'middle');
            $s_last_name = $this->checkSpouse($beneficiary->civil_status, $beneficiary->sex, 'last', $last_name);
            $s_extension_name = $this->checkSpouse($beneficiary->civil_status, $beneficiary->sex, 'ext');

            $beneficiary->first_name = $first_name;
            $beneficiary->middle_name = $middle_name;
            $beneficiary->last_name = $last_name;
            $beneficiary->extension_name = $extension_name;
            $beneficiary->spouse_first_name = $s_first_name;
            $beneficiary->spouse_middle_name = $s_middle_name;
            $beneficiary->spouse_last_name = $s_last_name;
            $beneficiary->spouse_extension_name = $s_extension_name;
            $beneficiary->save();
        });
    }

    protected function prefetchNames($beneficiary, string $filteredInputString, string|null $middle_name)
    {
        # only take beneficiaries from the start of the year until today
        $startDate = now()->startOfYear();
        $endDate = now();

        # separate each word from all the name fields
        # and get the first letter of each word
        $namesToLetters = array_map(fn($word) => $word[0], explode(' ', $filteredInputString));

        $beneficiariesFromDatabase = Beneficiary::join('batches', 'beneficiaries.batches_id', '=', 'batches.id')
            ->join('implementations', 'batches.implementations_id', '=', 'implementations.id')
            ->whereBetween('implementations.created_at', [$startDate, $endDate])
            ->whereNotIn('beneficiaries.id', [$beneficiary->id])
            ->where(function ($query) use ($namesToLetters) {
                foreach ($namesToLetters as $letter) {
                    $query->orWhere('beneficiaries.first_name', 'LIKE', $letter . '%');
                }
            })
            ->where(function ($q) use ($namesToLetters, $middle_name) {

                $q->when($middle_name, function ($q) use ($namesToLetters) {
                    foreach ($namesToLetters as $letter) {
                        $q->orWhere('beneficiaries.middle_name', 'LIKE', $letter . '%');
                    }
                });

                foreach ($namesToLetters as $letter) {
                    $q->orWhere('beneficiaries.last_name', 'LIKE', $letter . '%');
                }
            })
            ->select([
                'beneficiaries.*'
            ])
            ->get();


        return $beneficiariesFromDatabase;
    }

    function generateSectorTitle()
    {
        $type_of_sector = fake()->randomElement(['occupation', 'assistance']);

        if ($type_of_sector === 'occupation') {
            return 'Occupation:' . fake()->randomElement([
                'Fisherman',
                'Street Vendor',
                'Plumber',
                'Warehouse Worker',
                'Construction Worker',
                'Driver',
                'Cook',
                'Street Sweeper',
                'Farmer',
                'Janitor',
                'Welder',
                'Gardener'
            ]);
        } elseif ($type_of_sector === 'assistance') {
            return fake()->randomElement([
                'School Related',
                'Community Program',
                'Calamity Assistance',
            ]);
        }
    }

    function batchNumberGenerator(string $year)
    {
        $prefix = config('settings.batch_number_prefix', 'DCFO-BN-');
        $number = $prefix . $year . fake()->bothify('########');
        $existingNumber = Batch::where('batch_num', $number)->first();

        while ($existingNumber) {
            $number = $prefix . $year . fake()->bothify('########');
            $existingNumber = Batch::where('batch_num', $number)->first(); // Check if the new number exists
        }

        return $number;
    }

    function getFullName2($person, $middle_name)
    {
        $name = $person->first_name;

        if ($middle_name) {
            $name .= ' ' . $person->middle_name;
        }

        $name .= ' ' . $person->last_name;

        if ($person->extension_name) {
            $name .= ' ' . $person->extension_name;
        }
        return $name;
    }

    function getFullName($person)
    {
        $name = $person->first_name;

        if ($person->middle_name) {
            $name .= ' ' . $person->middle_name;
        }

        $name .= ' ' . $person->last_name;

        if ($person->extension_name) {
            $name .= ' ' . $person->extension_name;
        }
        return $name;
    }

    function generateRandomArray($totalValue, $minThresholdPercentage = 15)
    {
        // Determine the number of values (2 to 6)
        $numValues = mt_rand($this->batchAmountMin, $this->batchAmountMax);

        // Minimum threshold for each value
        $minThreshold = $totalValue * ($minThresholdPercentage / 100);

        // Initialize the array
        $values = [];

        // Generate random values ensuring each is above the minimum threshold
        for ($i = 0; $i < $numValues - 1; $i++) {
            $maxPossible = $totalValue - (($numValues - $i - 1) * $minThreshold);
            $values[$i] = mt_rand($minThreshold, $maxPossible);
            $totalValue -= $values[$i];
        }

        // Adjust the values to make sure the sum equals the initial total value
        $values[] = $totalValue;

        return $values;
    }

    function getBarangayName($implementationId, $district): string
    {
        $barangays = $this->getBarangaysByDistrict($district);

        do {
            $barangay = fake()->randomElement($barangays);
        } while (Batch::where('implementations_id', $implementationId)->where('barangay_name', $barangay)->exists());

        return $barangay;
    }

    function getBarangaysByDistrict(string $district): array
    {
        $barangayList = [
            '1st District' => [
                '1-A',
                '2-A',
                '3-A',
                '4-A',
                '5-A',
                '6-A',
                '7-A',
                '8-A',
                '9-A',
                '10-A',
                '11-B',
                '12-B',
                '13-B',
                '14-B',
                '15-B',
                '16-B',
                '17-B',
                '18-B',
                '19-B',
                '20-B',
                '21-C',
                '22-C',
                '23-C',
                '24-C',
                '25-C',
                '26-C',
                '27-C',
                '28-C',
                '29-C',
                '30-C',
                '31-D',
                '32-D',
                '33-D',
                '34-D',
                '35-D',
                '36-D',
                '37-D',
                '38-D',
                '39-D',
                '40-D',
                'Bago Aplaya',
                'Bago Gallera',
                'Baliok',
                'Bucana',
                'Catalunan Grande',
                'Catalunan Pequeño',
                'Dumoy',
                'Langub',
                'Ma-a',
                'Magtuod',
                'Matina Aplaya',
                'Matina Crossing',
                'Matina Pangi',
                'Talomo Proper',
            ],
            '2nd District' => [
                'Agdao Proper',
                'Centro (San Juan)',
                'Gov. Paciano Bangoy',
                'Gov. Vicente Duterte',
                'Kap. Tomas Monteverde, Sr.',
                'Lapu-Lapu',
                'Leon Garcia',
                'Rafael Castillo',
                'San Antonio',
                'Ubalde',
                'Wilfredo Aquino',
                'Acacia',
                'Alfonso Angliongto Sr.',
                'Buhangin Proper',
                'Cabantian',
                'Callawa',
                'Communal',
                'Indangan',
                'Mandug',
                'Pampanga',
                'Sasa',
                'Tigatto',
                'Vicente Hizon Sr.',
                'Waan',
                'Alejandra Navarro (Lasang)',
                'Bunawan Proper',
                'Gatungan',
                'Ilang',
                'Mahayag',
                'Mudiang',
                'Panacan',
                'San Isidro (Licanan)',
                'Tibungco',
                "Colosas",
                "Fatima (Benowang)",
                "Lumiad",
                "Mabuhay",
                "Malabog",
                "Mapula",
                "Panalum",
                "Pandaitan",
                "Paquibato Proper",
                "Paradise Embak",
                "Salapawan",
                "Sumimao",
                "Tapak",
            ],
            '3rd District' => [
                "Baguio Proper",
                "Cadalian",
                "Carmen",
                "Gumalang",
                "Malagos",
                "Tambobong",
                "Tawan-Tawan",
                "Wines",
                "Biao Joaquin",
                "Calinan Proper",
                "Cawayan",
                "Dacudao",
                "Dalagdag",
                "Dominga",
                "Inayangan",
                "Lacson",
                "Lamanan",
                "Lampianao",
                "Megkawayan",
                "Pangyan",
                "Riverside",
                "Saloy",
                "Sirib",
                "Subasta",
                "Talomo River",
                "Tamayong",
                "Wangan",
                "Baganihan",
                "Bantol",
                "Buda",
                "Dalag",
                "Datu Salumay",
                "Gumitan",
                "Magsaysay",
                "Malamba",
                "Marilog Proper",
                "Salaysay",
                "Suawan (Tuli)",
                "Tamugan",
                "Alambre",
                "Atan-Awe",
                "Bangkas Heights",
                "Baracatan",
                "Bato",
                "Bayabas",
                "Binugao",
                "Camansi",
                "Catigan",
                "Crossing Bayabas",
                "Daliao",
                "Daliaon Plantation",
                "Eden",
                "Kilate",
                "Lizada",
                "Lubogan",
                "Marapangi",
                "Mulig",
                "Sibulan",
                "Sirawan",
                "Tagluno",
                "Tagurano",
                "Tibuloy",
                "Toril Proper",
                "Tungkalan",
                "Angalan",
                "Bago Oshiro",
                "Balenggaeng",
                "Biao Escuela",
                "Biao Guinga",
                "Los Amigos",
                "Manambulan",
                "Manuel Guianga",
                "Matina Biao",
                "Mintal",
                "New Carmen",
                "New Valencia",
                "Santo Niño",
                "Tacunan",
                "Tagakpan",
                "Talandang",
                "Tugbok Proper",
                "Ula"
            ],
        ];

        return $barangayList[$district] ?? [];
    }

    protected function checkSpouse($civil_status, $sex, $nameType, $last_name = null)
    {
        $name = null;
        if (strtolower($civil_status) == 'married') {
            switch ($nameType) {
                case 'first':
                    if (strtolower($sex) == 'male') {
                        $name = fake()->randomElement([
                            'Andres',
                            'Antonio',
                            'Angelo',
                            'Aldrin',
                            'Albert',
                            'Armando',
                            'Alfonso',
                            'Alex',
                            'Ariel',
                            'Arturo',
                            'Benito',
                            'Bernardo',
                            'Baltazar',
                            'Bartolome',
                            'Benedicto',
                            'Benjamin',
                            'Berto',
                            'Bonifacio',
                            'Bayani',
                            'Basilio',
                            'Carlos',
                            'Cristobal',
                            'Casimir',
                            'Clemente',
                            'Claro',
                            'Celestino',
                            'Cesar',
                            'Cirilo',
                            'Cornelio',
                            'Calixto',
                            'Domingo',
                            'Diosdado',
                            'Diego',
                            'Delfin',
                            'Dante',
                            'Dionisio',
                            'Danilo',
                            'Damaso',
                            'Daniel',
                            'Darwin',
                            'Emilio',
                            'Enrique',
                            'Eduardo',
                            'Esteban',
                            'Eusebio',
                            'Efren',
                            'Eleuterio',
                            'Eliseo',
                            'Ely',
                            'Epifanio',
                            'Federico',
                            'Fernando',
                            'Florante',
                            'Feliciano',
                            'Francisco',
                            'Felipe',
                            'Fabio',
                            'Florencio',
                            'Fortunato',
                            'Fausto',
                            'Gregorio',
                            'Gabriel',
                            'Gerardo',
                            'Gonzalo',
                            'Gavino',
                            'Gilberto',
                            'Generoso',
                            'Geronimo',
                            'Gaudencio',
                            'Gil',
                            'Hector',
                            'Hilario',
                            'Hernando',
                            'Honesto',
                            'Hermogenes',
                            'Huberto',
                            'Haribon',
                            'Hermoso',
                            'Hilarion',
                            'Homer',
                            'Ignacio',
                            'Isidro',
                            'Ireneo',
                            'Irwin',
                            'Ismael',
                            'Isko',
                            'Ibarra',
                            'Igor',
                            'Jose',
                            'Juan',
                            'Julio',
                            'Justo',
                            'Javier',
                            'Jacinto',
                            'Jovito',
                            'Jethro',
                            'Jomar',
                            'Jericho',
                            'Kiko',
                            'Kaleb',
                            'Kian',
                            'Kulas',
                            'Kristoff',
                            'Kimo',
                            'Kian',
                            'Kaden',
                            'Kean',
                            'Kiyoshi',
                            'Luis',
                            'Leonardo',
                            'Lorenzo',
                            'Lamberto',
                            'Lando',
                            'Lito',
                            'Leonel',
                            'Lavrenti',
                            'Larry',
                            'Lakan',
                            'Manuel',
                            'Marcelo',
                            'Mariano',
                            'Mateo',
                            'Maximo',
                            'Melchor',
                            'Modesto',
                            'Macario',
                            'Martin',
                            'Matias',
                            'Nicolas',
                            'Nestor',
                            'Norberto',
                            'Narciso',
                            'Noel',
                            'Nelson',
                            'Nereo',
                            'Natanael',
                            'Napoleon',
                            'Oscar',
                            'Orlando',
                            'Octavio',
                            'Onofre',
                            'Obet',
                            'Osmundo',
                            'Otoniel',
                            'Oliver',
                            'Odilon',
                            'Orlan',
                            'Pedro',
                            'Pablo',
                            'Patricio',
                            'Panfilo',
                            'Primo',
                            'Percival',
                            'Placido',
                            'Paolo',
                            'Porfirio',
                            'Pepito',
                            'Quirino',
                            'Quirico',
                            'Quinito',
                            'Quicho',
                            'Quilo',
                            'Quimby',
                            'Quixote',
                            'Quino',
                            'Querubin',
                            'Quasimodo',
                            'Rafael',
                            'Ramon',
                            'Rodrigo',
                            'Reynaldo',
                            'Rolando',
                            'Romeo',
                            'Rizal',
                            'Rogelio',
                            'Ruben',
                            'Rodolfo',
                            'Santiago',
                            'Salvador',
                            'Severino',
                            'Simeon',
                            'Silverio',
                            'Saturnino',
                            'Sandro',
                            'Solomon',
                            'Sigfredo',
                            'Sylvestre',
                            'Tomas',
                            'Teodoro',
                            'Tito',
                            'Tiburcio',
                            'Tayo',
                            'Tacio',
                            'Tadeo',
                            'Talib',
                            'Tano',
                            'Teyo',
                            'Urbano',
                            'Uriel',
                            'Ulysses',
                            'Ulrich',
                            'Uziel',
                            'Ulan',
                            'Ubay',
                            'Urdaneta',
                            'Ubaldo',
                            'Umberto',
                            'Vicente',
                            'Valerio',
                            'Victor',
                            'Vincent',
                            'Venancio',
                            'Valentino',
                            'Venusto',
                            'Venerando',
                            'Valeriano',
                            'Val',
                            'Waldo',
                            'Wenceslao',
                            'Waldo',
                            'Wally',
                            'Wilson',
                            'Wendell',
                            'Wilfredo',
                            'Winston',
                            'Xavier',
                            'Xandro',
                            'Xian',
                            'Xenon',
                            'Xeres',
                            'Xebastian',
                            'Xian',
                            'Xander',
                            'Yvan',
                            'Ysmael',
                            'Ygor',
                            'Yoel',
                            'Yusuf',
                            'Yvonne',
                            'Yuri',
                            'Yzrael',
                            'Yen',
                            'Yago',
                            'Ymer',
                            'Yael',
                            'Zacarias',
                            'Zaldy',
                            'Ziggy',
                            'Zeus',
                            'Zosimo',
                            'Zenon',
                            'Zander',
                            'Zandro',
                            'Zack',
                            'Zandro',
                            'Zane',
                            'Zosimo'
                        ]);
                    } else if (strtolower($sex) == 'female') {
                        $name = fake()->randomElement([
                            'Andrea',
                            'Angela',
                            'Adelina',
                            'Aria',
                            'Anabella',
                            'Amara',
                            'Araceli',
                            'Antonia',
                            'Althea',
                            'Amihan',
                            'Beatriz',
                            'Bernadette',
                            'Belinda',
                            'Bambi',
                            'Basilia',
                            'Bonita',
                            'Biance',
                            'Benjamina',
                            'Brenda',
                            'Bethany',
                            'Carmela',
                            'Clarissa',
                            'Camila',
                            'Corazon',
                            'Celestina',
                            'Catherine',
                            'Cecilia',
                            'Catalina',
                            'Chona',
                            'Cristeta',
                            'Diana',
                            'Dianne',
                            'Dahlia',
                            'Delfina',
                            'Divina',
                            'Dominique',
                            'Darlene',
                            'Dalisay',
                            'Danica',
                            'Darcy',
                            'Elena',
                            'Estrella',
                            'Eloisa',
                            'Esperanza',
                            'Editha',
                            'Evangeline',
                            'Eulalia',
                            'Eunice',
                            'Elvira',
                            'Elisa',
                            'Felicia',
                            'Francesca',
                            'Filomena',
                            'Faye',
                            'Fatima',
                            'Florante',
                            'Flordeliza',
                            'Feona',
                            'Febe',
                            'Felicity',
                            'Gabriela',
                            'Guadalupe',
                            'Giselle',
                            'Greta',
                            'Gwendolyn',
                            'Glenda',
                            'Gemma',
                            'Graciela',
                            'Galadriel',
                            'Giana',
                            'Hazel',
                            'Helene',
                            'Hortensia',
                            'Hera',
                            'Harriet',
                            'Hilda',
                            'Hannelore',
                            'Hildegard',
                            'Herminia',
                            'Henrietta',
                            'Imelda',
                            'Isabella',
                            'Ines',
                            'Iliana',
                            'Isha',
                            'Iluminada',
                            'Iara',
                            'Ilang-Ilang',
                            'Isidora',
                            'Idania',
                            'Josefina',
                            'Juanita',
                            'Juliana',
                            'Jocelyn',
                            'Jolina',
                            'Jacinta',
                            'Karina',
                            'Kristina',
                            'Katrina',
                            'Kalista',
                            'Kareen',
                            'Keziah',
                            'Kayla',
                            'Kassandra',
                            'Kiana',
                            'Kimmy',
                            'Lourdes',
                            'Leonora',
                            'Lorena',
                            'Luzviminda',
                            'Luningning',
                            'Leticia',
                            'Lolita',
                            'Laurencia',
                            'Leilani',
                            'Lilybeth',
                            'Maria',
                            'Marissa',
                            'Magdalena',
                            'Magnolia',
                            'Milagros',
                            'Melinda',
                            'Marcela',
                            'Maricar',
                            'Manuela',
                            'Myrna',
                            'Natalia',
                            'Norma',
                            'Nerissa',
                            'Ninfa',
                            'Naomi',
                            'Nydia',
                            'Nenita',
                            'Nelia',
                            'Nieves',
                            'Olivia',
                            'Ophelia',
                            'Ofelia',
                            'Orlinda',
                            'Orquidea',
                            'Odelia',
                            'Octavia',
                            'Omaira',
                            'Osmilda',
                            'Osita',
                            'Patricia',
                            'Priscilla',
                            'Paz',
                            'Perlita',
                            'Pilar',
                            'Paloma',
                            'Paulina',
                            'Paula',
                            'Penelope',
                            'Perla',
                            'Queenie',
                            'Quenby',
                            'Quirina',
                            'Quirita',
                            'Quimberly',
                            'Quenita',
                            'Quirita',
                            'Quenicia',
                            'Quinna',
                            'Quenelle',
                            'Rosalinda',
                            'Rowena',
                            'Remedios',
                            'Regina',
                            'Ruby',
                            'Racquel',
                            'Rizalina',
                            'Rosanna',
                            'Rosita',
                            'Rina',
                            'Solita',
                            'Seraphina',
                            'Soledad',
                            'Salvacion',
                            'Socorro',
                            'Susana',
                            'Sylvia',
                            'Sachi',
                            'Samara',
                            'Selena',
                            'Teresa',
                            'Trinidad',
                            'Tala',
                            'Thelma',
                            'Tintin',
                            'Tisay',
                            'Tessie',
                            'Tatiana',
                            'Tala',
                            'Ursula',
                            'Ulyssa',
                            'Ula',
                            'Ulrica',
                            'Ume',
                            'Urbana',
                            'Valeria',
                            'Veronica',
                            'Violeta',
                            'Venus',
                            'Vanessa',
                            'Viviana',
                            'Vilma',
                            'Wanda',
                            'Wilhelmina',
                            'Winnie',
                            'Wilma',
                            'Winda',
                            'Xenia',
                            'Ximena',
                            'Xochitl',
                            'Xandra',
                            'Yolanda',
                            'Ysabel',
                            'Yvonne',
                            'Yvanna',
                            'Ysabelle',
                            'Yvette',
                            'Yazmin',
                            'Ysolde',
                            'Ynez',
                            'Zenaida',
                            'Zorayda',
                            'Zelia',
                            'Zita',
                            'Zarina',
                            'Zyra',
                            'Zarah',
                            'Zennia',
                            'Zena',
                            'Zenaida',
                            'Zosima'
                        ]);
                    }
                case 'middle':
                    $name = $this->getMiddleName();
                case 'last':
                    $name = $last_name;
                case 'ext':
                    $name = $this->getSuffix();
            }
        }
        return $name ? mb_strtoupper($name, "UTF-8") : null;
    }
    protected function getFirstName($sex)
    {
        $firstNames = null;
        if ($sex === 'male') {
            $firstNames = fake()->randomElement([
                'Andres',
                'Antonio',
                'Angelo',
                'Aldrin',
                'Albert',
                'Armando',
                'Alfonso',
                'Alex',
                'Ariel',
                'Arturo',
                'Benito',
                'Bernardo',
                'Baltazar',
                'Bartolome',
                'Benedicto',
                'Benjamin',
                'Berto',
                'Bonifacio',
                'Bayani',
                'Basilio',
                'Carlos',
                'Cristobal',
                'Casimir',
                'Clemente',
                'Claro',
                'Celestino',
                'Cesar',
                'Cirilo',
                'Cornelio',
                'Calixto',
                'Domingo',
                'Diosdado',
                'Diego',
                'Delfin',
                'Dante',
                'Dionisio',
                'Danilo',
                'Damaso',
                'Daniel',
                'Darwin',
                'Emilio',
                'Enrique',
                'Eduardo',
                'Esteban',
                'Eusebio',
                'Efren',
                'Eleuterio',
                'Eliseo',
                'Ely',
                'Epifanio',
                'Federico',
                'Fernando',
                'Florante',
                'Feliciano',
                'Francisco',
                'Felipe',
                'Fabio',
                'Florencio',
                'Fortunato',
                'Fausto',
                'Gregorio',
                'Gabriel',
                'Gerardo',
                'Gonzalo',
                'Gavino',
                'Gilberto',
                'Generoso',
                'Geronimo',
                'Gaudencio',
                'Gil',
                'Hector',
                'Hilario',
                'Hernando',
                'Honesto',
                'Hermogenes',
                'Huberto',
                'Haribon',
                'Hermoso',
                'Hilarion',
                'Homer',
                'Ignacio',
                'Isidro',
                'Ireneo',
                'Irwin',
                'Ismael',
                'Isko',
                'Ibarra',
                'Igor',
                'Jose',
                'Juan',
                'Julio',
                'Justo',
                'Javier',
                'Jacinto',
                'Jovito',
                'Jethro',
                'Jomar',
                'Jericho',
                'Kiko',
                'Kaleb',
                'Kian',
                'Kulas',
                'Kristoff',
                'Kimo',
                'Kian',
                'Kaden',
                'Kean',
                'Kiyoshi',
                'Luis',
                'Leonardo',
                'Lorenzo',
                'Lamberto',
                'Lando',
                'Lito',
                'Leonel',
                'Lavrenti',
                'Larry',
                'Lakan',
                'Manuel',
                'Marcelo',
                'Mariano',
                'Mateo',
                'Maximo',
                'Melchor',
                'Modesto',
                'Macario',
                'Martin',
                'Matias',
                'Nicolas',
                'Nestor',
                'Norberto',
                'Narciso',
                'Noel',
                'Nelson',
                'Nereo',
                'Natanael',
                'Napoleon',
                'Oscar',
                'Orlando',
                'Octavio',
                'Onofre',
                'Obet',
                'Osmundo',
                'Otoniel',
                'Oliver',
                'Odilon',
                'Orlan',
                'Pedro',
                'Pablo',
                'Patricio',
                'Panfilo',
                'Primo',
                'Percival',
                'Placido',
                'Paolo',
                'Porfirio',
                'Pepito',
                'Quirino',
                'Quirico',
                'Quinito',
                'Quicho',
                'Quilo',
                'Quimby',
                'Quixote',
                'Quino',
                'Querubin',
                'Quasimodo',
                'Rafael',
                'Ramon',
                'Rodrigo',
                'Reynaldo',
                'Rolando',
                'Romeo',
                'Rizal',
                'Rogelio',
                'Ruben',
                'Rodolfo',
                'Santiago',
                'Salvador',
                'Severino',
                'Simeon',
                'Silverio',
                'Saturnino',
                'Sandro',
                'Solomon',
                'Sigfredo',
                'Sylvestre',
                'Tomas',
                'Teodoro',
                'Tito',
                'Tiburcio',
                'Tayo',
                'Tacio',
                'Tadeo',
                'Talib',
                'Tano',
                'Teyo',
                'Urbano',
                'Uriel',
                'Ulysses',
                'Ulrich',
                'Uziel',
                'Ulan',
                'Ubay',
                'Urdaneta',
                'Ubaldo',
                'Umberto',
                'Vicente',
                'Valerio',
                'Victor',
                'Vincent',
                'Venancio',
                'Valentino',
                'Venusto',
                'Venerando',
                'Valeriano',
                'Val',
                'Waldo',
                'Wenceslao',
                'Waldo',
                'Wally',
                'Wilson',
                'Wendell',
                'Wilfredo',
                'Winston',
                'Xavier',
                'Xandro',
                'Xian',
                'Xenon',
                'Xeres',
                'Xebastian',
                'Xian',
                'Xander',
                'Yvan',
                'Ysmael',
                'Ygor',
                'Yoel',
                'Yusuf',
                'Yvonne',
                'Yuri',
                'Yzrael',
                'Yen',
                'Yago',
                'Ymer',
                'Yael',
                'Zacarias',
                'Zaldy',
                'Ziggy',
                'Zeus',
                'Zosimo',
                'Zenon',
                'Zander',
                'Zandro',
                'Zack',
                'Zandro',
                'Zane',
                'Zosimo'
            ]);
        } else {
            $firstNames = fake()->randomElement([
                'Andrea',
                'Angela',
                'Adelina',
                'Aria',
                'Anabella',
                'Amara',
                'Araceli',
                'Antonia',
                'Althea',
                'Amihan',
                'Beatriz',
                'Bernadette',
                'Belinda',
                'Bambi',
                'Basilia',
                'Bonita',
                'Biance',
                'Benjamina',
                'Brenda',
                'Bethany',
                'Carmela',
                'Clarissa',
                'Camila',
                'Corazon',
                'Celestina',
                'Catherine',
                'Cecilia',
                'Catalina',
                'Chona',
                'Cristeta',
                'Diana',
                'Dianne',
                'Dahlia',
                'Delfina',
                'Divina',
                'Dominique',
                'Darlene',
                'Dalisay',
                'Danica',
                'Darcy',
                'Elena',
                'Estrella',
                'Eloisa',
                'Esperanza',
                'Editha',
                'Evangeline',
                'Eulalia',
                'Eunice',
                'Elvira',
                'Elisa',
                'Felicia',
                'Francesca',
                'Filomena',
                'Faye',
                'Fatima',
                'Florante',
                'Flordeliza',
                'Feona',
                'Febe',
                'Felicity',
                'Gabriela',
                'Guadalupe',
                'Giselle',
                'Greta',
                'Gwendolyn',
                'Glenda',
                'Gemma',
                'Graciela',
                'Galadriel',
                'Giana',
                'Hazel',
                'Helene',
                'Hortensia',
                'Hera',
                'Harriet',
                'Hilda',
                'Hannelore',
                'Hildegard',
                'Herminia',
                'Henrietta',
                'Imelda',
                'Isabella',
                'Ines',
                'Iliana',
                'Isha',
                'Iluminada',
                'Iara',
                'Ilang-Ilang',
                'Isidora',
                'Idania',
                'Josefina',
                'Juanita',
                'Juliana',
                'Jocelyn',
                'Jolina',
                'Jacinta',
                'Karina',
                'Kristina',
                'Katrina',
                'Kalista',
                'Kareen',
                'Keziah',
                'Kayla',
                'Kassandra',
                'Kiana',
                'Kimmy',
                'Lourdes',
                'Leonora',
                'Lorena',
                'Luzviminda',
                'Luningning',
                'Leticia',
                'Lolita',
                'Laurencia',
                'Leilani',
                'Lilybeth',
                'Maria',
                'Marissa',
                'Magdalena',
                'Magnolia',
                'Milagros',
                'Melinda',
                'Marcela',
                'Maricar',
                'Manuela',
                'Myrna',
                'Natalia',
                'Norma',
                'Nerissa',
                'Ninfa',
                'Naomi',
                'Nydia',
                'Nenita',
                'Nelia',
                'Nieves',
                'Olivia',
                'Ophelia',
                'Ofelia',
                'Orlinda',
                'Orquidea',
                'Odelia',
                'Octavia',
                'Omaira',
                'Osmilda',
                'Osita',
                'Patricia',
                'Priscilla',
                'Paz',
                'Perlita',
                'Pilar',
                'Paloma',
                'Paulina',
                'Paula',
                'Penelope',
                'Perla',
                'Queenie',
                'Quenby',
                'Quirina',
                'Quirita',
                'Quimberly',
                'Quenita',
                'Quirita',
                'Quenicia',
                'Quinna',
                'Quenelle',
                'Rosalinda',
                'Rowena',
                'Remedios',
                'Regina',
                'Ruby',
                'Racquel',
                'Rizalina',
                'Rosanna',
                'Rosita',
                'Rina',
                'Solita',
                'Seraphina',
                'Soledad',
                'Salvacion',
                'Socorro',
                'Susana',
                'Sylvia',
                'Sachi',
                'Samara',
                'Selena',
                'Teresa',
                'Trinidad',
                'Tala',
                'Thelma',
                'Tintin',
                'Tisay',
                'Tessie',
                'Tatiana',
                'Tala',
                'Ursula',
                'Ulyssa',
                'Ula',
                'Ulrica',
                'Ume',
                'Urbana',
                'Valeria',
                'Veronica',
                'Violeta',
                'Venus',
                'Vanessa',
                'Viviana',
                'Vilma',
                'Wanda',
                'Wilhelmina',
                'Winnie',
                'Wilma',
                'Winda',
                'Xenia',
                'Ximena',
                'Xochitl',
                'Xandra',
                'Yolanda',
                'Ysabel',
                'Yvonne',
                'Yvanna',
                'Ysabelle',
                'Yvette',
                'Yazmin',
                'Ysolde',
                'Ynez',
                'Zenaida',
                'Zorayda',
                'Zelia',
                'Zita',
                'Zarina',
                'Zyra',
                'Zarah',
                'Zennia',
                'Zena',
                'Zenaida',
                'Zosima'
            ]);
        }

        $pickedFirstNames = $firstNames;

        $randomNumber = mt_rand(1, 10);
        if ($randomNumber % 2 === 1) {
            if ($sex === 'male') {
                $firstNames = fake()->randomElement([
                    'Andres',
                    'Antonio',
                    'Angelo',
                    'Aldrin',
                    'Albert',
                    'Armando',
                    'Alfonso',
                    'Alex',
                    'Ariel',
                    'Arturo',
                    'Benito',
                    'Bernardo',
                    'Baltazar',
                    'Bartolome',
                    'Benedicto',
                    'Benjamin',
                    'Berto',
                    'Bonifacio',
                    'Bayani',
                    'Basilio',
                    'Carlos',
                    'Cristobal',
                    'Casimir',
                    'Clemente',
                    'Claro',
                    'Celestino',
                    'Cesar',
                    'Cirilo',
                    'Cornelio',
                    'Calixto',
                    'Domingo',
                    'Diosdado',
                    'Diego',
                    'Delfin',
                    'Dante',
                    'Dionisio',
                    'Danilo',
                    'Damaso',
                    'Daniel',
                    'Darwin',
                    'Emilio',
                    'Enrique',
                    'Eduardo',
                    'Esteban',
                    'Eusebio',
                    'Efren',
                    'Eleuterio',
                    'Eliseo',
                    'Ely',
                    'Epifanio',
                    'Federico',
                    'Fernando',
                    'Florante',
                    'Feliciano',
                    'Francisco',
                    'Felipe',
                    'Fabio',
                    'Florencio',
                    'Fortunato',
                    'Fausto',
                    'Gregorio',
                    'Gabriel',
                    'Gerardo',
                    'Gonzalo',
                    'Gavino',
                    'Gilberto',
                    'Generoso',
                    'Geronimo',
                    'Gaudencio',
                    'Gil',
                    'Hector',
                    'Hilario',
                    'Hernando',
                    'Honesto',
                    'Hermogenes',
                    'Huberto',
                    'Haribon',
                    'Hermoso',
                    'Hilarion',
                    'Homer',
                    'Ignacio',
                    'Isidro',
                    'Ireneo',
                    'Irwin',
                    'Ismael',
                    'Isko',
                    'Ibarra',
                    'Igor',
                    'Jose',
                    'Juan',
                    'Julio',
                    'Justo',
                    'Javier',
                    'Jacinto',
                    'Jovito',
                    'Jethro',
                    'Jomar',
                    'Jericho',
                    'Kiko',
                    'Kaleb',
                    'Kian',
                    'Kulas',
                    'Kristoff',
                    'Kimo',
                    'Kian',
                    'Kaden',
                    'Kean',
                    'Kiyoshi',
                    'Luis',
                    'Leonardo',
                    'Lorenzo',
                    'Lamberto',
                    'Lando',
                    'Lito',
                    'Leonel',
                    'Lavrenti',
                    'Larry',
                    'Lakan',
                    'Manuel',
                    'Marcelo',
                    'Mariano',
                    'Mateo',
                    'Maximo',
                    'Melchor',
                    'Modesto',
                    'Macario',
                    'Martin',
                    'Matias',
                    'Nicolas',
                    'Nestor',
                    'Norberto',
                    'Narciso',
                    'Noel',
                    'Nelson',
                    'Nereo',
                    'Natanael',
                    'Napoleon',
                    'Oscar',
                    'Orlando',
                    'Octavio',
                    'Onofre',
                    'Obet',
                    'Osmundo',
                    'Otoniel',
                    'Oliver',
                    'Odilon',
                    'Orlan',
                    'Pedro',
                    'Pablo',
                    'Patricio',
                    'Panfilo',
                    'Primo',
                    'Percival',
                    'Placido',
                    'Paolo',
                    'Porfirio',
                    'Pepito',
                    'Quirino',
                    'Quirico',
                    'Quinito',
                    'Quicho',
                    'Quilo',
                    'Quimby',
                    'Quixote',
                    'Quino',
                    'Querubin',
                    'Quasimodo',
                    'Rafael',
                    'Ramon',
                    'Rodrigo',
                    'Reynaldo',
                    'Rolando',
                    'Romeo',
                    'Rizal',
                    'Rogelio',
                    'Ruben',
                    'Rodolfo',
                    'Santiago',
                    'Salvador',
                    'Severino',
                    'Simeon',
                    'Silverio',
                    'Saturnino',
                    'Sandro',
                    'Solomon',
                    'Sigfredo',
                    'Sylvestre',
                    'Tomas',
                    'Teodoro',
                    'Tito',
                    'Tiburcio',
                    'Tayo',
                    'Tacio',
                    'Tadeo',
                    'Talib',
                    'Tano',
                    'Teyo',
                    'Urbano',
                    'Uriel',
                    'Ulysses',
                    'Ulrich',
                    'Uziel',
                    'Ulan',
                    'Ubay',
                    'Urdaneta',
                    'Ubaldo',
                    'Umberto',
                    'Vicente',
                    'Valerio',
                    'Victor',
                    'Vincent',
                    'Venancio',
                    'Valentino',
                    'Venusto',
                    'Venerando',
                    'Valeriano',
                    'Val',
                    'Waldo',
                    'Wenceslao',
                    'Waldo',
                    'Wally',
                    'Wilson',
                    'Wendell',
                    'Wilfredo',
                    'Winston',
                    'Xavier',
                    'Xandro',
                    'Xian',
                    'Xenon',
                    'Xeres',
                    'Xebastian',
                    'Xian',
                    'Xander',
                    'Yvan',
                    'Ysmael',
                    'Ygor',
                    'Yoel',
                    'Yusuf',
                    'Yvonne',
                    'Yuri',
                    'Yzrael',
                    'Yen',
                    'Yago',
                    'Ymer',
                    'Yael',
                    'Zacarias',
                    'Zaldy',
                    'Ziggy',
                    'Zeus',
                    'Zosimo',
                    'Zenon',
                    'Zander',
                    'Zandro',
                    'Zack',
                    'Zandro',
                    'Zane',
                    'Zosimo'
                ]);
            } else {
                $firstNames = fake()->randomElement([
                    'Andrea',
                    'Angela',
                    'Adelina',
                    'Aria',
                    'Anabella',
                    'Amara',
                    'Araceli',
                    'Antonia',
                    'Althea',
                    'Amihan',
                    'Beatriz',
                    'Bernadette',
                    'Belinda',
                    'Bambi',
                    'Basilia',
                    'Bonita',
                    'Biance',
                    'Benjamina',
                    'Brenda',
                    'Bethany',
                    'Carmela',
                    'Clarissa',
                    'Camila',
                    'Corazon',
                    'Celestina',
                    'Catherine',
                    'Cecilia',
                    'Catalina',
                    'Chona',
                    'Cristeta',
                    'Diana',
                    'Dianne',
                    'Dahlia',
                    'Delfina',
                    'Divina',
                    'Dominique',
                    'Darlene',
                    'Dalisay',
                    'Danica',
                    'Darcy',
                    'Elena',
                    'Estrella',
                    'Eloisa',
                    'Esperanza',
                    'Editha',
                    'Evangeline',
                    'Eulalia',
                    'Eunice',
                    'Elvira',
                    'Elisa',
                    'Felicia',
                    'Francesca',
                    'Filomena',
                    'Faye',
                    'Fatima',
                    'Florante',
                    'Flordeliza',
                    'Feona',
                    'Febe',
                    'Felicity',
                    'Gabriela',
                    'Guadalupe',
                    'Giselle',
                    'Greta',
                    'Gwendolyn',
                    'Glenda',
                    'Gemma',
                    'Graciela',
                    'Galadriel',
                    'Giana',
                    'Hazel',
                    'Helene',
                    'Hortensia',
                    'Hera',
                    'Harriet',
                    'Hilda',
                    'Hannelore',
                    'Hildegard',
                    'Herminia',
                    'Henrietta',
                    'Imelda',
                    'Isabella',
                    'Ines',
                    'Iliana',
                    'Isha',
                    'Iluminada',
                    'Iara',
                    'Ilang-Ilang',
                    'Isidora',
                    'Idania',
                    'Josefina',
                    'Juanita',
                    'Juliana',
                    'Jocelyn',
                    'Jolina',
                    'Jacinta',
                    'Karina',
                    'Kristina',
                    'Katrina',
                    'Kalista',
                    'Kareen',
                    'Keziah',
                    'Kayla',
                    'Kassandra',
                    'Kiana',
                    'Kimmy',
                    'Lourdes',
                    'Leonora',
                    'Lorena',
                    'Luzviminda',
                    'Luningning',
                    'Leticia',
                    'Lolita',
                    'Laurencia',
                    'Leilani',
                    'Lilybeth',
                    'Maria',
                    'Marissa',
                    'Magdalena',
                    'Magnolia',
                    'Milagros',
                    'Melinda',
                    'Marcela',
                    'Maricar',
                    'Manuela',
                    'Myrna',
                    'Natalia',
                    'Norma',
                    'Nerissa',
                    'Ninfa',
                    'Naomi',
                    'Nydia',
                    'Nenita',
                    'Nelia',
                    'Nieves',
                    'Olivia',
                    'Ophelia',
                    'Ofelia',
                    'Orlinda',
                    'Orquidea',
                    'Odelia',
                    'Octavia',
                    'Omaira',
                    'Osmilda',
                    'Osita',
                    'Patricia',
                    'Priscilla',
                    'Paz',
                    'Perlita',
                    'Pilar',
                    'Paloma',
                    'Paulina',
                    'Paula',
                    'Penelope',
                    'Perla',
                    'Queenie',
                    'Quenby',
                    'Quirina',
                    'Quirita',
                    'Quimberly',
                    'Quenita',
                    'Quirita',
                    'Quenicia',
                    'Quinna',
                    'Quenelle',
                    'Rosalinda',
                    'Rowena',
                    'Remedios',
                    'Regina',
                    'Ruby',
                    'Racquel',
                    'Rizalina',
                    'Rosanna',
                    'Rosita',
                    'Rina',
                    'Solita',
                    'Seraphina',
                    'Soledad',
                    'Salvacion',
                    'Socorro',
                    'Susana',
                    'Sylvia',
                    'Sachi',
                    'Samara',
                    'Selena',
                    'Teresa',
                    'Trinidad',
                    'Tala',
                    'Thelma',
                    'Tintin',
                    'Tisay',
                    'Tessie',
                    'Tatiana',
                    'Tala',
                    'Ursula',
                    'Ulyssa',
                    'Ula',
                    'Ulrica',
                    'Ume',
                    'Urbana',
                    'Valeria',
                    'Veronica',
                    'Violeta',
                    'Venus',
                    'Vanessa',
                    'Viviana',
                    'Vilma',
                    'Wanda',
                    'Wilhelmina',
                    'Winnie',
                    'Wilma',
                    'Winda',
                    'Xenia',
                    'Ximena',
                    'Xochitl',
                    'Xandra',
                    'Yolanda',
                    'Ysabel',
                    'Yvonne',
                    'Yvanna',
                    'Ysabelle',
                    'Yvette',
                    'Yazmin',
                    'Ysolde',
                    'Ynez',
                    'Zenaida',
                    'Zorayda',
                    'Zelia',
                    'Zita',
                    'Zarina',
                    'Zyra',
                    'Zarah',
                    'Zennia',
                    'Zena',
                    'Zenaida',
                    'Zosima'
                ]);
            }
            $pickedFirstNames .= ' ' . $firstNames;
        }

        return mb_strtoupper($pickedFirstNames, "UTF-8");
    }

    protected function getLastName()
    {
        return mb_strtoupper(fake()->randomElement([
            'Abad',
            'Abalos',
            'Abdullah',
            'Abdul',
            'Abella',
            'Acuña',
            'Acosta',
            'Adriano',
            'Agustin',
            'Aguilar',
            'Aguirre',
            'Alba',
            'Alcantara',
            'Alejandro',
            'Ali',
            'Alonzo',
            'Alvarez',
            'Alvarado',
            'Alfonso',
            'Alba',
            'Alcantara',
            'Alejandro',
            'Ali',
            'Alonzo',
            'Alvarez',
            'Andaya',
            'Andres',
            'Andrade',
            'Ang',
            'Angeles',
            'Anonuevo',
            'Antonio',
            'Aquino',
            'Arellano',
            'Arevalo',
            'Apostol',
            'Arcilla',
            'Aragon',
            'Arroyo',
            'Asis',
            'Asuncion',
            'Austria',
            'Avila',
            'Baguio',
            'Baltazar',
            'Ballesteros',
            'Basa',
            'Basco',
            'Basilio',
            'Bartolome',
            'Barrientos',
            'Bautista',
            'Baylon',
            'Beltran',
            'Bello',
            'Benitez',
            'Bernabe',
            'Bernal',
            'Bernardo',
            'Blanco',
            'Bondoc',
            'Bonifacio',
            'Borja',
            'Borromeo',
            'Bravo',
            'Briones',
            'Buenaventura',
            'Bueno',
            'Bustamante',
            'Caballero',
            'Cabrera',
            'Cabral',
            'Calderon',
            'Calma',
            'Camacho',
            'Canete',
            'Canlas',
            'Canoy',
            'Carino',
            'Carpio',
            'Carlos',
            'Castaneda',
            'Castillo',
            'Castor',
            'Castro',
            'Catalan',
            'Cayabyab',
            'Cervantes',
            'Chan',
            'Chavez',
            'Chua',
            'Clemente',
            'Cordero',
            'Corpus',
            'Concepcion',
            'Constantino',
            'Conde',
            'Cordova',
            'Cortes',
            'Cortez',
            'Collado',
            'Cuizon',
            'Cristobal',
            'Cruz',
            'Custodio',
            'Dela Cruz',
            'Dela Pena',
            'Dela Rosa',
            'Dela Torre',
            'Delgado',
            'Delos Reyes',
            'Delos Santos',
            'Diaz',
            'Dizon',
            'Domingo',
            'Dominguez',
            'Dulay',
            'Duran',
            'Estrada',
            'Espina',
            'Espino',
            'Espiritu',
            'Esguerra',
            'Esteban',
            'Estrella',
            'Evangelista',
            'Fernandez',
            'Fernando',
            'Feliciano',
            'Felipe',
            'Ferrer',
            'Flores',
            'Francisco',
            'Franco',
            'Frias',
            'Fuentes',
            'Gallardo',
            'Galang',
            'Galicia',
            'Gallego',
            'Galvez',
            'Gamboa',
            'Garcia',
            'Gaspar',
            'Geronimo',
            'Gregorio',
            'Gomez',
            'Go',
            'Gonzaga',
            'Gonzales',
            'Guerrero',
            'Gutierrez',
            'Guinto',
            'Guzman',
            'Hilario',
            'Hipolito',
            'Hernandez',
            'Ibanez',
            'Ilagan',
            'Ignacio',
            'Jacinto',
            'Javier',
            'Jimenez',
            'Jose',
            'Juan',
            'Junio',
            'Labrador',
            'Lachica',
            'Lacson',
            'Lara',
            'Laurente',
            'Lazaro',
            'Ledesma',
            'Legaspi',
            'Lim',
            'Lorenzo',
            'Lopez',
            'Lozada',
            'Lucas',
            'Lucero',
            'Luna',
            'Mahinay',
            'Mallari',
            'Manalo',
            'Manansala',
            'Manzano',
            'Marcelo',
            'Marquez',
            'Marasigan',
            'Martinez',
            'Mateo',
            'Mata',
            'Mejia',
            'Medina',
            'Mendez',
            'Mercado',
            'Miranda',
            'Miguel',
            'Molina',
            'Molino',
            'Montero',
            'Morales',
            'Moreno',
            'Muhammad',
            'Munoz',
            'Narciso',
            'Navarro',
            'Natividad',
            'Nicolas',
            'Nunez',
            'Ocampo',
            'Oliva',
            'Omar',
            'Ong',
            'Ortega',
            'Ortiz',
            'Padilla',
            'Palma',
            'Panes',
            'Pangilinan',
            'Pangan',
            'Pascual',
            'Pascua',
            'Peña',
            'Pepito',
            'Perez',
            'Pimentel',
            'Pineda',
            'Ponce',
            'Porras',
            'Prado',
            'Pablo',
            'Quijano',
            'Quinto',
            'Ramirez',
            'Ramos',
            'Ramos',
            'Reyes',
            'Rivera',
            'Rodriguez',
            'Roldan',
            'Romero',
            'Romualdez',
            'Ronquillo',
            'Roxas',
            'Ruedas',
            'Ruiz',
            'Salvador',
            'Salas',
            'Sali',
            'Samonte',
            'Sanchez',
            'Santillan',
            'Santos',
            'Sarmiento',
            'Sebastian',
            'Sebastian',
            'Serrano',
            'Serrano',
            'Solomon',
            'Solis',
            'Solis',
            'Soriano',
            'Suarez',
            'Tomas',
            'Tolentino',
            'Torres',
            'Tuazon',
            'Valdez',
            'Valencia',
            'Valenzuela',
            'Vargas',
            'Velasco',
            'Ventura',
            'Vergara',
            'Vicente',
            'Villa',
            'Villanueva',
            'Villarin',
            'Villamor',
            'Villaflor',
            'Villanueva',
            'Villareal',
            'Yap',
            'Yu',
            'Zamora',
            'Zapanta',
        ]), "UTF-8");
    }

    protected function getMiddleName()
    {
        $name = fake()->optional(0.85)->randomElement([
            'Abad',
            'Abalos',
            'Abdullah',
            'Abdul',
            'Abella',
            'Acuña',
            'Acosta',
            'Adriano',
            'Agustin',
            'Aguilar',
            'Aguirre',
            'Alba',
            'Alcantara',
            'Alejandro',
            'Ali',
            'Alonzo',
            'Alvarez',
            'Alvarado',
            'Alfonso',
            'Alba',
            'Alcantara',
            'Alejandro',
            'Ali',
            'Alonzo',
            'Alvarez',
            'Andaya',
            'Andres',
            'Andrade',
            'Ang',
            'Angeles',
            'Anonuevo',
            'Antonio',
            'Aquino',
            'Arellano',
            'Arevalo',
            'Apostol',
            'Arcilla',
            'Aragon',
            'Arroyo',
            'Asis',
            'Asuncion',
            'Austria',
            'Avila',
            'Baguio',
            'Baltazar',
            'Ballesteros',
            'Basa',
            'Basco',
            'Basilio',
            'Bartolome',
            'Barrientos',
            'Bautista',
            'Baylon',
            'Beltran',
            'Bello',
            'Benitez',
            'Bernabe',
            'Bernal',
            'Bernardo',
            'Blanco',
            'Bondoc',
            'Bonifacio',
            'Borja',
            'Borromeo',
            'Bravo',
            'Briones',
            'Buenaventura',
            'Bueno',
            'Bustamante',
            'Caballero',
            'Cabrera',
            'Cabral',
            'Calderon',
            'Calma',
            'Camacho',
            'Canete',
            'Canlas',
            'Canoy',
            'Carino',
            'Carpio',
            'Carlos',
            'Castaneda',
            'Castillo',
            'Castor',
            'Castro',
            'Catalan',
            'Cayabyab',
            'Cervantes',
            'Chan',
            'Chavez',
            'Chua',
            'Clemente',
            'Cordero',
            'Corpus',
            'Concepcion',
            'Constantino',
            'Conde',
            'Cordova',
            'Cortes',
            'Cortez',
            'Collado',
            'Cuizon',
            'Cristobal',
            'Cruz',
            'Custodio',
            'Dela Cruz',
            'Dela Pena',
            'Dela Rosa',
            'Dela Torre',
            'Delgado',
            'Delos Reyes',
            'Delos Santos',
            'Diaz',
            'Dizon',
            'Domingo',
            'Dominguez',
            'Dulay',
            'Duran',
            'Estrada',
            'Espina',
            'Espino',
            'Espiritu',
            'Esguerra',
            'Esteban',
            'Estrella',
            'Evangelista',
            'Fernandez',
            'Fernando',
            'Feliciano',
            'Felipe',
            'Ferrer',
            'Flores',
            'Francisco',
            'Franco',
            'Frias',
            'Fuentes',
            'Gallardo',
            'Galang',
            'Galicia',
            'Gallego',
            'Galvez',
            'Gamboa',
            'Garcia',
            'Gaspar',
            'Geronimo',
            'Gregorio',
            'Gomez',
            'Go',
            'Gonzaga',
            'Gonzales',
            'Guerrero',
            'Gutierrez',
            'Guinto',
            'Guzman',
            'Hilario',
            'Hipolito',
            'Hernandez',
            'Ibanez',
            'Ilagan',
            'Ignacio',
            'Jacinto',
            'Javier',
            'Jimenez',
            'Jose',
            'Juan',
            'Junio',
            'Labrador',
            'Lachica',
            'Lacson',
            'Lara',
            'Laurente',
            'Lazaro',
            'Ledesma',
            'Legaspi',
            'Lim',
            'Lorenzo',
            'Lopez',
            'Lozada',
            'Lucas',
            'Lucero',
            'Luna',
            'Mahinay',
            'Mallari',
            'Manalo',
            'Manansala',
            'Manzano',
            'Marcelo',
            'Marquez',
            'Marasigan',
            'Martinez',
            'Mateo',
            'Mata',
            'Mejia',
            'Medina',
            'Mendez',
            'Mercado',
            'Miranda',
            'Miguel',
            'Molina',
            'Molino',
            'Montero',
            'Morales',
            'Moreno',
            'Muhammad',
            'Munoz',
            'Narciso',
            'Navarro',
            'Natividad',
            'Nicolas',
            'Nunez',
            'Ocampo',
            'Oliva',
            'Omar',
            'Ong',
            'Ortega',
            'Ortiz',
            'Padilla',
            'Palma',
            'Panes',
            'Pangilinan',
            'Pangan',
            'Pascual',
            'Pascua',
            'Peña',
            'Pepito',
            'Perez',
            'Pimentel',
            'Pineda',
            'Ponce',
            'Porras',
            'Prado',
            'Pablo',
            'Quijano',
            'Quinto',
            'Ramirez',
            'Ramos',
            'Ramos',
            'Reyes',
            'Rivera',
            'Rodriguez',
            'Roldan',
            'Romero',
            'Romualdez',
            'Ronquillo',
            'Roxas',
            'Ruedas',
            'Ruiz',
            'Salvador',
            'Salas',
            'Sali',
            'Samonte',
            'Sanchez',
            'Santillan',
            'Santos',
            'Sarmiento',
            'Sebastian',
            'Sebastian',
            'Serrano',
            'Serrano',
            'Solomon',
            'Solis',
            'Solis',
            'Soriano',
            'Suarez',
            'Tomas',
            'Tolentino',
            'Torres',
            'Tuazon',
            'Valdez',
            'Valencia',
            'Valenzuela',
            'Vargas',
            'Velasco',
            'Ventura',
            'Vergara',
            'Vicente',
            'Villa',
            'Villanueva',
            'Villarin',
            'Villamor',
            'Villaflor',
            'Villanueva',
            'Villareal',
            'Yap',
            'Yu',
            'Zamora',
            'Zapanta',
        ]);
        return $name ? mb_strtoupper($name, "UTF-8") : null;
    }

    protected function getSuffix()
    {
        $name = fake()->optional(0.1)->randomElement(['I', 'II', 'III', 'IV', 'Sr.', 'Jr.']);
        return $name ? mb_strtoupper($name, "UTF-8") : null;
    }

    // -----------------------------------
// 
// 
// 
//      Do not uncomment this part
// 
// 
// 
// 
    // -----------------------------------


    // function getTotalBatchesCount()
    // {
    //     $totalBatchesCount = DB::table('batches')->count();
    //     return $totalBatchesCount;
    // }

    // function distributeSlots(): int
    // {
    //     $percentage = (static::$globalTotalSlots / static::$originalTotalSlots) * 100;
    //     // Check if the remaining slots is below a certain threshold
    //     if ($percentage <= 25) {
    //         static::$randBatchAmount = 0;
    //         return 69; // return the remaining slots
    //     }
    //     // The max allocated slots shouldn't exceed more than half of its value
    //     $max = floor(static::$globalTotalSlots * 0.75);
    //     // Generate a random slot allocation ensuring that there are enough slots left for the remaining batches
    //     $slot = rand(10, $max);
    //     static::$globalTotalSlots -= $slot;

    //     return $slot;
    // }
}
