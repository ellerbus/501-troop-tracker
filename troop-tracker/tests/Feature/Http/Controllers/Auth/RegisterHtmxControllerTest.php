<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Models\Club;
use Database\Seeders\ClubSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterHtmxControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(ClubSeeder::class);
    }

    public function test_invoke_returns_partial_view_with_club_selected(): void
    {
        // Arrange: Create a club and define the input data for selection.
        $club = Club::find(1);
        $input_data = [
            'clubs' => [
                $club->id => ['selected' => '1'],
            ],
        ];

        // Act: Simulate an HTMX POST request to the controller's route.
        // We assume a route is defined, e.g., 'auth.register-htmx'
        $response = $this->withHeaders(['HX-Request' => 'true'])
            ->post(route('auth.register-htmx', ['club' => $club->id]), $input_data);

        // Assert: Check for a successful response and the correct view.
        $response->assertOk();
        $response->assertViewIs('pages.auth.club-selection');

        // Assert that the 'club' object passed to the view has 'selected' set to true.
        $response->assertViewHas('club', function ($view_club)
        {
            return $view_club->selected === true;
        });
    }

    public function test_invoke_returns_partial_view_with_club_not_selected(): void
    {
        // Arrange: Create a club and define input data where the club is not selected.
        $club = Club::find(1);
        $input_data = [
            'clubs' => [
                $club->id => ['selected' => '0'], // or null/not present
            ],
        ];

        // Act: Simulate an HTMX POST request.
        $response = $this->withHeaders(['HX-Request' => 'true'])
            ->post(route('auth.register-htmx', ['club' => $club->id]), $input_data);

        // Assert: Check for a successful response and the correct view.
        $response->assertOk();
        $response->assertViewIs('pages.auth.club-selection');

        // Assert that the 'club' object passed to the view has 'selected' set to false.
        $response->assertViewHas('club', function ($view_club)
        {
            return $view_club->selected === false;
        });
    }
}