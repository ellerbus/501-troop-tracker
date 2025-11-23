<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Enums\MembershipRole;
use App\Enums\MembershipStatus;
use App\Models\Costume;
use App\Models\Organization;
use App\Models\Trooper;
use App\Models\TrooperAssignment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @see \App\Models\Trooper
 */
class TrooperTest extends TestCase
{
    use RefreshDatabase;

    public function test_casts_attributes_correctly(): void
    {
        // Arrange
        $trooper = Trooper::factory()->create([
            'membership_status' => MembershipStatus::Active,
            'membership_role' => MembershipRole::Member,
            'email' => 'Test.Email@Example.COM',
        ]);

        // Act
        $refreshed_trooper = $trooper->fresh();

        // Assert
        $this->assertInstanceOf(MembershipStatus::class, $refreshed_trooper->membership_status);
        $this->assertInstanceOf(MembershipRole::class, $refreshed_trooper->membership_role);
        $this->assertSame('test.email@example.com', $refreshed_trooper->email);
    }

    public function test_is_active_returns_correct_value(): void
    {
        // Arrange
        $active_trooper = Trooper::factory()->make(['membership_status' => MembershipStatus::Active]);
        $pending_trooper = Trooper::factory()->make(['membership_status' => MembershipStatus::Pending]);

        // Act & Assert
        $this->assertTrue($active_trooper->isActive());
        $this->assertFalse($pending_trooper->isActive());
    }

    public function test_is_denied_returns_correct_value(): void
    {
        // Arrange
        $denied_trooper = Trooper::factory()->make(['membership_status' => MembershipStatus::Denied]);
        $active_trooper = Trooper::factory()->make(['membership_status' => MembershipStatus::Active]);

        // Act & Assert
        $this->assertTrue($denied_trooper->isDenied());
        $this->assertFalse($active_trooper->isDenied());
    }

    public function test_attach_and_detach_costume_work_correctly(): void
    {
        // Arrange
        $trooper = Trooper::factory()->create();
        $costume = Costume::factory()->create();

        // Act (Attach)
        $trooper->attachCostume($costume->id);

        // Assert (Attach)
        $this->assertDatabaseHas('tt_trooper_costumes', [
            'trooper_id' => $trooper->id,
            'costume_id' => $costume->id,
        ]);

        // Act (Detach)
        $trooper->detachCostume($costume->id);

        // Assert (Detach)
        $this->assertDatabaseMissing('tt_trooper_costumes', [
            'trooper_id' => $trooper->id,
            'costume_id' => $costume->id,
        ]);
    }

    public function test_has_active_organization_status_returns_correct_value(): void
    {
        // Arrange
        $trooper_with_active = Trooper::factory()->create();
        $trooper_with_inactive = Trooper::factory()->create();
        $organization = Organization::factory()->create();

        $trooper_with_active->trooper_assignments()->create([
            'organization_id' => $organization->id,
            'membership_status' => MembershipStatus::Active,
        ]);

        $trooper_with_inactive->trooper_assignments()->create([
            'organization_id' => $organization->id,
            'membership_status' => MembershipStatus::Retired,
        ]);

        // Act & Assert
        $this->assertTrue($trooper_with_active->hasActiveOrganizationStatus());
        $this->assertFalse($trooper_with_inactive->hasActiveOrganizationStatus());
    }

    public function test_assigned_organizations_returns_active_assignments(): void
    {
        // Arrange
        $trooper = Trooper::factory()->create();
        $organization1 = Organization::factory()->create();
        $organization2 = Organization::factory()->create();
        $trooper->trooper_assignments()->create([
            'organization_id' => $organization1->id,
            'membership_status' => MembershipStatus::Active
        ]);
        $trooper->trooper_assignments()->create([
            'organization_id' => $organization2->id,
            'membership_status' => MembershipStatus::Retired
        ]);

        // Act
        $assignments = $trooper->assignedOrganizations(null);

        // Assert
        $this->assertCount(1, $assignments);
        $this->assertEquals(MembershipStatus::Active, $assignments->first()->membership_status);
    }
}
