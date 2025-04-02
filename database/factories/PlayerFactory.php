<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Player>
 */
class PlayerFactory extends Factory
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
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'position' => fake()->randomLetter(),
            'height' => fake()->randomNumber(),
            'weight' => fake()->randomDigit(),
            'jersey_number' => fake()->randomDigit(),
            'college' => fake()->name(),
            'country' => fake()->country(),
            'draft_year' => fake()->year(),
            'draft_round' => fake()->randomDigit(),
            'draft_number' => fake()->randomDigit(),
            'team_id' => \App\Models\Team::factory()
        ];
    }
}
