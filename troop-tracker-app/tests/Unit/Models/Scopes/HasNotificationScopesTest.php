<?php

declare(strict_types=1);

namespace Tests\Unit\Models\Scopes;

use App\Models\Notification;
use App\Models\Organization;
use App\Models\Trooper;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HasNotificationScopesTest extends TestCase
{
    use RefreshDatabase;

    public function test_active_scope_includes_current_notifications(): void
    {
        // Arrange
        Notification::factory()->active()->create(); // Has start and end dates
        Notification::factory()->create([            // Active without an end date
            'starts_at' => Carbon::now()->subDay(),
            'ends_at' => null,
        ]);
        // Act
        $result = Notification::active()->get();

        // Assert
        $this->assertCount(2, $result);
    }

    public function test_active_scope_excludes_future_notifications(): void
    {
        // Arrange
        Notification::factory()->future()->create(); // starts_at is in the future
        // Act
        $result = Notification::active()->count();

        // Assert
        $this->assertEquals(0, $result);
    }

    public function test_active_scope_excludes_past_notifications(): void
    {
        // Arrange
        Notification::factory()->past()->create(); // ends_at is in the past
        // Act
        $result = Notification::active()->count();

        // Assert
        $this->assertEquals(0, $result);
    }

    public function test_visible_to_scope_includes_notification_in_same_organization(): void
    {
        // Arrange
        $unit = Organization::factory()->unit()->create();
        $region = $unit->parent;
        $organization = $region->parent;

        $trooper = Trooper::factory()->withAssignment($unit, member: true)->create();
        Notification::factory()->withOrganization($organization)->create();

        // Act
        $result = Notification::visibleTo($trooper)->count();

        // Assert
        $this->assertEquals(1, $result);
    }

    public function test_visible_to_scope_includes_notification_in_child_organization(): void
    {
        // Arrange
        $unit = Organization::factory()->unit()->create();
        $region = $unit->parent;
        $organization = $region->parent;

        $trooper = Trooper::factory()->withAssignment($unit, member: true)->create();
        Notification::factory()->withOrganization($region)->create();

        // Act
        $result = Notification::visibleTo($trooper)->count();

        // Assert
        $this->assertEquals(1, $result);
    }

    public function test_visible_to_scope_includes_notification_in_grandchild_organization(): void
    {
        // Arrange
        $unit = Organization::factory()->unit()->create();
        $region = $unit->parent;
        $organization = $region->parent;

        $trooper = Trooper::factory()->withAssignment($unit, member: true)->create();
        Notification::factory()->withOrganization($unit)->create();

        // Act
        $result = Notification::visibleTo($trooper)->count();

        // Assert
        $this->assertEquals(1, $result);
    }

    public function test_visible_to_scope_includes_notification_from_parent_organization(): void
    {
        // Arrange

        $unit = Organization::factory()->unit()->create();
        $region = $unit->parent;
        $organization = $region->parent;

        $trooper = Trooper::factory()->withAssignment($unit, member: true)->create();
        Notification::factory()->withOrganization($organization)->create();

        // Act
        $result = Notification::visibleTo($trooper)->count();

        // Assert
        $this->assertEquals(1, $result);
    }

    public function test_visible_to_scope_excludes_notification_in_unrelated_organization(): void
    {
        // Arrange
        $unit = Organization::factory()->unit()->create();
        $unrelated_org = Organization::factory()->create();
        $trooper = Trooper::factory()->withAssignment($unit, member: true)->create();
        Notification::factory()->withOrganization($unrelated_org)->create();

        // Act
        $result = Notification::visibleTo($trooper)->count();

        // Assert
        $this->assertEquals(0, $result);
    }

    public function test_visible_to_scope_excludes_non_member_and_non_moderator(): void
    {
        // Arrange
        $unit = Organization::factory()->unit()->create();
        $region = $unit->parent;
        $organization = $region->parent;

        $trooper = Trooper::factory()->withAssignment($unit, member: false, moderator: false)->create();
        Notification::factory()->withOrganization($organization)->create();

        // Act
        $result = Notification::visibleTo($trooper)->count();

        // Assert
        $this->assertEquals(0, $result);
    }
}