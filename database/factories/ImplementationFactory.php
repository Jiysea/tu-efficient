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
    protected $currentMinimumWage = config('wage.minimum_wage');
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = Carbon::createFromDate(2023, 07, 30); // Start date (YYYY, MM, DD)
        $endDate = Carbon::createFromDate(2024, 07, 30); // End date (YYYY, MM, DD)

        $budgetAmount = fake()->numberBetween(750000, 2000000);
        $daysOfWork = fake()->randomElement([10, 15]);
        $district = fake()->randomElement(['Agdao', 'Talomo', 'Bunawan', 'Poblacion', 'Buhangin']);
        $currentDate = $this->dateRandomizer($startDate, $endDate);

        return [
            'users_id' => 2,
            'project_num' => fake()->bothify('XII-DCFO-######'),
            'project_title' => 'Implementation Numero ' . static::$counter++,
            'purpose' => 'DUE TO DISPLACEMENT/DISADVANTAGE',
            'province' => 'Davao del Sur',
            'city_municipality' => 'Davao City',
            'district' => $district,
            'budget_amount' => $budgetAmount,
            'total_slots' => $this->calculateTotalSlots($budgetAmount, $daysOfWork),
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

        return intdiv($budgetAmount, $this->currentMinimumWage * $daysOfWork);
    }
}
