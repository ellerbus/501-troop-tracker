<?php

namespace Database\Factories;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Organization>
 */
class OrganizationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            Organization::NAME => '501st-' . uniqid(),
            Organization::SLUG => 'slug.' . uniqid(),
            Organization::IDENTIFIER_DISPLAY => 'TKID',
            Organization::IMAGE_PATH_LG => '',
            Organization::IMAGE_PATH_SM => '',
            Organization::ACTIVE => true
        ];
    }
}
