<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\Batch;
use App\Models\Beneficiary;
use App\Models\Code;
use App\Models\Implementation;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
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
        User::factory()->create([
            'field_office' => null,
            'user_type' => 'r_focal',
        ]);
        User::factory()->create([
            'user_type' => 'focal',
        ]);
        User::factory(10)->create();

        $randImpAmount = 100;
        Implementation::factory($randImpAmount)->create();
        $batches = null;

        for ($i = 1; $i <= $randImpAmount; $i++) {
            $total_slots = Implementation::where('id', $i)->value('total_slots');
            $currentDate = Implementation::where('id', $i)->value('created_at');

            $alloted_slots = $this->generateRandomArray($total_slots);
            foreach ($alloted_slots as $slots) {
                $batches = Batch::factory()->create([
                    'implementations_id' => $i,
                    'barangay_name' => $this->getBarangayName($i),
                    'slots_allocated' => $slots,
                    'created_at' => $currentDate,
                    'updated_at' => $currentDate,
                ]);
            }
        }
        $batches = Batch::all();
        foreach ($batches as $batch) {
            $amount = rand(1, 6);
            $previousUser = 0;
            $currentDate = $batch->created_at;

            for ($j = 1; $j <= $amount; $j++) {
                $user = rand(3, 12);
                if ($user != $previousUser) {
                    $previousUser = $user;
                    Assignment::factory()->create([
                        'batches_id' => $batch->id,
                        'users_id' => $user,
                        'created_at' => $currentDate,
                        'updated_at' => $currentDate,
                    ]);
                }
            }
        }

        $focalUserId = 3; // example focal user id
        $batches = Batch::whereHas('implementation', function ($query) use ($focalUserId) {
            $query->where('users_id', $focalUserId);
        })
            ->with([
                'implementation' => function ($query) {
                    $query->select('id', 'district');
                }
            ])
            ->get();

        foreach ($batches as $batch) {
            $batches_id = $batch->id;
            $barangay_name = $batch->barangay_name;
            $district = $batch->implementation->district;
            $slots_allocated = $batch->slots_allocated;

            $batch->submission_status = 'submitted';
            $batch->save();

            Code::factory()->create([
                'batches_id' => $batches_id,
            ]);

            Beneficiary::factory($slots_allocated)->create([
                'batches_id' => $batches_id,
                'barangay_name' => $barangay_name,
                'district' => $district,
            ]);
        }
    }

    function generateRandomArray($totalValue, $minThresholdPercentage = 15)
    {
        // Determine the number of values (3 to 6)
        $numValues = rand(2, 7);

        // Minimum threshold for each value
        $minThreshold = $totalValue * ($minThresholdPercentage / 100);

        // Initialize the array
        $values = [];

        // Generate random values ensuring each is above the minimum threshold
        for ($i = 0; $i < $numValues; $i++) {
            $values[$i] = rand($minThreshold, $totalValue - ($numValues - $i - 1) * $minThreshold);
            $totalValue -= $values[$i];
        }

        // Adjust the values to make sure the sum equals the initial total value
        $values[$numValues - 1] += $totalValue;

        return $values;
    }

    function getBarangayName($implementationId): string // $implementationId = 1 | $district = 'Agdao'
    {
        $barangay = '';
        $district = Implementation::where('id', $implementationId)->value('district');

        switch ($district) {
            case 'Agdao':
                while (true) {
                    $choosenBarangay = fake()->randomElement(['Agdao Proper', 'Centro (San Juan)', 'Gov. Paciano Bangoy', 'Gov. Vicente Duterte', 'Kap. Tomas Monteverde, Sr.', 'Lapu-Lapu', 'Leon Garcia', 'Rafael Castillo', 'San Antonio', 'Ubalde', 'Wilfredo Aquino']);
                    if (Batch::where('implementations_id', $implementationId)->where('barangay_name', $choosenBarangay)->exists()) {
                        continue;
                    }
                    $barangay = $choosenBarangay;
                    break;
                }
                break;
            case 'Talomo':
                while (true) {
                    $choosenBarangay = fake()->randomElement(['Bago Aplaya', 'Bago Gallera', 'Baliok', 'Bucana', 'Catalunan Grande', 'Catalunan Pequeño', 'Dumoy', 'Langub', 'Ma-a', 'Magtuod', 'Matina Aplaya', 'Matina Crossing', 'Matina Pangi', 'Talomo Proper']);
                    if (Batch::where('implementations_id', $implementationId)->where('barangay_name', $choosenBarangay)->exists()) {
                        continue;
                    }
                    $barangay = $choosenBarangay;
                    break;
                }
                break;
            case 'Bunawan':
                while (true) {
                    $choosenBarangay = fake()->randomElement(['Alejandra Navarro (Lasang)', 'Bunawan Proper', 'Gatungan', 'Ilang', 'Mahayag', 'Mudiang', 'Panacan', 'San Isidro (Licanan)', 'Tibungco']);
                    if (Batch::where('implementations_id', $implementationId)->where('barangay_name', $choosenBarangay)->exists()) {
                        continue;
                    }
                    $barangay = $choosenBarangay;
                    break;
                }
                break;
            case 'Poblacion':
                while (true) {
                    $choosenBarangay = fake()->randomElement(['1-A', '2-A', '3-A', '4-A', '5-A', '6-A', '7-A', '8-A', '9-A', '10-A', '11-B', '12-B', '13-B', '14-B', '15-B', '16-B', '17-B', '18-B', '19-B', '20-B', '21-C', '22-C', '23-C', '24-C', '25-C', '26-C', '27-C', '28-C', '29-C', '30-C', '31-D', '32-D', '33-D', '34-D', '35-D', '36-D', '37-D', '38-D', '39-D', '40-D']);
                    if (Batch::where('implementations_id', $implementationId)->where('barangay_name', $choosenBarangay)->exists()) {
                        continue;
                    }
                    $barangay = $choosenBarangay;
                    break;
                }
                break;
            case 'Buhangin':
                while (true) {
                    $choosenBarangay = fake()->randomElement(['Acacia', 'Alfonso Angliongto Sr.', 'Buhangin Proper', 'Cabantian', 'Callawa', 'Communal', 'Indangan', 'Mandug', 'Pampanga', 'Sasa', 'Tigatto', 'Vicente Hizon Sr.', 'Waan']);
                    if (Batch::where('implementations_id', $implementationId)->where('barangay_name', $choosenBarangay)->exists()) {
                        continue;
                    }
                    $barangay = $choosenBarangay;
                    break;
                }
                break;
            case 'Paquibato':
                while (true) {
                    $choosenBarangay = fake()->randomElement([
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
                        "Tapak"
                    ]);
                    if (Batch::where('implementations_id', $implementationId)->where('barangay_name', $choosenBarangay)->exists()) {
                        continue;
                    }
                    $barangay = $choosenBarangay;
                    break;
                }
                break;
            case 'Baguio':
                while (true) {
                    $choosenBarangay = fake()->randomElement([
                        "Baguio Proper",
                        "Cadalian",
                        "Carmen",
                        "Gumalang",
                        "Malagos",
                        "Tambobong",
                        "Tawan-Tawan",
                        "Wines"
                    ]);
                    if (Batch::where('implementations_id', $implementationId)->where('barangay_name', $choosenBarangay)->exists()) {
                        continue;
                    }
                    $barangay = $choosenBarangay;
                    break;
                }
                break;
            case 'Calinan':
                while (true) {
                    $choosenBarangay = fake()->randomElement([
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
                        "Wangan"
                    ]);
                    if (Batch::where('implementations_id', $implementationId)->where('barangay_name', $choosenBarangay)->exists()) {
                        continue;
                    }
                    $barangay = $choosenBarangay;
                    break;
                }
                break;
            case 'Marilog':
                while (true) {
                    $choosenBarangay = fake()->randomElement([
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
                        "Tamugan"
                    ]);
                    if (Batch::where('implementations_id', $implementationId)->where('barangay_name', $choosenBarangay)->exists()) {
                        continue;
                    }
                    $barangay = $choosenBarangay;
                    break;
                }
                break;
            case 'Toril':
                while (true) {
                    $choosenBarangay = fake()->randomElement([
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
                        "Tungkalan"
                    ]);
                    if (Batch::where('implementations_id', $implementationId)->where('barangay_name', $choosenBarangay)->exists()) {
                        continue;
                    }
                    $barangay = $choosenBarangay;
                    break;
                }
                break;
            case 'Tugbok':
                while (true) {
                    $choosenBarangay = fake()->randomElement([
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
                    ]);
                    if (Batch::where('implementations_id', $implementationId)->where('barangay_name', $choosenBarangay)->exists()) {
                        continue;
                    }
                    $barangay = $choosenBarangay;
                    break;
                }
                break;

        }

        return $barangay;
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
