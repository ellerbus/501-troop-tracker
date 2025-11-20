<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Account;

use App\Enums\MembershipStatus;
use App\Models\Organization;
use App\Models\ClubCostume;
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
    private ClubCostume $costume;
    private Organization $organization;
    private Organization $club2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(OrganizationSeeder::class);

        $this->organization = Organization::find(1);
        $inactive_club = Organization::factory()->create(['active' => false]);

        $this->costume = ClubCostume::factory()->create([
            'club_id' => $this->organization->id,
            'name' => 'Stormtrooper'
        ]);

        $this->trooper = Trooper::factory()->create();
        $this->trooper->organizations()->attach($this->costume->organization->id, [
            'identifier' => 'TK000',
            'status' => MembershipStatus::Member
        ]);
        $this->trooper->organizations()->attach($inactive_club->id, [
            'identifier' => 'TK001',
            'status' => MembershipStatus::Member
        ]);

        // Create a trooper costume for the trooper
        TrooperCostume::factory()->create(['trooper_id' => $this->trooper->id, 'club_costume_id' => $this->costume->id]);
    }

    public function test_invoke_without_club_id_returns_correct_view_and_data(): void
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

        $response->assertViewHas('selected_club', null);
        $response->assertViewHas('costumes', []);
        $response->assertViewHas('trooper_costumes', function (Collection $troopers): bool
        {
            return $troopers->count() === 1;
        });
    }

    public function test_invoke_with_club_id_returns_correct_view_and_data(): void
    {
        // Arrange
        $expected_costumes = $this->organization->club_costumes
            ->sortBy('name')
            ->pluck('name', 'id')
            ->toArray();

        // Act
        $response = $this->actingAs($this->trooper)
            ->get(route('account.trooper-costumes-htmx', ['club_id' => $this->organization->id]));

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.account.trooper-costumes');

        $response->assertViewHas('organizations', function (Collection $organizations): bool
        {
            return $organizations->count() === 1
                && $organizations->contains($this->organization);
        });

        $response->assertViewHas('selected_club', function (Organization $selected_club): bool
        {
            return $selected_club->is($this->organization);
        });

        $response->assertViewHas('costumes', $expected_costumes);
        $response->assertViewHas('trooper_costumes', function (Collection $troopers): bool
        {
            return $troopers->count() === 1;
        });
    }

    public function test_invoke_does_not_show_costumes_for_unassigned_club(): void
    {
        // Arrange
        $unassigned_club = Organization::factory()->create(['active' => true]);

        // Act
        $response = $this->actingAs($this->trooper)
            ->get(route('account.trooper-costumes-htmx', ['club_id' => $unassigned_club->id]));

        // Assert
        $response->assertViewHas('selected_club', null);
        $response->assertViewHas('costumes', []);
    }
}
