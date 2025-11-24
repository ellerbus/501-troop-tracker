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

class TrooperCostumesDeleteHtmxControllerTest extends TestCase
{
    use RefreshDatabase;

    private Trooper $trooper;
    private Costume $costume;
    private Organization $organization;
    private TrooperCostume $trooper_costume;

    protected function setUp(): void
    {
        parent::setUp();

        $this->organization = Organization::factory()
            ->withCostume('Stormtrooper')
            ->create();

        $this->costume = $this->organization->costumes()->first();

        $this->trooper = Trooper::factory()
            ->withOrganization($this->organization, 'TK-1')
            ->withCostume($this->costume)
            ->withAssignment($this->organization, notify: true)
            ->create();
    }

    public function test_invoke_removes_trooper_costume_and_returns_view(): void
    {
        // Assert pre-condition
        $this->assertDatabaseHas('tt_trooper_costumes', [
            'trooper_id' => $this->trooper->id,
            'costume_id' => $this->costume->id,
        ]);

        // Act
        $response = $this->actingAs($this->trooper)
            ->delete(route('account.trooper-costumes-htmx'), [
                'costume_id' => $this->costume->id,
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
            'costume_id' => $this->costume->id,
        ]);
    }
}
