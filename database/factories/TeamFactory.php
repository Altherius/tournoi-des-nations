<?php

namespace Database\Factories;

use App\Enum\Region;
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
            'name' => fake()->country,
            'country_code' => fake()->countryCode,
            'region' => fake()->randomElement(Region::cases()),
            'rating' => fake()->numberBetween(500, 1500),
        ];
    }
}
