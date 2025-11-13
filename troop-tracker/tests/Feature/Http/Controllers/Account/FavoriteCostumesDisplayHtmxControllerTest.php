<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Account;

use App\Enums\MembershipStatus;
use App\Models\Club;
use App\Models\Costume;
use App\Models\FavoriteCostume;
use App\Models\Trooper;
use App\Models\User;
use Database\Seeders\ClubSeeder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoriteCostumesDisplayHtmxControllerTest extends TestCase
{
    use RefreshDatabase;

    private Trooper $trooper;
    private Club $club;
    private Club $club2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->trooper = Trooper::factory()->create(['pMando' => MembershipStatus::Member]);

        $this->seed(ClubSeeder::class);

        $this->club = Club::find(1);
        $inactive_club = Club::factory()->create(['active' => false]);

        // // Associate trooper with clubs
        $this->trooper->trooperClubs()
            ->create([
                'club_id' => $this->club->id,
                'identifier' => 'TK0000',
                'membership_status' => MembershipStatus::Member,
            ]);
        $this->trooper->trooperClubs()
            ->create([
                'club_id' => $inactive_club->id,
                'identifier' => 'TK0100',
                'membership_status' => MembershipStatus::Member,
            ]);

        // Create costumes for the clubs
        Costume::factory()->count(2)->create(['club_id' => $this->club->id]);

        $costume = Costume::first();

        // Create a favorite costume for the trooper
        FavoriteCostume::factory()->create(['trooperid' => $this->trooper->id, 'costumeid' => $costume->id]);
    }

    public function test_invoke_without_club_id_returns_correct_view_and_data(): void
    {
        // Act
        $response = $this->actingAs($this->trooper)
            ->get(route('account.favorite-costumes-htmx'));

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.account.favorite-costumes');

        $response->assertViewHas('clubs', function (Collection $clubs): bool
        {
            return $clubs->count() === 1
                && $clubs->contains($this->club);
        });

        $response->assertViewHas('selected_club', null);
        $response->assertViewHas('costumes', []);
        $response->assertViewHas('favorites', function (Collection $favorites): bool
        {
            return $favorites->count() === 1;
        });
    }

    public function test_invoke_with_club_id_returns_correct_view_and_data(): void
    {
        // Arrange
        $expected_costumes = $this->club->costumes
            ->sortBy('costume')
            ->pluck('costume', 'id')
            ->toArray();

        // Act
        $response = $this->actingAs($this->trooper)
            ->get(route('account.favorite-costumes-htmx', ['club_id' => $this->club->id]));

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.account.favorite-costumes');

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
        $response->assertViewHas('favorites', function (Collection $favorites): bool
        {
            return $favorites->count() === 1;
        });
    }

    public function test_invoke_does_not_show_costumes_for_unassigned_club(): void
    {
        // Arrange
        $unassigned_club = Club::factory()->create(['active' => true]);

        // Act
        $response = $this->actingAs($this->trooper)
            ->get(route('account.favorite-costumes-htmx', ['club_id' => $unassigned_club->id]));

        // Assert
        $response->assertViewHas('selected_club', null);
        $response->assertViewHas('costumes', []);
    }
}
