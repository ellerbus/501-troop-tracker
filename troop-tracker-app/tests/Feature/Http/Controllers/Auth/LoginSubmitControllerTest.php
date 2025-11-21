<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Auth;

use App\Contracts\AuthenticationInterface;
use App\Enums\AuthenticationStatus;
use App\Enums\MembershipStatus;
use App\Models\Organization;
use App\Models\Trooper;
use Database\Seeders\OrganizationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\TestCase;

class LoginSubmitControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The mocked authentication service.
     */
    private MockInterface $auth_mock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(OrganizationSeeder::class);

        $this->auth_mock = $this->mock(AuthenticationInterface::class);
    }

    public function test_invoke_with_valid_credentials_and_active_status_logs_user_in(): void
    {
        // Arrange
        $organization = Organization::first();
        $trooper = Trooper::factory()->create([
            'membership_status' => MembershipStatus::Active,
        ]);
        $trooper->organizations()->attach($organization->id, [
            'identifier' => 'TK9999',
            'membership_status' => MembershipStatus::Active,
        ]);

        $this->auth_mock->shouldReceive('authenticate')
            ->once()
            ->with($trooper->username, 'password')
            ->andReturn(AuthenticationStatus::SUCCESS);

        // Act
        $response = $this->post(route('auth.login'), [
            'username' => $trooper->username,
            'password' => 'password',
        ]);

        // Assert
        $response->assertRedirect(route('account.display'));
        $response->assertSessionHasNoErrors();
        $this->assertAuthenticatedAs($trooper);
    }

    public function test_invoke_with_invalid_credentials_fails_login(): void
    {
        // Arrange
        $trooper = Trooper::factory()->create([
            'membership_status' => MembershipStatus::Active,
        ]);

        $this->auth_mock->shouldReceive('authenticate')
            ->once()
            ->with($trooper->username, 'wrong-password')
            ->andReturn(AuthenticationStatus::FAILURE);

        // Act
        $response = $this->post(route('auth.login'), [
            'username' => $trooper->username,
            'password' => 'wrong-password',
        ]);

        // Assert
        $response->assertRedirect();
        $response->assertSessionHasErrors(['username' => 'Invalid credentials']);
        $this->assertGuest();
    }

    public function test_invoke_with_unapproved_trooper_fails_login(): void
    {
        // Arrange
        $trooper = Trooper::factory()->create(['membership_status' => MembershipStatus::Pending]);

        $this->auth_mock->shouldNotHaveBeenCalled();

        // Act
        $response = $this->post(route('auth.login'), [
            'username' => $trooper->username,
            'password' => 'password',
        ]);

        // Assert
        $response->assertRedirect();
        $response->assertSessionHasErrors(['username' => 'Refer to command staff']);
        $this->assertGuest();
    }

    public function test_invoke_with_banned_trooper_fails_login(): void
    {
        // Arrange
        $trooper = Trooper::factory()->create(['membership_status' => MembershipStatus::Active]);

        $this->auth_mock->shouldReceive('authenticate')
            ->once()
            ->with($trooper->username, 'password')
            ->andReturn(AuthenticationStatus::BANNED);

        // Act
        $response = $this->post(route('auth.login'), [
            'username' => $trooper->username,
            'password' => 'password',
        ]);

        // Assert
        $response->assertRedirect();
        $response->assertSessionHasErrors(['username' => 'Refer to command staff']);
        $this->assertGuest();
    }

    public function test_invoke_with_retired_trooper_fails_login(): void
    {
        // Arrange
        $trooper = Trooper::factory()->create([
            'membership_status' => MembershipStatus::Retired,
        ]);

        $this->auth_mock->shouldReceive('authenticate')
            ->once()
            ->with($trooper->username, 'password')
            ->andReturn(AuthenticationStatus::SUCCESS);

        // Act
        $response = $this->post(route('auth.login'), [
            'username' => $trooper->username,
            'password' => 'password',
        ]);

        // Assert
        $response->assertRedirect();
        $response->assertSessionHasErrors(['username' => 'You cannot access this account.']);
        $this->assertGuest();
    }

    public function test_invoke_with_no_active_organization_status_fails_login(): void
    {
        // Arrange
        $organization = Organization::first();
        $trooper = Trooper::factory()->create([
            'membership_status' => MembershipStatus::Active,
        ]);
        $trooper->organizations()->attach($organization->id, [
            'identifier' => 'TK999',
            'membership_status' => MembershipStatus::Retired,
        ]);

        $this->auth_mock->shouldReceive('authenticate')
            ->once()
            ->with($trooper->username, 'password')
            ->andReturn(AuthenticationStatus::SUCCESS);

        // Act
        $response = $this->post(route('auth.login'), [
            'username' => $trooper->username,
            'password' => 'password',
        ]);

        // Assert
        $response->assertRedirect();
        $response->assertSessionHasErrors(['username' => 'Refer to command staff']);
        $this->assertGuest();
    }
}