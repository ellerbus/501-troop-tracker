<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Account;

use App\Enums\MembershipStatus;
use App\Models\Club;
use App\Models\ClubCostume;
use App\Models\Trooper;
use App\Models\TrooperCostume;
use Database\Seeders\ClubSeeder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrooperCostumesDisplayHtmxControllerTest extends TestCase
{
    use RefreshDatabase;

    private Trooper $trooper;
    private ClubCostume $costume;
    private Club $club;
    private Club $club2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(ClubSeeder::class);

        $this->club = Club::find(1);
        $inactive_club = Club::factory()->create(['active' => false]);

        $this->costume = ClubCostume::factory()->create([
            'club_id' => $this->club->id,
            'name' => 'Stormtrooper'
        ]);

        $this->trooper = Trooper::factory()->create();
        $this->trooper->clubs()->attach($this->costume->club->id, [
            'identifier' => 'TK000',
            'status' => MembershipStatus::Member
        ]);
        $this->trooper->clubs()->attach($inactive_club->id, [
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

        $response->assertViewHas('clubs', function (Collection $clubs): bool
        {
            return $clubs->count() === 1
                && $clubs->contains($this->club);
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
        $expected_costumes = $this->club->club_costumes
            ->sortBy('name')
            ->pluck('name', 'id')
            ->toArray();

        // Act
        $response = $this->actingAs($this->trooper)
            ->get(route('account.trooper-costumes-htmx', ['club_id' => $this->club->id]));

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.account.trooper-costumes');

        $response->assertViewHas('clubs', function (Collection $clubs): bool
        {
            return $clubs->count() === 1
                && $clubs->contains($this->club);
        });

        $response->assertViewHas('selected_club', function (Club $selected_club): bool
        {
            return $selected_club->is($this->club);
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
        $unassigned_club = Club::factory()->create(['active' => true]);

        // Act
        $response = $this->actingAs($this->trooper)
            ->get(route('account.trooper-costumes-htmx', ['club_id' => $unassigned_club->id]));

        // Assert
        $response->assertViewHas('selected_club', null);
        $response->assertViewHas('costumes', []);
    }
}
