<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Account;

use App\Models\Trooper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileDisplayHtmxControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_unauthenticated_user_is_redirected_to_login(): void
    {
        // Act
        $response = $this->get(route('account.profile-htmx'));

        // Assert
        $response->assertRedirect(route('auth.login'));
    }

    public function test_authenticated_user_can_view_their_profile_data(): void
    {
        // Arrange
        $trooper = Trooper::factory()->create([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'phone' => '555-123-4567',
        ]);

        // Act
        $response = $this->actingAs($trooper)
            ->get(route('account.profile-htmx'));

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.account.profile');
        $response->assertViewHasAll([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'phone' => '555-123-4567',
        ]);
    }
}