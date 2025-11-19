<?php

namespace Database\Factories;

use App\Models\Club;
use App\Models\ClubCostume;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClubCostume>ClubCostumeFactory
 */
class ClubCostumeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            ClubCostume::NAME => fake()->name(),
            ClubCostume::CLUB_ID => Club::factory()
        ];
    }
}
