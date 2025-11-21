<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Auth;

use App\Contracts\AuthenticationInterface;
use App\Enums\MembershipRole;
use App\Enums\MembershipStatus;
use App\Models\Organization;
use App\Models\Region;
use App\Models\Trooper;
use App\Models\Unit;
use App\Services\FlashMessageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Auth\RegisterSubmitController
 */
class RegisterSubmitControllerTest extends TestCase
{
    use RefreshDatabase;

    private MockInterface $auth_mock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->auth_mock = $this->mock(AuthenticationInterface::class);
    }

    public function test_invoke_with_invalid_credentials_fails_registration(): void
    {
        // Arrange
        $this->auth_mock->shouldReceive('verify')
            ->once()
            ->with('testuser', 'password')
            ->andReturn(null);

        $request_data = [
            'username' => 'testuser',
            'password' => 'password',
            'name' => 'Test User',
            'email' => 'test@example.com',
            'account_type' => 'member',
        ];

        // Act
        $response = $this->post(route('auth.register'), $request_data);

        // Assert
        $response->assertRedirect();
        $response->assertSessionHasErrors(['username' => 'Invalid Credentials']);
        $this->assertDatabaseMissing('tt_troopers', ['username' => 'testuser']);
    }

    public function test_invoke_with_valid_credentials_registers_member_successfully(): void
    {
        // Arrange
        $organization = Organization::factory()->create(['identifier_validation' => 'string']);
        $region = Region::factory()->create(['organization_id' => $organization->id]);
        $unit = Unit::factory()->create(['region_id' => $region->id]);

        $this->auth_mock->shouldReceive('verify')
            ->once()
            ->with('testuser', 'password')
            ->andReturn('auth123');

        $request_data = [
            'username' => 'testuser',
            'password' => 'password',
            'name' => 'Test User',
            'email' => 'test@example.com',
            'account_type' => 'member',
            'organizations' => [
                $organization->id => [
                    'selected' => '1',
                    'identifier' => 'TK12345',
                    'region_id' => $region->id,
                    'unit_id' => $unit->id,
                ],
            ],
        ];

        // Act
        $response = $this->post(route('auth.register'), $request_data);

        // Assert
        $response->assertRedirect(route('auth.register'));
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('tt_troopers', [
            'username' => 'testuser',
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $trooper = Trooper::where('username', 'testuser')->first();

        $this->assertDatabaseHas('tt_trooper_organizations', [
            'trooper_id' => $trooper->id,
            'organization_id' => $organization->id,
            'identifier' => 'TK12345',
            'membership_status' => MembershipStatus::Pending->value,
            'membership_role' => MembershipRole::Member->value,
        ]);

        $this->assertDatabaseHas('tt_trooper_regions', [
            'trooper_id' => $trooper->id,
            'region_id' => $region->id,
            'membership_status' => MembershipStatus::Pending->value,
            'membership_role' => MembershipRole::Member->value,
        ]);

        $this->assertDatabaseHas('tt_trooper_units', [
            'trooper_id' => $trooper->id,
            'unit_id' => $unit->id,
            'membership_status' => MembershipStatus::Pending->value,
            'membership_role' => MembershipRole::Member->value,
        ]);
    }

    public function test_invoke_registers_handler_successfully(): void
    {
        // Arrange
        $organization = Organization::factory()->create();

        $this->auth_mock->shouldReceive('verify')
            ->once()
            ->with('handleruser', 'password')
            ->andReturn('auth123');

        $request_data = [
            'username' => 'handleruser',
            'password' => 'password',
            'name' => 'Handler User',
            'email' => 'handler@example.com',
            'account_type' => 'handler',
            'organizations' => [
                $organization->id => [
                    'selected' => '1',
                    'identifier' => 'TK-ID',
                ],
            ],
        ];

        // Act
        $response = $this->post(route('auth.register'), $request_data);

        // Assert
        $response->assertRedirect(route('auth.register'));
        $trooper = Trooper::where('username', 'handleruser')->first();

        $this->assertDatabaseHas('tt_trooper_organizations', [
            'trooper_id' => $trooper->id,
            'organization_id' => $organization->id,
            'membership_role' => MembershipRole::Handler->value,
        ]);
    }
}