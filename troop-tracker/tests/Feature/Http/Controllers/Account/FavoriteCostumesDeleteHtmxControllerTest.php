<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Account;

use App\Enums\MembershipStatus;
use App\Models\Costume;
use App\Models\FavoriteCostume;
use App\Models\Trooper;
use Database\Seeders\ClubSeeder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoriteCostumesDeleteHtmxControllerTest extends TestCase
{
    use RefreshDatabase;

    private Trooper $trooper;
    private Costume $costume;
    private FavoriteCostume $favorite_costume;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(ClubSeeder::class);

        $this->trooper = Trooper::factory()->create(['pMando' => MembershipStatus::Member]);
        $this->costume = Costume::factory()->create(['club_id' => 1]);

        $this->favorite_costume = FavoriteCostume::factory()->create([
            'trooperid' => $this->trooper->id,
            'costumeid' => $this->costume->id,
        ]);
    }

    public function test_invoke_removes_favorite_costume_and_returns_view(): void
    {
        // Assert pre-condition
        $this->assertDatabaseHas('favorite_costumes', [
            'trooperid' => $this->trooper->id,
            'costumeid' => $this->costume->id,
        ]);

        // Act
        $response = $this->actingAs($this->trooper)
            ->delete(route('account.favorite-costumes-htmx'), [
                'costume_id' => $this->costume->id,
            ]);

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.account.favorite-costumes');
        $response->assertViewHas('favorites', function (Collection $favorites): bool
        {
            return $favorites->isEmpty();
        });

        $this->assertDatabaseMissing('favorite_costumes', [
            'trooperid' => $this->trooper->id,
            'costumeid' => $this->costume->id,
        ]);
    }
}
