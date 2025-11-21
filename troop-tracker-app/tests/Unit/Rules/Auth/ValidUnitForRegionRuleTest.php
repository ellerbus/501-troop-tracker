<?php

namespace Tests\Unit\Rules\Auth;

use App\Models\Region;
use App\Models\Unit;
use App\Rules\Auth\ValidUnitForRegionRule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ValidUnitForRegionRuleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Region::factory()->create();
        Unit::factory()->create();
    }

    public function test_validation_passes_when_unit_is_valid_for_region(): void
    {
        // Arrange
        $region = Region::factory()->create();
        $unit = Unit::factory()->for($region)->create(['active' => true]);
        $subject = new ValidUnitForRegionRule($region);
        $fail_was_called = false;
        $fail = function (string $message) use (&$fail_was_called): void
        {
            $fail_was_called = true;
        };

        // Act
        $subject->validate('unit_id', $unit->id, $fail);

        // Assert
        $this->assertFalse($fail_was_called, 'The validation rule should have passed but it failed.');
    }

    public function test_validation_fails_when_unit_is_for_another_region(): void
    {
        // Arrange
        $region1 = Region::factory()->create();
        $region2 = Region::factory()->create();
        $unit = Unit::factory()->for($region2)->create(['active' => true]);
        $subject = new ValidUnitForRegionRule($region1);
        $fail_was_called = false;
        $fail = function (string $message) use (&$fail_was_called): void
        {
            $fail_was_called = true;
            $this->assertEquals('Unit selection is invalid.', $message);
        };

        // Act
        $subject->validate('unit_id', $unit->id, $fail);

        // Assert
        $this->assertTrue($fail_was_called, 'The validation rule should have failed but it passed.');
    }

    public function test_validation_fails_when_unit_is_inactive(): void
    {
        // Arrange
        $region = Region::factory()->create();
        $unit = Unit::factory()->for($region)->create(['active' => false]);
        $subject = new ValidUnitForRegionRule($region);
        $fail_was_called = false;
        $fail = function (string $message) use (&$fail_was_called): void
        {
            $fail_was_called = true;
            $this->assertEquals('Unit selection is invalid.', $message);
        };

        // Act
        $subject->validate('unit_id', $unit->id, $fail);

        // Assert
        $this->assertTrue($fail_was_called, 'The validation rule should have failed but it passed.');
    }

    public function test_validation_passes_when_value_is_empty(): void
    {
        // Arrange
        $region = Region::factory()->create();
        $subject = new ValidUnitForRegionRule($region);
        $fail_was_called = false;
        $fail = function (string $message) use (&$fail_was_called): void
        {
            $fail_was_called = true;
        };

        // Act
        $subject->validate('unit_id', '', $fail);
        $subject->validate('unit_id', null, $fail);

        // Assert
        $this->assertFalse($fail_was_called, 'The validation rule should have passed but it failed.');
    }
}
