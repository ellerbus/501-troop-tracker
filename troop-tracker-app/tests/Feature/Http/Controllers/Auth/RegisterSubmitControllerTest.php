<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Contracts\AuthenticationInterface;
use App\Models\Club;
use Database\Seeders\ClubSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\TestCase;

class RegisterSubmitControllerTest extends TestCase
{
    use RefreshDatabase;

    private MockInterface $auth_mock;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed the database with necessary club data for validation
        $this->seed(ClubSeeder::class);

        // Mock the controller's dependencies
        $this->auth_mock = $this->mock(AuthenticationInterface::class);
    }

    public function test_invoke_with_valid_credentials_registers_trooper_and_redirects(): void
    {
        // Arrange: Prepare valid form data that passes RegisterRequest validation
        $club = Club::find(1); // Assuming ClubSeeder creates a club with ID 1
        $valid_data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'account_type' => 'member',
            'username' => 'testuser',
            'password' => 'password',
            'clubs' => [
                $club->id => [
                    'selected' => '1',
                    'identifier' => '12345', // Assuming club 1 requires an identifier
                ],
            ],
        ];

        // Set up mock expectations for a successful registration
        $auth_id = 999;
        $this->auth_mock->shouldReceive('verify')
            ->once()
            ->with('testuser', 'password')
            ->andReturn($auth_id);

        // Act: Post the valid data to the registration submission route
        $response = $this->post(route('auth.register'), $valid_data);

        // Assert: Verify the user is redirected back
        $response->assertRedirect();
    }

    public function test_invoke_with_invalid_credentials_redirects_with_errors(): void
    {
        // Arrange: Prepare valid form data but expect authentication to fail
        $club = Club::find(1); // Assuming ClubSeeder creates a club with ID 1
        $valid_data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'account_type' => 'member',
            'username' => 'wronguser',
            'password' => 'wrongpassword',
            'clubs' => [$club->id => ['selected' => '1', 'identifier' => '12345']],
        ];

        // Set up mock expectation for a failed authentication
        $this->auth_mock->shouldReceive('verify')
            ->once()
            ->with('wronguser', 'wrongpassword')
            ->andReturn(null);

        // Act: Post the data
        $response = $this->post(route('auth.register'), $valid_data);

        // Assert: Verify the user is redirected back with a specific error
        $response->assertRedirect();
        $response->assertSessionHasErrors('username');
    }
}