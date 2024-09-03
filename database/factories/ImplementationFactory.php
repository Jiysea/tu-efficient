<?php

namespace Database\Factories;

use App\Models\Implementation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Nette\Utils\Random;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Implementation>
 */
class ImplementationFactory extends Factory
{
    protected static $counter = 1;
    protected $currentMinimumWage;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = Carbon::createFromDate(2023, 9, 1); // Start date (YYYY, MM, DD)
        $endDate = Carbon::createFromDate(2024, 9, 1); // End date (YYYY, MM, DD)

        $project_title = ucwords(fake()->words(mt_rand(1, 3), true));
        $budgetAmount = fake()->numberBetween(100000000, 250000000);
        $daysOfWork = fake()->randomElement([10, 15]);
        $total_slots = $this->calculateTotalSlots($budgetAmount, $daysOfWork);
        $district = fake()->randomElement(['Poblacion', 'Talomo', 'Agdao', 'Buhangin', 'Bunawan', 'Paquibato', 'Baguio', 'Calinan', 'Marilog', 'Toril', 'Tugbok']);
        $currentDate = $this->dateRandomizer($startDate, $endDate);

        return [
            'users_id' => 3,
            'project_num' => fake()->bothify('XII-DCFO-######'),
            'project_title' => $project_title,
            'purpose' => 'DUE TO DISPLACEMENT/DISADVANTAGE',
            'province' => 'Davao del Sur',
            'city_municipality' => 'Davao City',
            'district' => $district,
            'budget_amount' => $budgetAmount,
            'total_slots' => $total_slots,
            'days_of_work' => $daysOfWork,
            'created_at' => $currentDate,
            'updated_at' => $currentDate,
        ];
    }

    protected function dateRandomizer($startDate, $endDate)
    {
        $min = strtotime($startDate);
        $max = strtotime($endDate);

        // Generate a random timestamp between the min and max dates
        $randomTimestamp = mt_rand($min, $max);

        // Convert the timestamp back to a Carbon instance
        return Carbon::createFromTimestamp($randomTimestamp);
    }

    protected function calculateTotalSlots(int $budgetAmount, int $daysOfWork): int
    {
        $this->currentMinimumWage = intval(str_replace([',', '.'], '', number_format(floatval(config('settings.minimum_wage')), 2)));
        return intdiv($budgetAmount, $this->currentMinimumWage * $daysOfWork);
    }
}
