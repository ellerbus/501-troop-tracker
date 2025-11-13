<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Auth;

use App\Contracts\AuthenticationInterface;
use App\Enums\AuthenticationStatus;
use App\Enums\MembershipStatus;
use App\Enums\Permissions;
use App\Models\Club;
use App\Models\Trooper;
use App\Services\FlashMessageService;
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

    /**
     * The mocked FlashMessageService.
     */
    private MockInterface $flash_mock;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock the AuthenticationInterface and bind it to the container
        $this->auth_mock = $this->mock(AuthenticationInterface::class);
        $this->flash_mock = $this->mock(FlashMessageService::class);

        $this->seed(ClubSeeder::class);
    }

    public function test_invoke_handles_successful_authentication_with_active_501st_membership(): void
    {
        // Arrange
        $trooper = Trooper::factory()->create([
            'approved' => 1,
        ]);

        $club = Club::where('name', '501st')->first();

        $trooper->trooperClubs()->create([
            'club_id' => $club->id,
            'identifier' => '12345',
            'membership_status' => MembershipStatus::Member,
        ]);

        $this->auth_mock->shouldReceive('authenticate')
            ->once()
            ->with($trooper->forum_id, 'password')
            ->andReturn(AuthenticationStatus::SUCCESS);

        // Act
        $response = $this->post(route('auth.login'), [
            'username' => $trooper->forum_id,
            'password' => 'password',
        ]);

        // Assert
        $this->assertAuthenticatedAs($trooper);
        $response->assertRedirect('/index.php');
        $response->assertSessionHasNoErrors();
        $response->assertSessionHasAll([
            'id' => $trooper->id,
            'tkid' => $trooper->tkid,
        ]);
    }

    public function test_invoke_handles_successful_authentication_with_active_501st_membership_legacy(): void
    {
        // Arrange
        $trooper = Trooper::factory()->create([
            'approved' => 1,
            'p501' => MembershipStatus::Member,
        ]);

        $this->auth_mock->shouldReceive('authenticate')
            ->once()
            ->with($trooper->forum_id, 'password')
            ->andReturn(AuthenticationStatus::SUCCESS);

        // Act
        $response = $this->post(route('auth.login'), [
            'username' => $trooper->forum_id,
            'password' => 'password',
        ]);

        // Assert
        $this->assertAuthenticatedAs($trooper);
        $response->assertRedirect('/index.php');
        $response->assertSessionHasNoErrors();
        $response->assertSessionHasAll([
            'id' => $trooper->id,
            'tkid' => $trooper->tkid,
        ]);
    }


    public function test_invoke_handles_successful_authentication_with_active_club_membership(): void
    {
        // Arrange
        $trooper = Trooper::factory()->create([
            'approved' => 1,
            'p501' => MembershipStatus::None,
            'pRebel' => MembershipStatus::Member,
        ]);

        $this->auth_mock->shouldReceive('authenticate')
            ->once()
            ->with($trooper->forum_id, 'password')
            ->andReturn(AuthenticationStatus::SUCCESS);

        // Act
        $response = $this->post(route('auth.login'), [
            'username' => $trooper->forum_id,
            'password' => 'password',
        ]);

        // Assert
        $this->assertAuthenticatedAs($trooper);
        $response->assertRedirect('/index.php');
    }

    public function test_invoke_handles_failed_authentication(): void
    {
        // Arrange
        $trooper = Trooper::factory()->create([
            'p501' => MembershipStatus::Member,
            'approved' => 1
        ]);

        $this->auth_mock->shouldReceive('authenticate')
            ->once()
            ->with($trooper->forum_id, 'wrong-password')
            ->andReturn(AuthenticationStatus::FAILURE);

        // Act
        $response = $this->post(route('auth.login'), [
            'username' => $trooper->forum_id,
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

        $this->flash_mock->shouldReceive('warning')->once();

        // Act
        $response = $this->post(route('auth.login'), [
            'username' => $trooper->forum_id,
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
        $this->flash_mock->shouldReceive('warning')->once();

        // Act
        $response = $this->post(route('auth.login'), [
            'username' => $trooper->forum_id,
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
        $trooper = Trooper::factory()->create(['permissions' => Permissions::Retired]);

        $this->flash_mock->shouldReceive('warning')->once();

        // Act
        $response = $this->post(route('auth.login'), [
            'username' => $trooper->forum_id,
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
            'p501' => MembershipStatus::Retired,
            'pRebel' => MembershipStatus::None,
        ]);

        $this->flash_mock->shouldReceive('warning');

        // Act
        $response = $this->post(route('auth.login'), [
            'username' => $trooper->forum_id,
            'password' => 'password',
        ]);

        // Assert
        $this->assertGuest();
        $response->assertRedirect();
        $response->assertSessionHasErrors(['username' => 'Refer to command staff']);
    }
}