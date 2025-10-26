<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use App\Enums\AuthenticationStatus;
use App\Models\Trooper;
use App\Services\AuthenticationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\TestCase;

class LoginSubmitControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The trooper model for testing.
     *
     * @var \App\Models\Trooper
     */
    private Trooper $trooper;

    /**
     * The mocked AuthenticationService.
     *
     * @var \Mockery\MockInterface
     */
    private MockInterface $xenforo_mock;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a test trooper
        $this->trooper = Trooper::factory()->create([
            'forum_id' => '12345',
            'name' => 'TK-12345',
        ]);

        // Mock the AuthenticationService and bind it to the container
        $this->xenforo_mock = $this->mock(AuthenticationService::class);
    }

    public function test_invoke_handles_successful_authentication(): void
    {
        // Arrange: Xenforo service will return SUCCESS
        $this->xenforo_mock->shouldReceive('authenticate')
            ->once()
            ->with($this->trooper->forum_id, 'password')
            ->andReturn(AuthenticationStatus::SUCCESS);

        // Act: Post to the login route
        $response = $this->post(route('login'), [
            'username' => $this->trooper->forum_id,
            'password' => 'password',
        ]);

        // Assert: User is authenticated, session is set, and redirected
        $this->assertAuthenticatedAs($this->trooper);
        $response->assertRedirect('/index.php');
        $response->assertSessionHasNoErrors();
        $response->assertSessionHasAll([
            'id' => $this->trooper->id,
            'tkid' => $this->trooper->tkid,
        ]);
    }

    public function test_invoke_handles_failed_authentication(): void
    {
        // Arrange: Xenforo service will return FAILURE
        $this->xenforo_mock->shouldReceive('authenticate')
            ->once()
            ->andReturn(AuthenticationStatus::FAILURE);

        // Act: Post to the login route
        $response = $this->post(route('login'), [
            'username' => $this->trooper->forum_id,
            'password' => 'wrong-password',
        ]);

        // Assert: User is a guest and redirected back with an error
        $this->assertGuest();
        $response->assertRedirect();
        $response->assertSessionHasErrors(['username' => 'Invalid credentials']);
    }

    public function test_invoke_handles_banned_user(): void
    {
        // Arrange: Xenforo service will return BANNED
        $this->xenforo_mock->shouldReceive('authenticate')
            ->once()
            ->andReturn(AuthenticationStatus::BANNED);

        // Act: Post to the login route
        $response = $this->post(route('login'), [
            'username' => $this->trooper->forum_id,
            'password' => 'password',
        ]);

        // Assert: User is a guest and redirected back with errors and a flash message
        $this->assertGuest();
        $response->assertRedirect();
        $response->assertSessionHasErrors(['username' => 'Refer to command staff']);
    }
}