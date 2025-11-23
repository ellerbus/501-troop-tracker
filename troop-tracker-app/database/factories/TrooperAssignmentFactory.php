<?php

namespace Database\Factories;

use App\Enums\MembershipRole;
use App\Enums\MembershipStatus;
use App\Models\Organization;
use App\Models\Trooper;
use App\Models\TrooperAssignment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TrooperAssignment>
 */
class TrooperAssignmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            TrooperAssignment::TROOPER_ID => Trooper::factory(),
            TrooperAssignment::MEMBERSHIP_STATUS => MembershipStatus::Active,
            TrooperAssignment::MEMBERSHIP_ROLE => MembershipRole::Member,
            TrooperAssignment::ORGANIZATION_ID => Organization::factory(),
        ];
    }

    public function active(): static
    {
        return $this->state(fn(array $attributes) => [
            'membership_status' => MembershipStatus::Active,
        ]);
    }

    public function retired(): static
    {
        return $this->state(fn(array $attributes) => [
            'membership_status' => MembershipStatus::Retired,
        ]);
    }

}
