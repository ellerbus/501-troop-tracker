<?php

namespace Tests\Unit\Rules\Auth;

use App\Models\Organization;
use App\Models\Region;
use App\Rules\Auth\ValidRegionForOrganizationRule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ValidRegionForOrganizationRuleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Organization::factory()->create();
        Region::factory()->create();
    }

    public function test_validation_passes_when_region_is_valid_for_organization(): void
    {
        // Arrange
        $organization = Organization::factory()->create();
        $region = Region::factory()->for($organization)->create(['active' => true]);
        $subject = new ValidRegionForOrganizationRule($organization);
        $fail_was_called = false;
        $fail = function (string $message) use (&$fail_was_called): void
        {
            $fail_was_called = true;
        };

        // Act
        $subject->validate('region_id', $region->id, $fail);

        // Assert
        $this->assertFalse($fail_was_called, 'The validation rule should have passed but it failed.');
    }

    public function test_validation_fails_when_region_is_for_another_organization(): void
    {
        // Arrange
        $organization1 = Organization::factory()->create();
        $organization2 = Organization::factory()->create();
        $region = Region::factory()->for($organization2)->create(['active' => true]);
        $subject = new ValidRegionForOrganizationRule($organization1);
        $fail_was_called = false;
        $fail = function (string $message) use (&$fail_was_called): void
        {
            $fail_was_called = true;
            $this->assertEquals('Region selection is invalid.', $message);
        };

        // Act
        $subject->validate('region_id', $region->id, $fail);
    }

    public function test_validation_fails_when_region_is_inactive(): void
    {
        // Arrange
        $organization = Organization::factory()->create();
        $region = Region::factory()->for($organization)->create(['active' => false]);
        $subject = new ValidRegionForOrganizationRule($organization);
        $fail_was_called = false;
        $fail = function (string $message) use (&$fail_was_called): void
        {
            $fail_was_called = true;
            $this->assertEquals('Region selection is invalid.', $message);
        };

        // Act
        $subject->validate('region_id', $region->id, $fail);
    }

    public function test_validation_passes_when_value_is_empty(): void
    {
        // Arrange
        $organization = Organization::factory()->create();
        $subject = new ValidRegionForOrganizationRule($organization);
        $fail_was_called = false;
        $fail = function (string $message) use (&$fail_was_called): void
        {
            $fail_was_called = true;
        };

        // Act
        $subject->validate('region_id', '', $fail);
        $subject->validate('region_id', null, $fail);

        // Assert
        $this->assertFalse($fail_was_called, 'The validation rule should have passed but it failed.');
    }
}
