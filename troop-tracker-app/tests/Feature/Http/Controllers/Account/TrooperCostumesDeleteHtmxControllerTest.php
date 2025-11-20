<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Account;

use App\Enums\MembershipStatus;
use App\Models\ClubCostume;
use App\Models\Trooper;
use App\Models\TrooperCostume;
use Database\Seeders\OrganizationSeeder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrooperCostumesDeleteHtmxControllerTest extends TestCase
{
    use RefreshDatabase;

    private Trooper $trooper;
    private ClubCostume $costume;
    private TrooperCostume $trooper_costume;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(OrganizationSeeder::class);

        $this->costume = ClubCostume::factory()->create([
            'club_id' => 1,
            'name' => 'Stormtrooper'
        ]);

        $this->trooper = Trooper::factory()->create();
        $this->trooper->organizations()->attach($this->costume->organization->id, [
            'identifier' => 'TK000',
            'status' => MembershipStatus::Member
        ]);

        $this->trooper_costume = TrooperCostume::factory()->create([
            'trooper_id' => $this->trooper->id,
            'club_costume_id' => $this->costume->id,
        ]);
    }

    public function test_invoke_removes_trooper_costume_and_returns_view(): void
    {
        // Assert pre-condition
        $this->assertDatabaseHas('tt_trooper_costumes', [
            'trooper_id' => $this->trooper->id,
            'club_costume_id' => $this->costume->id,
        ]);

        // Act
        $response = $this->actingAs($this->trooper)
            ->delete(route('account.trooper-costumes-htmx'), [
                'club_costume_id' => $this->costume->id,
            ]);

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.account.trooper-costumes');
        $response->assertViewHas('trooper_costumes', function (Collection $troopers): bool
        {
            return $troopers->isEmpty();
        });

        $this->assertDatabaseMissing('tt_trooper_costumes', [
            'trooper_id' => $this->trooper->id,
            'club_costume_id' => $this->costume->id,
        ]);
    }
}
