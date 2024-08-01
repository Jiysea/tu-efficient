<?php

namespace Database\Factories;

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
            'batch_num' => fake()->bothify('DCFO-BN-######'),
            'barangay_name' => '',
            'slots_allocated' => 0,
            'submission_status' => 'UNOPENED',
            'approval_status' => 'PENDING',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
