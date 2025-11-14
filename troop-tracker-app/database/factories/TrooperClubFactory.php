<?php

namespace Database\Factories;

use App\Enums\MembershipStatus;
use App\Models\Trooper;
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
            'trooper_id' => Trooper::factory(),
            'identifier' => 'TK' . uniqid(),
            'status' => MembershipStatus::Member
        ];
    }
}
