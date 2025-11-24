<?php

namespace Database\Factories;

use App\Enums\MembershipRole;
use App\Enums\MembershipStatus;
use App\Models\Costume;
use App\Models\Organization;
use App\Models\Trooper;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Trooper>
 */
class TrooperFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            Trooper::NAME => fake()->name(),
            Trooper::USERNAME => fake()->name(),
            Trooper::EMAIL => fake()->unique()->safeEmail(),
            Trooper::EMAIL_VERIFIED_AT => now(),
            Trooper::PASSWORD => static::$password ??= Hash::make('password'),
            Trooper::MEMBERSHIP_STATUS => MembershipStatus::Active,
            Trooper::MEMBERSHIP_ROLE => MembershipRole::Member,
            Trooper::REMEMBER_TOKEN => Str::random(10),
        ];
    }

    public function asActive(): static
    {
        return $this->withMembershipStatus(MembershipStatus::Active);
    }

    public function asRetired(): static
    {
        return $this->withMembershipStatus(MembershipStatus::Retired);
    }

    public function asPending(): static
    {
        return $this->withMembershipStatus(MembershipStatus::Pending);
    }

    private function withMemberShipStatus(MembershipStatus $status = MembershipStatus::Active): static
    {
        return $this->state(fn(array $attributes) => [
            'membership_status' => $status,
        ]);
    }

    public function withOrganization(Organization $organization, string $identifier = 'TK9999'): static
    {
        return $this->afterCreating(function (Trooper $trooper) use ($organization, $identifier)
        {
            $trooper->organizations()->attach($organization->id, [
                'identifier' => $identifier,
            ]);
        });
    }

    public function withAssignment(Organization $organization, MembershipStatus $status = MembershipStatus::Active, bool $notify = false): static
    {
        return $this->afterCreating(function (Trooper $trooper) use ($organization, $status, $notify)
        {
            $trooper->trooper_assignments()->create([
                'organization_id' => $organization->id,
                'membership_status' => $status,
                'notify' => $notify
            ]);
        });
    }

    public function withCostume(Costume $costume): static
    {
        return $this->afterCreating(function (Trooper $trooper) use ($costume)
        {
            $trooper->costumes()->attach($costume->id);
        });
    }
}
