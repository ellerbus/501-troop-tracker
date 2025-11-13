<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Account;

use App\Enums\MembershipStatus;
use App\Models\Club;
use App\Models\Costume;
use App\Models\Trooper;
use Database\Seeders\ClubSeeder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoriteCostumesSubmitHtmxControllerTest extends TestCase
{
    use RefreshDatabase;

    private Trooper $trooper;
    private Club $assigned_club;
    private Costume $assigned_club_costume;
    private Club $unassigned_club;
    private Costume $unassigned_club_costume;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(ClubSeeder::class);

        $this->trooper = Trooper::factory()->create(['pMando' => MembershipStatus::Member]);

        $this->assigned_club = Club::find(1);
        $this->assigned_club_costume = Costume::factory()->create(['club_id' => 1]);

        $this->unassigned_club = Club::find(2);
        $this->unassigned_club_costume = Costume::factory()->create(['club_id' => 2]);

        // Associate trooper with the assigned club
        $this->trooper->trooperClubs()->create([
            'club_id' => $this->assigned_club->id,
            'identifier' => 'TK0',
            'membership_status' => MembershipStatus::Member,
        ]);
    }

    public function test_invoke_adds_favorite_costume_for_valid_request(): void
    {
        // Arrange
        $this->assertDatabaseMissing('favorite_costumes', [
            'trooperid' => $this->trooper->id,
            'costumeid' => $this->assigned_club_costume->id,
        ]);

        $request_data = [
            'club_id' => $this->assigned_club->id,
            'costume_id' => $this->assigned_club_costume->id,
        ];

        // Act
        $response = $this->actingAs($this->trooper)
            ->post(route('account.favorite-costumes-htmx'), $request_data);

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.account.favorite-costumes');
        $response->assertViewHas('favorites', function (Collection $favorites): bool
        {
            return $favorites->count() === 1
                && $favorites->first()->costumeid === $this->assigned_club_costume->id;
        });

        $this->assertDatabaseHas('favorite_costumes', [
            'trooperid' => $this->trooper->id,
            'costumeid' => $this->assigned_club_costume->id,
        ]);
    }

    public function test_invoke_does_not_add_favorite_for_unassigned_club(): void
    {
        // Arrange
        $request_data = [
            'club_id' => $this->unassigned_club->id,
            'costume_id' => $this->unassigned_club_costume->id,
        ];

        // Act
        $response = $this->actingAs($this->trooper)
            ->post(route('account.favorite-costumes-htmx'), $request_data);

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.account.favorite-costumes');
        $response->assertViewHas('favorites', function (Collection $favorites): bool
        {
            return $favorites->isEmpty();
        });

        $this->assertDatabaseMissing('favorite_costumes', [
            'trooperid' => $this->trooper->id,
            'costumeid' => $this->unassigned_club_costume->id,
        ]);
    }
}
