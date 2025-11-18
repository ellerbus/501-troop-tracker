<?php

declare(strict_types=1);

namespace Tests\Feature\Observers;

use App\Models\Trooper;
use App\Models\TrooperAchievement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrooperObserverTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that creating a Trooper triggers the observer to create a TrooperAchievement.
     *
     * @return void
     */
    public function test_creating_a_trooper_creates_an_achievement_record(): void
    {
        // Arrange: Assert the achievements table is initially empty.
        $this->assertDatabaseCount('tt_trooper_achievements', 0);

        // Act: Create a new Trooper, which should trigger the observer.
        $trooper = Trooper::factory()->create();

        // Assert: Check that a new achievement record was created for the new trooper.
        $this->assertDatabaseCount('tt_trooper_achievements', 1);
        $this->assertDatabaseHas('tt_trooper_achievements', [TrooperAchievement::TROOPER_ID => $trooper->id]);
    }
}