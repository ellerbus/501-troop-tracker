<?php

namespace Database\Factories;

use App\Enums\MembershipStatus;
use App\Models\Region;
use App\Models\Trooper;
use App\Models\TrooperRegion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TrooperRegion>
 */
class TrooperRegionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            TrooperRegion::TROOPER_ID => Trooper::factory(),
            TrooperRegion::STATUS => MembershipStatus::Member,
            TrooperRegion::REGION_ID => Region::factory()
        ];
    }
}
