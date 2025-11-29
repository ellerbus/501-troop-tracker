<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Pickers;

use App\Models\Organization;
use App\Models\Trooper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganizationPickerControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_invoke_returns_only_moderated_organizations_when_flag_is_set(): void
    {
        // Arrange
        $moderated_org = Organization::factory()->create();
        $unmoderated_org = Organization::factory()->create();

        $moderator = Trooper::factory()
            ->asModerator()
            ->withAssignment($moderated_org, moderator: true)
            ->create();

        $this->actingAs($moderator);

        // Act
        $response = $this->get(route('pickers.organization', ['moderatored_only' => 'true']));

        // Assert
        $response->assertOk();
        $response->assertViewIs('pickers.organization');
        $response->assertViewHas('organizations', function ($organizations) use ($moderated_org, $unmoderated_org)
        {
            return $organizations->contains($moderated_org) && !$organizations->contains($unmoderated_org);
        });
    }

    public function test_invoke_returns_empty_array_when_flag_is_not_set(): void
    {
        // Arrange
        $organization = Organization::factory()->create();
        $trooper = Trooper::factory()
            ->withAssignment($organization, moderator: true)
            ->create();

        $this->actingAs($trooper);

        // Act
        $response = $this->get(route('pickers.organization'));

        // Assert
        $response->assertOk();
        $response->assertViewIs('pickers.organization');
        $response->assertViewHas('organizations', []);
    }

    public function test_invoke_returns_empty_array_for_non_moderator(): void
    {
        // Arrange
        $organization = Organization::factory()->create();
        $trooper = Trooper::factory()
            ->withAssignment($organization, member: true) // Not a moderator
            ->create();

        $this->actingAs($trooper);

        // Act
        $response = $this->get(route('pickers.organization', ['moderatored_only' => 'true']));

        // Assert
        $response->assertOk();
        $response->assertViewIs('pickers.organization');
        $response->assertViewHas('organizations', function ($organizations)
        {
            return $organizations->isEmpty();
        });
    }
}