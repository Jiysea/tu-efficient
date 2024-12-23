<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Code>
 */
class CodeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'batches_id' => 1,
            'access_code' => fake()->bothify('?##??#?#'),
            'is_accessible' => 'no',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
