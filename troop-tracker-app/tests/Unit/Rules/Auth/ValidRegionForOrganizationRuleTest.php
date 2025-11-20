<?php

namespace Tests\Unit\Rules\Auth;

use App\Models\Organization;
use App\Models\Region;
use App\Rules\Auth\ValidRegionForOrganizationRule;
use Closure;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class ValidRegionForOrganizationRuleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Define factories for Organization and Region if they don't exist
        if (!class_exists(\Database\Factories\OrganizationFactory::class))
        {
            Factory::guessFactoryNamesUsing(fn(string $modelName) => 'Database\\Factories\\' . class_basename($modelName) . 'Factory');
            Organization::factory()->create();
        }
        if (!class_exists(\Database\Factories\RegionFactory::class))
        {
            Factory::guessFactoryNamesUsing(fn(string $modelName) => 'Database\\Factories\\' . class_basename($modelName) . 'Factory');
            Region::factory()->create();
        }
    }

    public function test_validation_passes_when_region_is_valid_for_organization(): void
    {
        // Arrange
        $organization = Organization::factory()->create();
        $region = Region::factory()->for($organization)->create(['active' => true]);
        $subject = new ValidRegionForOrganizationRule($organization);
        $fail = Mockery::mock(Closure::class);

        // Expect
        $fail->shouldNotReceive('__invoke');

        // Act
        $subject->validate('region_id', $region->id, $fail);
    }

    public function test_validation_fails_when_region_is_for_another_organization(): void
    {
        // Arrange
        $organization1 = Organization::factory()->create();
        $organization2 = Organization::factory()->create();
        $region = Region::factory()->for($organization2)->create(['active' => true]);
        $subject = new ValidRegionForOrganizationRule($organization1);
        $fail = Mockery::mock(Closure::class);

        // Expect
        $fail->expects('__invoke')->once()->with('Region selection is invalid.');

        // Act
        $subject->validate('region_id', $region->id, $fail);
    }

    public function test_validation_fails_when_region_is_inactive(): void
    {
        // Arrange
        $organization = Organization::factory()->create();
        $region = Region::factory()->for($organization)->create(['active' => false]);
        $subject = new ValidRegionForOrganizationRule($organization);
        $fail = Mockery::mock(Closure::class);

        // Expect
        $fail->expects('__invoke')->once()->with('Region selection is invalid.');

        // Act
        $subject->validate('region_id', $region->id, $fail);
    }

    public function test_validation_passes_when_value_is_empty(): void
    {
        // Arrange
        $organization = Organization::factory()->create();
        $subject = new ValidRegionForOrganizationRule($organization);
        $fail = Mockery::mock(Closure::class);

        // Expect
        $fail->shouldNotReceive('__invoke');

        // Act
        $subject->validate('region_id', '', $fail);
        $subject->validate('region_id', null, $fail);
    }
}
