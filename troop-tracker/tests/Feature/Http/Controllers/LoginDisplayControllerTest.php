<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use App\Services\FlashMessageService;
use Tests\TestCase;

class LoginDisplayControllerTest extends TestCase
{
    public function test_displays_the_login_page()
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        $response->assertViewIs('pages.login');
    }

    public function test_invoke_flash_message_when_logged_out_parameter_is_present(): void
    {
        // Arrange: Mock the FlashMessageService to expect a success call.
        $flash_mock = $this->mock(FlashMessageService::class);
        $flash_mock->shouldReceive('success')
            ->once()
            ->with('You have been logged out.');
        $flash_mock->shouldReceive('getMessages')
            ->once();


        // Act: Call the login page with the 'logged_out' query parameter.
        $response = $this->get(route('login', ['logged_out' => '1']));

        // Assert: The page is displayed correctly. The mock handles the flash assertion.
        $response->assertStatus(200);
        $response->assertViewIs('pages.login');
    }
}