<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Team>
 */
class TeamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'external_id' => fake()->unique()->randomNumber(),
            'conference' => fake()->state(),
            'division' => fake()->state(),
            'city' => fake()->city(),
            'name' => fake()->name(),
            'full_name' => fake()->lastName(),
            'abbreviation' => fake()->stateAbbr(),
        ];
    }
}
