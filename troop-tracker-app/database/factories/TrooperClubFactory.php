<?php

namespace Database\Factories;

use App\Enums\MembershipStatus;
use App\Models\Trooper;
use App\Models\TrooperClub;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TrooperClub>
 */
class TrooperClubFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            TrooperClub::TROOPER_ID => Trooper::factory(),
            TrooperClub::IDENTIFIER => 'TK' . uniqid(),
            TrooperClub::STATUS => MembershipStatus::Member
        ];
    }
}
