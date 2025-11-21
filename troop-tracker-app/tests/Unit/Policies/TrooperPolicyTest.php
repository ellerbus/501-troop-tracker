<?php

declare(strict_types=1);

namespace Tests\Unit\Policies;

use App\Enums\MembershipRole;
use App\Enums\MembershipStatus;
use App\Models\Organization;
use App\Models\Region;
use App\Models\Trooper;
use App\Models\Unit;
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
            'view' => ['view'],
            'update' => ['update'],
            'delete' => ['delete'],
            'restore' => ['restore'],
            'forceDelete' => ['forceDelete'],
        ];
    }

    #[DataProvider('moderatorPermissionProvider')]
    public function test_moderator_permissions_grant_access(string $method): void
    {
        // Arrange
        $model = $method == 'viewAny' ? null : Trooper::factory()->create();

        $admin = Trooper::factory()->create(['membership_role' => MembershipRole::Admin]);
        $moderator = Trooper::factory()->create(['membership_role' => MembershipRole::Moderator]);
        $args = $model ? [$admin, $model] : [$admin];

        // Act & Assert
        $this->assertTrue($this->subject->{$method}(...$args));
        $args[0] = $moderator;
        $this->assertTrue($this->subject->{$method}(...$args));
    }

    #[DataProvider('moderatorPermissionProvider')]
    public function test_member_permissions_deny_access(string $method): void
    {
        // Arrange
        $model = $method == 'viewAny' ? null : Trooper::factory()->create();

        $member = Trooper::factory()->create(['membership_role' => MembershipRole::Member]);
        $args = $model ? [$member, $model] : [$member];

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
        $candidate = Trooper::factory()->create();
        $organization = Organization::factory()->create();
        $moderator->organizations()->attach($organization->id, [
            'membership_role' => MembershipRole::Moderator,
            'membership_status' => MembershipStatus::Active,
            'identifier' => ''
        ]);

        // Act & Assert
        $this->assertFalse($this->subject->approve($moderator, $candidate));
    }

    public function test_approve_is_granted_for_moderator_with_shared_organization(): void
    {
        // Arrange
        $moderator = Trooper::factory()->create(['membership_role' => MembershipRole::Moderator]);
        $candidate = Trooper::factory()->create();
        $organization = Organization::factory()->create();

        $moderator->organizations()->attach($organization->id, [
            'membership_role' => MembershipRole::Moderator,
            'membership_status' => MembershipStatus::Active,
            'identifier' => ''
        ]);
        $candidate->organizations()->attach($organization->id, ['identifier' => '']);

        // Act & Assert
        $this->assertTrue($this->subject->approve($moderator, $candidate));
    }

    public function test_approve_is_granted_for_moderator_with_shared_region(): void
    {
        // Arrange
        $moderator = Trooper::factory()->create(['membership_role' => MembershipRole::Moderator]);
        $candidate = Trooper::factory()->create();
        $region = Region::factory()->create();

        $moderator->regions()->attach($region->id, [
            'membership_role' => MembershipRole::Moderator,
            'membership_status' => MembershipStatus::Active
        ]);
        $candidate->regions()->attach($region->id);

        // Act & Assert
        $this->assertTrue($this->subject->approve($moderator, $candidate));
    }

    public function test_approve_is_granted_for_moderator_with_shared_unit(): void
    {
        // Arrange
        $moderator = Trooper::factory()->create(['membership_role' => MembershipRole::Moderator]);
        $candidate = Trooper::factory()->create();
        $unit = Unit::factory()->create();

        $moderator->units()->attach($unit->id, [
            'membership_role' => MembershipRole::Moderator,
            'membership_status' => MembershipStatus::Active
        ]);
        $candidate->units()->attach($unit->id);

        // Act & Assert
        $this->assertTrue($this->subject->approve($moderator, $candidate));
    }
}

