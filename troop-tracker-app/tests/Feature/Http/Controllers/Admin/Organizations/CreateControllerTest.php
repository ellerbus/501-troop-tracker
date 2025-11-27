<?php

declare(strict_types=1);

namespace Tests\Feature\Admin\Organizations;

use App\Models\Organization;
use App\Models\Trooper;
use App\Policies\OrganizationPolicy;
use App\Services\BreadCrumbService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\TestCase;

class CreateControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_invoke_returns_view_for_authorized_trooper(): void
    {
        // Arrange
        $trooper = Trooper::factory()->asAdmin()->create();
        $this->actingAs($trooper);

        $parent_organization = Organization::factory()->create();

        // Act
        $response = $this->get(route('admin.organizations.create', ['parent' => $parent_organization]));

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.admin.organizations.create');
        $response->assertViewHas('parent', $parent_organization);
        $response->assertViewHas('organization', function ($organization)
        {
            return $organization instanceof Organization && !$organization->exists;
        });
    }

    public function test_invoke_returns_forbidden_for_unauthorized_trooper(): void
    {
        // Arrange
        $trooper = Trooper::factory()->create();
        $this->actingAs($trooper);

        $parent_organization = Organization::factory()->create();

        // Act
        $response = $this->get(route('admin.organizations.create', ['parent' => $parent_organization]));

        // Assert
        $response->assertForbidden();
    }
}
