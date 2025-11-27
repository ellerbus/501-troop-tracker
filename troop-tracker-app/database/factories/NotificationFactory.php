<?php

namespace Database\Factories;

use App\Enums\NotificationType;
use App\Models\Notification;
use App\Models\Organization;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notification>
 */
class NotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            Notification::TITLE => fake()->title(),
            Notification::MESSAGE => 'Hello',
            Notification::ORGANIZATION_ID => Organization::factory(),
            Notification::TYPE => NotificationType::Info,
            Notification::STARTS_AT => Carbon::now(),
            Notification::ENDS_AT => Carbon::now()->addDays(7),
        ];
    }

    public function active(): static
    {
        return $this->state(fn(array $attributes) => [
            Notification::STARTS_AT => Carbon::now()->subDay(),
            Notification::ENDS_AT => Carbon::now()->addDays(7),
        ]);
    }

    public function future(): static
    {
        return $this->state(fn(array $attributes) => [
            Notification::STARTS_AT => Carbon::now()->addDay(),
            Notification::ENDS_AT => Carbon::now()->addDays(7),
        ]);
    }

    public function past(): static
    {
        return $this->state(fn(array $attributes) => [
            Notification::STARTS_AT => Carbon::now()->subDays(8),
            Notification::ENDS_AT => Carbon::now()->subDays(7),
        ]);
    }

    public function withOrganization(Organization $organization): static
    {
        return $this->state(fn(array $attributes) => [
            Notification::ORGANIZATION_ID => $organization,
        ]);
    }
}
