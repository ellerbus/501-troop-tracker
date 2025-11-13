<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Account;

use App\Models\Club;
use App\Models\Squad;
use App\Models\Trooper;
use Database\Seeders\ClubSeeder;
use Database\Seeders\SquadSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationsSubmitHtmxControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(ClubSeeder::class);
        $this->seed(SquadSeeder::class);
    }

    public function test_invoke_updates_notifications_and_returns_view_with_flash_message(): void
    {
        // Arrange
        // Arrange: Create a trooper with specific notification settings
        $trooper = Trooper::factory()->create([
            'efast' => 1,
            'econfirm' => 0,
            'ecommandnotify' => 1,
            'esquad0' => 1, // Assume this trooper is subscribed to club 1
            'esquad1' => 1, // and squad 1
            'esquad2' => 0, // but not squad 2
        ]);

        $clubs = Club::where('active', true)->get();

        $club = $clubs->firstWhere('troopers_notification_field', 'esquad0');

        $squad = $club->squads->firstWhere('troopers_notification_field', 'esquad1');

        $request_data = [
            'instant_notification' => '1',
            'attendance_notification' => '0',
            'command_staff_notification' => '1',
            'clubs' => [
                $club->id => ['notification' => '1'],
            ],
            'squads' => [
                $squad->id => ['notification' => '1'],
            ],
        ];

        $expected_updates = [
            'efast' => '1',
            'econfirm' => '0',
            'ecommandnotify' => '1',
            'esquad0' => 0,
            'esquad1' => true,
            'esquad2' => 0,
            'esquad3' => 0,
            'esquad4' => 0,
            'esquad5' => 0,
            'esquad6' => 0,
            'esquad7' => 0,
            'esquad8' => 0,
            'esquad9' => 0,
            'esquad10' => 0,
            'esquad13' => 0,
            'eclub1' => true,
        ];


        $expected_flash_message = json_encode([
            'message' => 'Notifications updated successfully!',
            'type' => 'success',
        ]);

        // Act
        $response = $this->actingAs($trooper)
            ->post(route('account.notifications-htmx'), $request_data);

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.account.notifications');
        $response->assertViewHasAll([
            'clubs',
            'instant_notification',
            'attendance_notification',
            'command_staff_notification',
        ]);
        $response->assertHeader('X-Flash-Message', $expected_flash_message);

        $view_data = $response->getOriginalContent()->getData();
        $this->assertEquals('1', $view_data['instant_notification']);
        $this->assertEquals('0', $view_data['attendance_notification']);
        $this->assertEquals('1', $view_data['command_staff_notification']);
        $this->assertTrue($view_data['clubs']->first()->selected);
        $this->assertTrue($view_data['clubs']->first()->squads->first()->selected);
    }
}