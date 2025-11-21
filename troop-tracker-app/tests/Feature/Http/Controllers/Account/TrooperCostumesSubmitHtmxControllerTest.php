<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Account;

use App\Enums\MembershipRole;
use App\Enums\MembershipStatus;
use App\Models\Costume;
use App\Models\Organization;
use App\Models\Trooper;
use Database\Seeders\OrganizationSeeder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrooperCostumesSubmitHtmxControllerTest extends TestCase
{
    use RefreshDatabase;

    private Trooper $trooper;
    private Organization $assigned_club;
    private Costume $assigned_costume;
    private Organization $unassigned_club;
    private Costume $unassigned_costume;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(OrganizationSeeder::class);

        $this->assigned_club = Organization::find(1);
        $this->assigned_costume = Costume::factory()->create(['organization_id' => 1]);

        $this->unassigned_club = Organization::find(2);
        $this->unassigned_costume = Costume::factory()->create(['organization_id' => 2]);

        $this->trooper = Trooper::factory()->create();
        $this->trooper->organizations()->attach($this->assigned_club->id, [
            'identifier' => 'TK000',
            'membership_status' => MembershipStatus::Active,
            'membership_role' => MembershipRole::Member
        ]);
    }

    public function test_invoke_adds_trooper_costume_for_valid_request(): void
    {
        // Arrange
        $this->assertDatabaseMissing('tt_trooper_costumes', [
            'trooper_id' => $this->trooper->id,
            'costume_id' => $this->assigned_costume->id,
        ]);

        $request_data = [
            'organization_id' => $this->assigned_club->id,
            'costume_id' => $this->assigned_costume->id,
        ];

        // Act
        $response = $this->actingAs($this->trooper)
            ->post(route('account.trooper-costumes-htmx'), $request_data);

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.account.trooper-costumes');
        $response->assertViewHas('trooper_costumes', function (Collection $trooper_costumes): bool
        {
            return $trooper_costumes->count() === 1
                && $trooper_costumes->first()->id === $this->assigned_costume->id;
        });

        $this->assertDatabaseHas('tt_trooper_costumes', [
            'trooper_id' => $this->trooper->id,
            'costume_id' => $this->assigned_costume->id,
        ]);
    }

    public function test_invoke_does_not_add_trooper_for_unassigned_club(): void
    {
        // Arrange
        $request_data = [
            'organization_id' => $this->unassigned_club->id,
            'costume_id' => $this->unassigned_costume->id,
        ];

        // Act
        $response = $this->actingAs($this->trooper)
            ->post(route('account.trooper-costumes-htmx'), $request_data);

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.account.trooper-costumes');
        $response->assertViewHas('trooper_costumes', function (Collection $trooper_costumes): bool
        {
            return $trooper_costumes->isEmpty();
        });

        $this->assertDatabaseMissing('tt_trooper_costumes', [
            'trooper_id' => $this->trooper->id,
            'costume_id' => $this->unassigned_costume->id,
        ]);
    }
}
