<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\Batch;
use App\Models\Beneficiary;
use App\Models\Code;
use App\Models\Implementation;
use App\Models\SystemsLog;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\UserSetting;
use Carbon\Carbon;
use Database\Factories\CredentialFactory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    protected $implementationAmount = 100;
    protected $coordinatorsAmount = 10;
    protected $assignmentAmountMin = 2;
    protected $assignmentAmountMax = 6;
    protected $batchAmountMin = 2;
    protected $batchAmountMax = 6;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::factory()->create([
            'first_name' => 'TU-Admin',
            'middle_name' => '-',
            'last_name' => '-',
            'extension_name' => '-',
            'email' => 'tu-admin@gmail.com',
            'password' => Hash::make('password'),
            'contact_num' => fake()->phoneNumber(),
            'regional_office' => null,
            'field_office' => null,
            'user_type' => 'admin',
        ]);
        SystemsLog::factory()->create([
            'users_id' => $admin['id'],
            'log_timestamp' => Carbon::now(),
            'description' => $admin['first_name'] . ' has been created as admin.'
        ]);

        $rFocalUser = User::factory()->create([
            'field_office' => null,
            'user_type' => 'r_focal',
        ]);

        SystemsLog::factory()->create([
            'users_id' => $rFocalUser['id'],
            'log_timestamp' => Carbon::now(),
            'description' => $this->getFullName($rFocalUser) . ' has been created as regional office focal.'
        ]);

        $focalUser = User::factory()->create([
            'user_type' => 'focal',
        ]);

        SystemsLog::factory()->create([
            'users_id' => $focalUser['id'],
            'log_timestamp' => Carbon::now(),
            'description' => $this->getFullName($focalUser) . ' has been created as field office focal.'
        ]);

        $coordinatorUsers = User::factory($this->coordinatorsAmount)->create();

        foreach ($coordinatorUsers as $key => $user) {
            SystemsLog::factory()->create([
                'users_id' => $user['id'],
                'log_timestamp' => Carbon::now(),
                'description' => $this->getFullName($user) . ' has been created as field office focal.'
            ]);
        }

        # Uncomment and use this only when adding additional settings
        # also remove all existing settings if you're adding new settings
        # so that it won't overwrite the settings in production
        // $focalUser = User::where('user_type', 'focal')->get();
        // $coordinatorUsers = User::where('user_type', 'coordinator')->get();

        $settingsFocal = [
            'minimum_wage' => config('settings.minimum_wage'),
            'duplication_threshold' => config('settings.duplication_threshold'),
            'extensive_matching' => config('settings.extensive_matching'),
            'project_number_prefix' => config('settings.project_number_prefix'),
            'batch_number_prefix' => config('settings.batch_number_prefix'),
        ];

        $settingsCoordinator = [
            'minimum_wage' => config('settings.minimum_wage'),
            'duplication_threshold' => config('settings.duplication_threshold'),
            'extensive_matching' => config('settings.extensive_matching'),
        ];

        foreach ($settingsFocal as $key => $setting) {
            $initSetting = UserSetting::factory()->create([
                'users_id' => $focalUser->id,
                'key' => $key,
                'value' => $setting,
            ]);

            SystemsLog::factory()->create([
                'users_id' => null,
                'log_timestamp' => Carbon::now(),
                'description' => 'A setting ' . $initSetting['key'] . ' has been initialized with ' . $initSetting['value'] . ' for ' . $this->getFullName(person: $focalUser)
            ]);
        }

        foreach ($coordinatorUsers as $user) {
            foreach ($settingsCoordinator as $key => $setting) {
                $initSetting = UserSetting::factory()->create([
                    'users_id' => $user->id,
                    'key' => $key,
                    'value' => $setting,
                ]);

                SystemsLog::factory()->create([
                    'users_id' => null,
                    'log_timestamp' => Carbon::now(),
                    'description' => 'A setting ' . $initSetting['key'] . ' has been initialized with ' . $initSetting['value'] . ' for ' . $this->getFullName(person: $user)
                ]);
            }
        }

        $project_title = ucwords('implementation number');
        $implementations = Implementation::factory($this->implementationAmount)->create();

        foreach ($implementations as $key => $implementation) {
            $implementation->update([
                'project_title' => $project_title . ' ' . $key,
            ]);
            SystemsLog::factory()->create([
                'users_id' => $focalUser->id,
                'log_timestamp' => Carbon::now(),
                'description' => 'Created an implementation project ' . $implementation['project_num'],
            ]);
        }

        $batches = $implementations->flatMap(function ($implementation) {
            $currentDate = $implementation->created_at;
            $allottedSlots = $this->generateRandomArray($implementation->total_slots);

            return collect($allottedSlots)->map(function ($slots) use ($implementation, $currentDate) {
                return Batch::factory()->create([
                    'implementations_id' => $implementation->id,
                    'barangay_name' => $this->getBarangayName($implementation->id),
                    'slots_allocated' => $slots,
                    'created_at' => $currentDate,
                    'updated_at' => $currentDate,
                ]);
            });
        });

        foreach ($batches as $batch) {
            SystemsLog::factory()->create([
                'users_id' => $focalUser->id,
                'log_timestamp' => Carbon::now(),
                'description' => 'Created a batch assignment ' . $batch['batch_num'] . ' in Project ' . Implementation::find($batch['implementations_id'])->get('project_num'),
            ]);

            $amount = mt_rand($this->assignmentAmountMin, $this->assignmentAmountMax);
            $coordinators = $coordinatorUsers->random($amount);
            $currentDate = $batch->created_at;

            $coordinators->each(function ($user) use ($batch, $currentDate, $focalUser) {
                $assignment = Assignment::factory()->create([
                    'batches_id' => $batch->id,
                    'users_id' => $user->id,
                    'created_at' => $currentDate,
                    'updated_at' => $currentDate,
                ]);

                SystemsLog::factory()->create([
                    'users_id' => $focalUser->id,
                    'log_timestamp' => Carbon::now(),
                    'description' => 'Assigned ' . $this->getFullName($user) . ' in Batch ' . $batch->batch_num,
                ]);
            });
        }

        $focalUserId = $focalUser->id; // example focal user id
        $batches = Batch::whereHas('implementation', fn($query) =>
            $query->where('users_id', $focalUserId))
            ->with('implementation:id,district')
            ->get();

        foreach ($batches as $batch) {
            $batch->update(['submission_status' => 'submitted']);
            SystemsLog::factory()->create([
                'users_id' => $focalUserId,
                'log_timestamp' => Carbon::now(),
                'description' => 'Updated a batch (' . $batch->batch_num . ')',
            ]);
            $code = Code::factory()->create(['batches_id' => $batch->id]);

            SystemsLog::factory()->create([
                'users_id' => $focalUserId,
                'log_timestamp' => Carbon::now(),
                'description' => 'Created an access code for Batch ' . $code->access_code,
            ]);

            $beneficiary = Beneficiary::factory($batch->slots_allocated)->create([
                'batches_id' => $batch->id,
                'barangay_name' => $batch->barangay_name,
                'district' => $batch->implementation->district,
            ]);

            SystemsLog::factory()->create([
                'users_id' => $focalUser->id,
                'log_timestamp' => Carbon::now(),
                'description' => 'Added ' . $this->getFullName($beneficiary) . ' as beneficiary in Batch ' . $batch->batch_num,
            ]);

            // CredentialFactory::factory()->create([
            //     'beneficiaries_id' => $beneficiary->id,

            // ]);
        }
    }

    function getFullName($person)
    {
        $name = null;
        $name = $person['first_name'];

        if ($person['middle_name']) {
            $name .= ' ' . $person['middle_name'];
        }

        $name .= ' ' . $person['last_name'];

        if ($person['extension_name']) {
            $name .= ' ' . $person['extension_name'];
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

    function getBarangayName($implementationId): string
    {
        $district = Implementation::find($implementationId)->district;
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
