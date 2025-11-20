<?php

namespace Database\Factories;

use App\Enums\MembershipStatus;
use App\Models\Trooper;
use App\Models\TrooperUnit;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TrooperUnit>
 */
class TrooperUnitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            TrooperUnit::TROOPER_ID => Trooper::factory(),
            TrooperUnit::STATUS => MembershipStatus::Member,
            TrooperUnit::UNIT_ID => Unit::factory()
        ];
    }
}
