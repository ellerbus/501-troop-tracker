<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Dashboard;

use App\Models\Club;
use App\Models\ClubCostume;
use App\Models\Event;
use App\Models\EventTrooper;
use App\Models\Trooper;
use App\Models\TrooperAchievement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardDisplayControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_invoke_displays_dashboard_for_authenticated_user(): void
    {
        // Arrange
        $user = Trooper::factory()->create();
        $this->actingAs($user);

        $club = Club::factory()->create();
        $costume = ClubCostume::factory()->for($club)->create();
        $event = Event::factory()->closed()->create();
        EventTrooper::factory()->for($event)->for($user)->create([
            'club_costume_id' => $costume->id
        ]);

        // Act
        $response = $this->get(route('dashboard.display'));

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.dashboard.display');
        $response->assertViewHas('trooper', function (Trooper $trooper) use ($user)
        {
            return $trooper->id === $user->id;
        });
        $response->assertViewHas('total_troops_by_club', function ($collection)
        {
            return $collection->count() === 1 && $collection->first()->troop_count === 1;
        });
        $response->assertViewHas('total_troops_by_costume', function ($collection)
        {
            return $collection->count() === 1 && $collection->first()->troop_count === 1;
        });
    }

    public function test_invoke_displays_dashboard_for_another_trooper(): void
    {
        // Arrange
        $user = Trooper::factory()->create();
        $this->actingAs($user);

        $other_trooper = Trooper::factory()->create();
        $club = Club::factory()->create();
        $costume = ClubCostume::factory()->for($club)->create();
        $event = Event::factory()->closed()->create();
        EventTrooper::factory()->for($event)->for($other_trooper)->create([
            'club_costume_id' => $costume->id
        ]);

        // Act
        $response = $this->get(route('dashboard.display', ['trooper_id' => $other_trooper->id]));

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.dashboard.display');
        $response->assertViewHas('trooper', function (Trooper $trooper) use ($other_trooper)
        {
            return $trooper->id === $other_trooper->id;
        });
    }

    public function test_invoke_redirects_if_trooper_not_found(): void
    {
        // Arrange
        $user = Trooper::factory()->create();
        $this->actingAs($user);

        // Act
        $response = $this->get(route('dashboard.display', ['trooper_id' => 999]));

        // Assert
        $response->assertNotFound();
    }

    public function test_invoke_correctly_calculates_troop_counts(): void
    {
        // Arrange
        $user = Trooper::factory()->create();
        $this->actingAs($user);

        $club1 = Club::factory()->create();
        $costume1 = ClubCostume::factory()->for($club1)->create();
        $costume2 = ClubCostume::factory()->for($club1)->create();

        $club2 = Club::factory()->create();
        $costume3 = ClubCostume::factory()->for($club2)->create();

        EventTrooper::factory()->for(Event::factory()->closed()->create())->for($user)->create([
            'club_costume_id' => $costume1->id
        ]);
        EventTrooper::factory()->for(Event::factory()->closed()->create())->for($user)->create([
            'club_costume_id' => $costume3->id
        ]);

        // Act
        $response = $this->get(route('dashboard.display'));

        // Assert
        $response->assertViewHas('total_troops_by_club', function ($collection)
        {
            return $collection->count() === 2;
        });
        $response->assertViewHas('total_troops_by_costume', function ($collection)
        {
            return $collection->count() === 2;
        });
    }
}
