<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Account;

use App\Models\Organization;
use App\Models\Region;
use App\Models\Trooper;
use App\Models\Unit;
use Database\Seeders\OrganizationSeeder;
use Database\Seeders\RegionSeeder;
use Database\Seeders\UnitSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationsDisplayHtmxControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed(OrganizationSeeder::class);
        $this->seed(RegionSeeder::class);
        $this->seed(UnitSeeder::class);
    }

    public function test_unauthenticated_trooper_is_redirected_to_login(): void
    {
        // Act
        $response = $this->get(route('account.notifications-htmx'));

        // Assert
        $response->assertRedirect(route('auth.login'));
    }

    public function test_authenticated_trooper_can_view_their_notification_settings(): void
    {
        // Arrange: Create a trooper with specific notification settings
        $trooper = Trooper::factory()->create([
            'instant_notification' => 1,
            'attendance_notification' => 0,
            'command_staff_notification' => 1,
        ]);

        // Attach trooper to organizations, regions, and units with notification settings
        $trooper->organizations()->attach(1, [
            'notify' => 1,
            'identifier' => 'TKID',
        ]);
        $trooper->regions()->attach(1, [
            'notify' => 1,
        ]);
        $trooper->units()->attach(1, [
            'notify' => 1,
        ]);

        // Act: Make a request to the controller as the authenticated trooper
        $response = $this->actingAs($trooper)->get(route('account.notifications-htmx'));

        // Assert: Check for a successful response and correct view
        $response->assertOk();
        $response->assertViewIs('pages.account.notifications');

        // Assert: Check that the basic notification settings are passed correctly
        $response->assertViewHasAll([
            'instant_notification' => 1,
            'attendance_notification' => 0,
            'command_staff_notification' => 1,
        ]);

        // Assert: Check that the organization, region, and unit data is processed correctly
        $response->assertViewHas('organizations', function ($organizations)
        {
            // Organization 1: Should be selected
            $view_organization_1 = $organizations->firstWhere('id', 1);
            $this->assertNotNull($view_organization_1, 'Organization 1 not found in view data.');
            $this->assertTrue($view_organization_1->selected, 'Expected Organization 1 to be selected.');

            // Organization 2: Should NOT be selected (trooper not attached with notify=1)
            $view_organization_2 = $organizations->firstWhere('id', 2);
            if ($view_organization_2)
            { // It might not be in the collection if no regions/units are active
                $this->assertFalse($view_organization_2->selected, 'Expected Organization 2 to NOT be selected.');
            }

            // Region 1 (under Organization 1): Should be selected
            $view_region_1 = $view_organization_1->regions->firstWhere('id', 1);
            $this->assertNotNull($view_region_1, 'Region 1 not found under Organization 1.');
            $this->assertTrue($view_region_1->selected, 'Expected Region 1 to be selected.');

            // Region 2 (under Organization 1): Should NOT be selected (trooper not attached with notify=1)
            $view_region_2 = $view_organization_1->regions->firstWhere('id', 2);
            if ($view_region_2)
            { // It might not be in the collection if no units are active
                $this->assertFalse($view_region_2->selected, 'Expected Region 2 to NOT be selected.');
            }

            // Unit 1 (under Region 1): Should be selected
            $view_unit_1 = $view_region_1->units->firstWhere('id', 1);
            $this->assertNotNull($view_unit_1, 'Unit 1 not found under Region 1.');
            $this->assertTrue($view_unit_1->selected, 'Expected Unit 1 to be selected.');

            // Unit 3 (under Region 1): Should NOT be selected (trooper not attached with notify=1)
            $view_unit_3 = $view_region_1->units->firstWhere('id', 3);
            if ($view_unit_3)
            {
                $this->assertFalse($view_unit_3->selected, 'Expected Unit 3 to NOT be selected.');
            }

            // Unit 2 (under Region 2): Should NOT be selected (trooper not attached with notify=1)
            if ($view_region_2)
            {
                $view_unit_2 = $view_region_2->units->firstWhere('id', 2);
                if ($view_unit_2)
                {
                    $this->assertFalse($view_unit_2->selected, 'Expected Unit 2 to NOT be selected.');
                }
            }

            return true;
        });
    }
}
