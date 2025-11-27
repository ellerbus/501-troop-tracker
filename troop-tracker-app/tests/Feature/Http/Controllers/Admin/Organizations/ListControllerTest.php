<?php

declare(strict_types=1);

namespace Tests\Feature\Admin\Organizations;

use App\Models\Organization;
use App\Models\Trooper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class ListControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_invoke_returns_view_with_organizations_for_authenticated_trooper(): void
    {
        // Arrange
        $trooper = Trooper::factory()->asAdmin()->create();
        $this->actingAs($trooper);

        $organizations = Organization::factory(3)->create();

        // Act
        $response = $this->get(route('admin.organizations.list'));

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.admin.organizations.list');
        $response->assertViewHas('organizations', function (Collection $view_organizations) use ($organizations)
        {
            return $view_organizations->count() === 3;
        });
    }
}