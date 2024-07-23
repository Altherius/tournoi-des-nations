<?php

namespace Database\Factories;

use App\Models\Team;
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
            'hosting_team_id' => Team::factory(),
            'receiving_team_id' => Team::factory(),
            'winning_team_id' => null,
            'host_score_1' => fake()->numberBetween(0, 5),
            'guest_score_1' => fake()->numberBetween(0, 5),
            'host_score_2' => fake()->numberBetween(0, 5),
            'guest_score_2' => fake()->numberBetween(0, 5),
        ];
    }
}
