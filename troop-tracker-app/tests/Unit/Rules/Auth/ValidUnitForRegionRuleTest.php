<?php

namespace Tests\Unit\Rules\Auth;

use App\Models\Region;
use App\Models\Unit;
use App\Rules\Auth\ValidUnitForRegionRule;
use Closure;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class ValidUnitForRegionRuleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Define factories for Region and Unit if they don't exist
        if (!class_exists(\Database\Factories\RegionFactory::class))
        {
            Factory::guessFactoryNamesUsing(function (string $modelName)
            {
                return 'Database\\Factories\\' . class_basename($modelName) . 'Factory';
            });
            Region::factory()->create();
        }
        if (!class_exists(\Database\Factories\UnitFactory::class))
        {
            Factory::guessFactoryNamesUsing(function (string $modelName)
            {
                return 'Database\\Factories\\' . class_basename($modelName) . 'Factory';
            });
            Unit::factory()->create();
        }
    }

    public function test_validation_passes_when_unit_is_valid_for_region(): void
    {
        // Arrange
        $region = Region::factory()->create();
        $unit = Unit::factory()->for($region)->create(['active' => true]);
        $subject = new ValidUnitForRegionRule($region);
        $fail = Mockery::mock(Closure::class);

        // Expect
        $fail->shouldNotReceive('__invoke');

        // Act
        $subject->validate('unit_id', $unit->id, $fail);
    }

    public function test_validation_fails_when_unit_is_for_another_region(): void
    {
        // Arrange
        $region1 = Region::factory()->create();
        $region2 = Region::factory()->create();
        $unit = Unit::factory()->for($region2)->create(['active' => true]);
        $subject = new ValidUnitForRegionRule($region1);
        $fail = Mockery::mock(Closure::class);

        // Expect
        $fail->expects('__invoke')->once()->with('Unit selection is invalid.');

        // Act
        $subject->validate('unit_id', $unit->id, $fail);
    }

    public function test_validation_fails_when_unit_is_inactive(): void
    {
        // Arrange
        $region = Region::factory()->create();
        $unit = Unit::factory()->for($region)->create(['active' => false]);
        $subject = new ValidUnitForRegionRule($region);
        $fail = Mockery::mock(Closure::class);

        // Expect
        $fail->expects('__invoke')->once()->with('Unit selection is invalid.');

        // Act
        $subject->validate('unit_id', $unit->id, $fail);
    }

    public function test_validation_passes_when_value_is_empty(): void
    {
        // Arrange
        $region = Region::factory()->create();
        $subject = new ValidUnitForRegionRule($region);
        $fail = Mockery::mock(Closure::class);

        // Expect
        $fail->shouldNotReceive('__invoke');

        // Act
        $subject->validate('unit_id', '', $fail);
        $subject->validate('unit_id', null, $fail);
    }
}
