<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Account;

use App\Enums\MembershipRole;
use App\Enums\MembershipStatus;
use App\Models\Costume;
use App\Models\Organization;
use App\Models\Trooper;
use App\Models\TrooperCostume;
use Database\Seeders\OrganizationSeeder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrooperCostumesDisplayHtmxControllerTest extends TestCase
{
    use RefreshDatabase;

    private Trooper $trooper;
    private Costume $costume;
    private Organization $organization;
    private Organization $organization2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(OrganizationSeeder::class);

        $this->organization = Organization::find(1);
        $inactive_organization = Organization::factory()->create(['active' => false]);

        $this->costume = Costume::factory()->create([
            'organization_id' => $this->organization->id,
            'name' => 'Stormtrooper'
        ]);

        $this->trooper = Trooper::factory()->create();
        $this->trooper->organizations()->attach($this->costume->organization->id, [
            'identifier' => 'TK000',
            'membership_status' => MembershipStatus::Active,
            'membership_role' => MembershipRole::Member
        ]);
        $this->trooper->organizations()->attach($inactive_organization->id, [
            'identifier' => 'TK001',
            'membership_status' => MembershipStatus::Active,
            'membership_role' => MembershipRole::Member
        ]);

        // Create a trooper costume for the trooper
        TrooperCostume::factory()->create(['trooper_id' => $this->trooper->id, 'costume_id' => $this->costume->id]);
    }

    public function test_invoke_without_organization_id_returns_correct_view_and_data(): void
    {
        // Act
        $response = $this->actingAs($this->trooper)
            ->get(route('account.trooper-costumes-htmx'));

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.account.trooper-costumes');

        $response->assertViewHas('organizations', function (Collection $organizations): bool
        {
            return $organizations->count() === 1
                && $organizations->contains($this->organization);
        });

        $response->assertViewHas('selected_organization', null);
        $response->assertViewHas('costumes', []);
        $response->assertViewHas('trooper_costumes', function (Collection $troopers): bool
        {
            return $troopers->count() === 1;
        });
    }

    public function test_invoke_with_organization_id_returns_correct_view_and_data(): void
    {
        // Arrange
        //  the costume is assigned to the trooper
        //  and should not be included
        $expected_costumes = [];

        // Act
        $response = $this->actingAs($this->trooper)
            ->get(route('account.trooper-costumes-htmx', ['organization_id' => $this->organization->id]));

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.account.trooper-costumes');

        $response->assertViewHas('organizations', function (Collection $organizations): bool
        {
            return $organizations->count() === 1
                && $organizations->contains($this->organization);
        });

        $response->assertViewHas('selected_organization', function (Organization $selected_organization): bool
        {
            return $selected_organization->is($this->organization);
        });

        $response->assertViewHas('costumes', $expected_costumes);
        $response->assertViewHas('trooper_costumes', function (Collection $troopers): bool
        {
            return $troopers->count() === 1;
        });
    }

    public function test_invoke_does_not_show_costumes_for_unassigned_organization(): void
    {
        // Arrange
        $unassigned_organization = Organization::factory()->create(['active' => true]);

        // Act
        $response = $this->actingAs($this->trooper)
            ->get(route('account.trooper-costumes-htmx', ['organization_id' => $unassigned_organization->id]));

        // Assert
        $response->assertViewHas('selected_organization', null);
        $response->assertViewHas('costumes', []);
    }
}
