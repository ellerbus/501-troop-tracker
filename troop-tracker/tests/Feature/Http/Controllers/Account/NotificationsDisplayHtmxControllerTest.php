<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Account;

use App\Models\Trooper;
use Database\Seeders\ClubSeeder;
use Database\Seeders\SquadSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationsDisplayHtmxControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        // Seed the database with necessary club and squad data for the test
        $this->seed(ClubSeeder::class);
        $this->seed(SquadSeeder::class);
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
            'efast' => 1,
            'econfirm' => 0,
            'ecommandnotify' => 1,
            'esquad0' => 1, // Assume this trooper is subscribed to club 1
            'esquad1' => 1, // and squad 1
            'esquad2' => 0, // but not squad 2
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

        // Assert: Check that the club and squad data is processed correctly
        $response->assertViewHas('clubs', function ($clubs)
        {
            // Find the club we expect to be selected
            $esquad0 = $clubs->firstWhere('troopers_notification_field', 'esquad0');
            if (!$esquad0)
            {
                $this->fail('Club with esquad0 not found in view data.');
            }
            $this->assertTrue($esquad0->selected, 'Expected esquad0 to be selected.');

            // Find the squad we expect to be selected
            $esquad1 = $esquad0->squads->firstWhere('troopers_notification_field', 'esquad1');
            if (!$esquad1)
            {
                $this->fail('Squad with esquad1 not found in view data.');
            }
            $this->assertTrue($esquad1->selected, 'Expected esquad1 to be selected.');

            // Find the squad we expect to be unselected
            $esquad2 = $esquad0->squads->firstWhere('troopers_notification_field', 'esquad2');
            if (!$esquad2)
            {
                $this->fail('Squad with esquad2 not found in view data.');
            }
            $this->assertFalse($esquad2->selected, 'Expected esquad2 to be unselected.');

            return true;
        });
    }


    public function test_invoke_returns_view_with_correct_notification_data(): void
    {
        // Act 
        $trooper = Trooper::factory()->create([
            'efast' => 1,
            'econfirm' => 0,
            'ecommandnotify' => 1,
            'esquad0' => 1, // Legacy field for club1
            'esquad1' => 1, // Legacy field for squad1
            'esquad2' => 0, // Legacy field for squad2
        ]);

        $response = $this->actingAs($trooper)
            ->get(route('account.notifications-htmx'));

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.account.notifications');

        $response->assertViewHas('instant_notification', 1);
        $response->assertViewHas('attendance_notification', 0);
        $response->assertViewHas('command_staff_notification', 1);

        $response->assertViewHas('clubs', function ($clubs)
        {
            $view_club1 = $clubs->firstWhere('troopers_notification_field', 'esquad0');
            $view_squad1 = $view_club1->squads->firstWhere('troopers_notification_field', 'esquad1');
            $view_squad2 = $view_club1->squads->firstWhere('troopers_notification_field', 'esquad2');

            // Club1 and Squad1 should be selected, Club2 and Squad2 should not
            return $view_club1->selected === true
                && $view_squad1->selected === true
                && $view_squad2->selected === false;
        });
    }
}

