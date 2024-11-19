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
            'implementations_id' => 0,
            'batch_num' => '',
            'sector_title' => null,
            'district' => '',
            'barangay_name' => '',
            'slots_allocated' => 0,
            'submission_status' => 'unopened', // submitted unopened revalidate encoding
            'approval_status' => 'pending', // approved pending
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
