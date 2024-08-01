<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{

    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'first_name' => fake()->firstName(),
            'middle_name' => fake()->optional($weight = 0.8, $default = "-")->lastName(),
            'last_name' => fake()->lastName(),
            'extension_name' => fake()->optional($weight = 0.1, $default = "-")->suffix(),
            'email' => fake()->freeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'contact_num' => fake()->numerify('+639#########'),
            'regional_office' => 'Region XI',
            'field_office' => 'Davao City',
            'user_type' => 'Coordinator',
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    // public function unverified(): static
    // {
    //     return $this->state(fn(array $attributes) => [
    //         'email_verified_at' => null,
    //     ]);
    // }
}
