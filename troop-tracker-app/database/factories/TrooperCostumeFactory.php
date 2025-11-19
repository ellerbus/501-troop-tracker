<?php

namespace Database\Factories;

use App\Models\ClubCostume;
use App\Models\Trooper;
use App\Models\TrooperCostume;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TrooperCostume>TrooperCostumeFactory
 */
class TrooperCostumeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            TrooperCostume::TROOPER_ID => Trooper::factory(),
            TrooperCostume::CLUB_COSTUME_ID => ClubCostume::factory(),
        ];
    }
}
