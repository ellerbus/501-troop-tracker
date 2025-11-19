<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Auth;

use App\Contracts\AuthenticationInterface;
use App\Enums\AuthenticationStatus;
use App\Enums\MembershipStatus;
use App\Enums\TrooperPermissions;
use App\Models\Club;
use App\Models\Trooper;
use Database\Seeders\ClubSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\TestCase;

class LoginSubmitControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The mocked AuthenticationInterface.
     */
    private MockInterface $auth_mock;

    private Club $the_501st;
    private Club $the_rebels;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock the AuthenticationInterface and bind it to the container
        $this->auth_mock = $this->mock(AuthenticationInterface::class);

        $this->seed(ClubSeeder::class);

        $this->the_501st = Club::where('name', '501st')->first();
        $this->the_rebels = Club::where('name', 'Rebel Legion')->first();
    }

    public function test_invoke_handles_successful_authentication_with_active_501st_membership(): void
    {
        // Arrange
        $trooper = Trooper::factory()->create([
            'approved' => 1,
        ]);

        $trooper->clubs()->attach($this->the_501st->id, [
            'identifier' => '12345',
            'status' => MembershipStatus::Member,
        ]);

        $trooper->clubs()->attach($this->the_rebels->id, [
            'identifier' => '12345',
            'status' => MembershipStatus::None,
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
        $this->assertAuthenticatedAs($trooper);
        $response->assertRedirect('/index.php');
        $response->assertSessionHasNoErrors();
    }

    public function test_invoke_handles_failed_authentication(): void
    {
        // Arrange
        $trooper = Trooper::factory()->create([
            'approved' => 1
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
        $this->assertGuest();
        $response->assertRedirect();
        $response->assertSessionHasErrors(['username' => 'Invalid credentials']);
    }

    public function test_invoke_handles_banned_user(): void
    {
        // Arrange
        $trooper = Trooper::factory()->create();

        // Act
        $response = $this->post(route('auth.login'), [
            'username' => $trooper->username,
            'password' => 'password',
        ]);

        // Assert
        $this->assertGuest();
        $response->assertRedirect();
        $response->assertSessionHasErrors(['username' => 'Refer to command staff']);
    }

    public function test_invoke_handles_unapproved_user(): void
    {
        // Arrange
        $trooper = Trooper::factory()->create(['approved' => false]);

        $this->auth_mock->shouldNotReceive('authenticate');

        // Act
        $response = $this->post(route('auth.login'), [
            'username' => $trooper->username,
            'password' => 'password',
        ]);

        // Assert
        $this->assertGuest();
        $response->assertRedirect();
        $response->assertSessionHasErrors(['username' => 'Refer to command staff']);
    }

    public function test_invoke_handles_retired_user_via_permissions_column(): void
    {
        // Arrange
        $trooper = Trooper::factory()->create(['permissions' => TrooperPermissions::Retired]);

        // Act
        $response = $this->post(route('auth.login'), [
            'username' => $trooper->username,
            'password' => 'password',
        ]);

        // Assert
        $this->assertGuest();
        $response->assertRedirect();
        $response->assertSessionHasErrors(['username' => 'Refer to command staff']);
    }

    public function test_invoke_handles_user_with_no_active_memberships(): void
    {
        // Arrange
        $trooper = Trooper::factory()->create([
            'approved' => 1,
        ]);

        $trooper->clubs()->attach($this->the_501st->id, [
            'identifier' => '12345',
            'status' => MembershipStatus::Retired,
        ]);

        $trooper->clubs()->attach($this->the_rebels->id, [
            'identifier' => '12345',
            'status' => MembershipStatus::None,
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
        $this->assertGuest();
        $response->assertRedirect();
        $response->assertSessionHasErrors(['username' => 'Refer to command staff']);
    }
}