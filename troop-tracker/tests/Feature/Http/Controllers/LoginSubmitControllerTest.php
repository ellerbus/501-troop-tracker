<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use App\Enums\AuthenticationStatus;
use App\Models\Trooper;
use App\Services\XenforoService;
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
     * The mocked XenforoService.
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

        // Mock the XenforoService and bind it to the container
        $this->xenforo_mock = $this->mock(XenforoService::class);
    }

    public function test_invoke_handles_successful_authentication(): void
    {
        // Arrange: Xenforo service will return SUCCESS
        $this->xenforo_mock->shouldReceive('authenticate')
            ->once()
            ->with($this->trooper->forum_id, 'password', \Mockery::on(function ($trooper)
            {
                return $trooper->id === $this->trooper->id;
            }))
            ->andReturn(AuthenticationStatus::SUCCESS);

        // Act: Post to the login route
        $response = $this->post(route('login'), [
            'username' => $this->trooper->forum_id,
            'password' => 'password',
        ]);

        // Assert: User is authenticated and redirected to the dashboard
        $this->assertAuthenticatedAs($this->trooper);
        $response->assertRedirect('/dashboard');
        $response->assertSessionHasNoErrors();
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
        $response->assertSessionHas('flash_messages.danger', [
            'You are currently banned. Please refer to command staff for additional information.'
        ]);
    }
}