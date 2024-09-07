<?php

namespace Database\Factories;

use App\Models\Batch;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Batch>
 */
class BatchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'implementations_id' => rand(1, 3),
            'batch_num' => $this->batchNumberGenerator(),
            'barangay_name' => '',
            'slots_allocated' => 0,
            'submission_status' => 'unopened', // submitted unopened revalidate encoding
            'approval_status' => 'approved', // approved pending
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function batchNumberGenerator()
    {
        $number = fake()->bothify('DCFO-BN-######');
        $existingNumber = Batch::where('batch_num', $number)->first();

        while ($existingNumber) {
            if ($existingNumber->batch_num === $number) {
                $number = fake()->bothify('DCFO-BN-######');
                $existingNumber = $number;
            } else {
                break;
            }
        }

        return $number;
    }
}
