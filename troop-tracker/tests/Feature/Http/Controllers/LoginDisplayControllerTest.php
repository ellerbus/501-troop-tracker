<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;

class LoginDisplayControllerTest extends TestCase
{
    public function test_displays_the_login_page()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertViewIs('pages.login');
        // $response->assertSee('Frequently Asked Questions'); // optional content check
    }
}