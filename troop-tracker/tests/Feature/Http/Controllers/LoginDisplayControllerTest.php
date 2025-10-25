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
}