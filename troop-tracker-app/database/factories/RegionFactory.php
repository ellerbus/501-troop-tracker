<?php

namespace Database\Factories;

use App\Models\Organization;
use App\Models\Region;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Region>
 */
class RegionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            Region::NAME => '501st-' . uniqid(),
            Region::IMAGE_PATH_LG => '',
            Region::IMAGE_PATH_SM => '',
            Region::ACTIVE => true,
            Region::ORGANIZATION_ID => Organization::factory()
        ];
    }
}
