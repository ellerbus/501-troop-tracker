<?php

namespace Database\Factories;

use App\Models\Region;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Unit>
 */
class UnitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            Unit::NAME => '501st-' . uniqid(),
            Unit::IMAGE_PATH_LG => '',
            Unit::IMAGE_PATH_SM => '',
            Unit::ACTIVE => true,
            Unit::REGION_ID => Region::factory()
        ];
    }
}
