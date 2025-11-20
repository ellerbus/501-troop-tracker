<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Account;

use App\Models\Trooper;
use Database\Seeders\OrganizationSeeder;
use Database\Seeders\UnitSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationsDisplayHtmxControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        // Seed the database with necessary organization and unit data for the test
        $this->seed(OrganizationSeeder::class);
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

        $trooper->organizations()->attach(1, [
            'notify' => 1,
            'identifier' => 'TKID',
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

        // Assert: Check that the organization and unit data is processed correctly
        $response->assertViewHas('organizations', function ($organizations)
        {
            // Find the organization we expect to be selected
            $view_club = $organizations->firstWhere('id', '1');
            if (!$view_club)
            {
                $this->fail('Organization with esquad0 not found in view data.');
            }
            $this->assertTrue($view_club->selected, 'Expected esquad0 to be selected.');

            // Find the unit we expect to be selected
            $view_squad1 = $view_club->units->firstWhere('id', '1');
            if (!$view_squad1)
            {
                $this->fail('Squad with esquad1 not found in view data.');
            }
            $this->assertTrue($view_squad1->selected, 'Expected esquad1 to be selected.');

            // Find the unit we expect to be unselected
            $view_squad2 = $view_club->units->firstWhere('id', '2');
            if (!$view_squad2)
            {
                $this->fail('Squad with esquad2 not found in view data.');
            }
            $this->assertFalse($view_squad2->selected, 'Expected esquad2 to be unselected.');

            return true;
        });
    }


    public function test_invoke_returns_view_with_correct_notification_data(): void
    {
        // Act 
        $trooper = Trooper::factory()->create([
            'instant_notification' => 1,
            'attendance_notification' => 0,
            'command_staff_notification' => 1,
        ]);

        $trooper->organizations()->attach(1, [
            'notify' => 1,
            'identifier' => 'TKID',
        ]);

        $trooper->units()->attach(1, [
            'notify' => 1,
        ]);

        $response = $this->actingAs($trooper)->get(route('account.notifications-htmx'));

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.account.notifications');

        $response->assertViewHas('instant_notification', 1);
        $response->assertViewHas('attendance_notification', 0);
        $response->assertViewHas('command_staff_notification', 1);

        $response->assertViewHas('organizations', function ($organizations)
        {
            $view_club1 = $organizations->firstWhere('id', '1');
            $view_squad1 = $view_club1->units->firstWhere('id', '1');
            $view_squad2 = $view_club1->units->firstWhere('id', '2');

            // Club1 and Squad1 should be selected, Club2 and Squad2 should not
            return $view_club1->selected === true
                && $view_squad1->selected === true
                && $view_squad2->selected === false;
        });
    }
}

