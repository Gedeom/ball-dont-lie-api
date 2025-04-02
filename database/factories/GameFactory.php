<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Game>
 */
class GameFactory extends Factory
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
            'date' => fake()->date(),
            'season' => fake()->year(),
            'status' => fake()->shuffleString(),
            'period' => fake()->randomDigit(),
            'time' => fake()->shuffleString(),
            'postseason' => fake()->boolean(),
            'home_team_score' => fake()->randomDigit(),
            'visitor_team_score' => fake()->randomDigit(),
            'datetime' => fake()->dateTime(),
            'visitor_team_id' => \App\Models\Team::factory(),
            'home_team_id' => \App\Models\Team::factory()
        ];
    }
}
