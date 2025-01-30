<?php

namespace Database\Factories;

use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tournament>
 */
class TournamentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Tournoi n° '.fake()->numberBetween(1, 100),
            'gold_team_id' => Team::factory(),
            'silver_team_id' => Team::factory(),
            'bronze_team_id' => Team::factory(),
        ];
    }
}
