<?php

declare(strict_types=1);

namespace Tests\Unit\Policies;

use App\Enums\MembershipRole;
use App\Enums\MembershipStatus;
use App\Models\Organization;
use App\Models\Trooper;
use App\Policies\TrooperPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class TrooperPolicyTest extends TestCase
{
    use RefreshDatabase;

    private TrooperPolicy $subject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->subject = new TrooperPolicy();
    }

    public static function moderatorPermissionProvider(): array
    {
        return [
            'viewAny' => ['viewAny'],
            'view' => ['view', Trooper::factory()],
            'update' => ['update', Trooper::factory()],
            'delete' => ['delete', Trooper::factory()],
            'restore' => ['restore', Trooper::factory()],
            'forceDelete' => ['forceDelete', Trooper::factory()],
        ];
    }

    #[DataProvider('moderatorPermissionProvider')]
    public function test_moderator_permissions_grant_access(string $method): void
    {
        // Arrange
        $admin = Trooper::factory()->create(['membership_role' => MembershipRole::Admin]);
        $moderator = Trooper::factory()->create(['membership_role' => MembershipRole::Moderator]);
        $args = $method === 'viewAny' ? [$admin] : [$admin, Trooper::factory()->create()];

        // Act & Assert
        $this->assertTrue($this->subject->{$method}(...$args));
        $args[0] = $moderator;
        $this->assertTrue($this->subject->{$method}(...$args));
    }

    #[DataProvider('moderatorPermissionProvider')]
    public function test_member_permissions_deny_access(string $method): void
    {
        // Arrange
        $member = Trooper::factory()->create(['membership_role' => MembershipRole::Member]);
        $args = $method === 'viewAny' ? [$member] : [$member, Trooper::factory()->create()];

        // Act & Assert
        $this->assertFalse($this->subject->{$method}(...$args));
    }

    public function test_create_is_always_denied(): void
    {
        // Arrange
        $admin = Trooper::factory()->create(['membership_role' => MembershipRole::Admin]);
        $moderator = Trooper::factory()->create(['membership_role' => MembershipRole::Moderator]);
        $member = Trooper::factory()->create(['membership_role' => MembershipRole::Member]);

        // Act & Assert
        $this->assertFalse($this->subject->create($admin));
        $this->assertFalse($this->subject->create($moderator));
        $this->assertFalse($this->subject->create($member));
    }

    public function test_approve_is_granted_for_admin(): void
    {
        // Arrange
        $admin = Trooper::factory()->create(['membership_role' => MembershipRole::Admin]);
        $candidate = Trooper::factory()->create();

        // Act & Assert
        $this->assertTrue($this->subject->approve($admin, $candidate));
    }

    public function test_approve_is_denied_for_member(): void
    {
        // Arrange
        $member = Trooper::factory()->create(['membership_role' => MembershipRole::Member]);
        $candidate = Trooper::factory()->create();

        // Act & Assert
        $this->assertFalse($this->subject->approve($member, $candidate));
    }

    public function test_approve_is_denied_for_moderator_with_no_shared_scope(): void
    {
        // Arrange
        $moderator = Trooper::factory()->create(['membership_role' => MembershipRole::Moderator]);
        $candidate = Trooper::factory()->create(['membership_status' => MembershipStatus::Pending]);
        $organization = Organization::factory()->create();

        $moderator->trooper_assignments()->create([
            'organization_id' => $organization->id,
            'membership_role' => MembershipRole::Moderator,
            'membership_status' => MembershipStatus::Active,
        ]);

        // Act & Assert
        $this->assertFalse($this->subject->approve($moderator, $candidate));
    }

    public function test_approve_is_granted_for_moderator_with_shared_organization(): void
    {
        // Arrange
        $moderator = Trooper::factory()->create(['membership_role' => MembershipRole::Moderator]);
        $candidate = Trooper::factory()->create(['membership_status' => MembershipStatus::Pending]);
        $organization = Organization::factory()->create();

        $assignment = $moderator->trooper_assignments()->create([
            'organization_id' => $organization->id,
            'membership_role' => MembershipRole::Moderator,
            'membership_status' => MembershipStatus::Active,
        ]);
        $candidate->trooper_assignments()->create(['organization_id' => $organization->id]);

        // Act & Assert
        $this->assertTrue($this->subject->approve($moderator, $candidate));
    }

    public function test_approve_is_granted_for_moderator_with_shared_region(): void
    {
        // Arrange
        $moderator = Trooper::factory()->create(['membership_role' => MembershipRole::Moderator]);
        $candidate = Trooper::factory()->create(['membership_status' => MembershipStatus::Pending]);
        $organization = Organization::factory()->create();

        $assignment = $moderator->trooper_assignments()->create([
            'organization_id' => $organization->id,
            'membership_role' => MembershipRole::Moderator,
            'membership_status' => MembershipStatus::Active,
        ]);
        $candidate->trooper_assignments()->create(['organization_id' => $organization->id]);

        // Act & Assert
        $this->assertTrue($this->subject->approve($moderator, $candidate));
    }

    public function test_approve_is_granted_for_moderator_with_shared_unit(): void
    {
        // Arrange
        $moderator = Trooper::factory()->create(['membership_role' => MembershipRole::Moderator]);
        $candidate = Trooper::factory()->create(['membership_status' => MembershipStatus::Pending]);
        $organization = Organization::factory()->create();

        $assignment = $moderator->trooper_assignments()->create([
            'organization_id' => $organization->id,
            'membership_role' => MembershipRole::Moderator,
            'membership_status' => MembershipStatus::Active,
        ]);
        $candidate->trooper_assignments()->create(['organization_id' => $organization->id]);

        // Act & Assert
        $this->assertTrue($this->subject->approve($moderator, $candidate));
    }
}
