<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Account;

use App\Models\Organization;
use App\Models\Region;
use App\Models\Trooper;
use App\Models\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationsSubmitHtmxControllerTest extends TestCase
{
    use RefreshDatabase;

    private Organization $organization1;
    private Organization $organization2;
    private Region $region1;
    private Region $region2;
    private Unit $unit1;
    private Unit $unit2;
    private Unit $unit3;

    /**
     * Set up the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->organization1 = Organization::factory()->create();
        $this->organization2 = Organization::factory()->create();
        $this->region1 = Region::factory()->for($this->organization1)->create();
        $this->region2 = Region::factory()->for($this->organization1)->create();
        $this->unit1 = Unit::factory()->for($this->region1)->create();
        $this->unit2 = Unit::factory()->for($this->region2)->create();
        $this->unit3 = Unit::factory()->for($this->region1)->create();
    }

    public function test_invoke_updates_notifications_and_returns_view_with_flash_message(): void
    {
        // Arrange
        $trooper = Trooper::factory()->create([
            'instant_notification' => 0,
            'attendance_notification' => 0,
            'command_staff_notification' => 0,
        ]);

        // Attach trooper to all relevant models to ensure pivots exist for updating
        $trooper->organizations()->attach($this->organization1->id, ['identifier' => 'TK-1']);
        $trooper->organizations()->attach($this->organization2->id, ['identifier' => 'BH-1']);
        $trooper->regions()->attach($this->region1->id);
        $trooper->regions()->attach($this->region2->id);
        $trooper->units()->attach($this->unit1->id);
        $trooper->units()->attach($this->unit2->id);
        $trooper->units()->attach($this->unit3->id);

        $request_data = [
            'instant_notification' => '1',
            'attendance_notification' => '1',
            'command_staff_notification' => '1',
            'organizations' => [
                $this->organization1->id => ['notification' => '1'], // Select Org 1
                // Org 2 is not sent, so it should be deselected
            ],
            'regions' => [
                $this->region1->id => ['notification' => '1'], // Select Region 1
                // Region 2 is not sent, so it should be deselected
            ],
            'units' => [
                $this->unit1->id => ['notification' => '1'], // Select Unit 1
                // Unit 2 & 3 are not sent, so they should be deselected
            ],
        ];

        // Act
        $response = $this->actingAs($trooper)->post(route('account.notifications-htmx'), $request_data);

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.account.notifications');
        $response->assertViewHas('organizations');

        $expected_flash_message = json_encode([
            'message' => 'Notifications updated successfully!',
            'type' => 'success',
        ]);
        $response->assertHeader('X-Flash-Message', $expected_flash_message);

        // Assert trooper base notifications were updated in the database
        $trooper->refresh();
        $this->assertEquals(1, $trooper->instant_notification);
        $this->assertEquals(1, $trooper->attendance_notification);
        $this->assertEquals(1, $trooper->command_staff_notification);

        // Assert pivot tables were updated in the database
        $this->assertDatabaseHas('tt_trooper_organizations', ['trooper_id' => $trooper->id, 'organization_id' => $this->organization1->id, 'notify' => 1]);
        $this->assertDatabaseHas('tt_trooper_organizations', ['trooper_id' => $trooper->id, 'organization_id' => $this->organization2->id, 'notify' => 0]);
        $this->assertDatabaseHas('tt_trooper_regions', ['trooper_id' => $trooper->id, 'region_id' => $this->region1->id, 'notify' => 1]);
        $this->assertDatabaseHas('tt_trooper_regions', ['trooper_id' => $trooper->id, 'region_id' => $this->region2->id, 'notify' => 0]);
        $this->assertDatabaseHas('tt_trooper_units', ['trooper_id' => $trooper->id, 'unit_id' => $this->unit1->id, 'notify' => 1]);
        $this->assertDatabaseHas('tt_trooper_units', ['trooper_id' => $trooper->id, 'unit_id' => $this->unit2->id, 'notify' => 0]);
        $this->assertDatabaseHas('tt_trooper_units', ['trooper_id' => $trooper->id, 'unit_id' => $this->unit3->id, 'notify' => 0]);

        // Assert the data returned to the view is correct
        $view_data = $response->getOriginalContent()->getData();
        $this->assertEquals(1, $view_data['instant_notification']);
        $this->assertEquals(1, $view_data['attendance_notification']);
        $this->assertEquals(1, $view_data['command_staff_notification']);

        $view_orgs = $view_data['organizations'];
        $view_org1 = $view_orgs->firstWhere('id', $this->organization1->id);
        $view_org2 = $view_orgs->firstWhere('id', $this->organization2->id);
        $view_region1 = $view_org1->regions->firstWhere('id', $this->region1->id);
        $view_region2 = $view_org1->regions->firstWhere('id', $this->region2->id);
        $view_unit1 = $view_region1->units->firstWhere('id', $this->unit1->id);
        $view_unit3 = $view_region1->units->firstWhere('id', $this->unit3->id);

        $this->assertTrue($view_org1->selected);
        $this->assertFalse($view_org2->selected);
        $this->assertTrue($view_region1->selected);
        $this->assertFalse($view_region2->selected);
        $this->assertTrue($view_unit1->selected);
        $this->assertFalse($view_unit3->selected);
    }
}