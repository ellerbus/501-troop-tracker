<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Account;

use App\Models\Organization;
use App\Models\Trooper;
use Database\Seeders\OrganizationSeeder;
use Database\Seeders\UnitSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationsSubmitHtmxControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(OrganizationSeeder::class);
        $this->seed(UnitSeeder::class);
    }

    public function test_invoke_updates_notifications_and_returns_view_with_flash_message(): void
    {
        // Arrange
        // Arrange: Create a trooper with specific notification settings
        $trooper = Trooper::factory()->create([
            'instant_notification' => 1,
            'attendance_notification' => 0,
            'command_staff_notification' => 1,
        ]);

        $organizations = Organization::where('active', true)->get();

        $organization = $organizations->firstWhere('id', '1');

        $unit = $organization->units->firstWhere('id', '1');

        $request_data = [
            'instant_notification' => '1',
            'attendance_notification' => '0',
            'command_staff_notification' => '1',
            'organizations' => [
                $organization->id => ['notification' => '1'],
            ],
            'units' => [
                $unit->id => ['notification' => '1'],
            ],
        ];

        $expected_flash_message = json_encode([
            'message' => 'Notifications updated successfully!',
            'type' => 'success',
        ]);

        // Act
        $response = $this->actingAs($trooper)->post(route('account.notifications-htmx'), $request_data);

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.account.notifications');
        $response->assertViewHasAll([
            'organizations',
            'instant_notification',
            'attendance_notification',
            'command_staff_notification',
        ]);
        $response->assertHeader('X-Flash-Message', $expected_flash_message);

        $view_data = $response->getOriginalContent()->getData();
        $this->assertEquals('1', $view_data['instant_notification']);
        $this->assertEquals('0', $view_data['attendance_notification']);
        $this->assertEquals('1', $view_data['command_staff_notification']);
        $this->assertTrue($view_data['organizations']->first()->selected);
        $this->assertTrue($view_data['organizations']->first()->units->first()->selected);
    }
}