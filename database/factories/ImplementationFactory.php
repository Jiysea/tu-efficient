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
        $startDate = Carbon::createFromDate(2023, 1, 1); // Start date (YYYY, MM, DD)
        $endDate = Carbon::createFromDate(2024, 11, 1); // End date (YYYY, MM, DD)

        $budgetAmount = fake()->numberBetween(100000000, 250000000);
        $minimumWage = mt_rand(config('settings.minimum_wage', 481.00) * 100, 61800);
        $daysOfWork = fake()->randomElement([10, 15]);
        $sectoral = fake()->randomElement([0, 1]);
        $total_slots = $this->calculateTotalSlots($budgetAmount, $minimumWage, $daysOfWork);
        $currentDate = $this->dateRandomizer($startDate, $endDate);

        return [
            'users_id' => 1,
            'project_num' => fake()->bothify(config('settings.project_number_prefix', 'XII-DCFO-') . $currentDate->format('Y-') . '######'),
            'project_title' => '',
            'purpose' => 'DUE TO DISPLACEMENT/DISADVANTAGE',
            'province' => 'Davao del Sur',
            'city_municipality' => 'Davao City',
            'is_sectoral' => $sectoral,
            'budget_amount' => $budgetAmount,
            'minimum_wage' => $minimumWage,
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

    protected function calculateTotalSlots(int $budgetAmount, int $minimumWage, int $daysOfWork): int
    {
        $this->currentMinimumWage = intval(str_replace([',', '.'], '', number_format(floatval($minimumWage / 100), 2)));
        return intdiv($budgetAmount, $this->currentMinimumWage * $daysOfWork);
    }
}
