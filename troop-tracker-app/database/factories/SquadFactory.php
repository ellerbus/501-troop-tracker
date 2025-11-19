<?php

namespace Database\Factories;

use App\Models\Club;
use App\Models\Squad;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Squad>
 */
class SquadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            Squad::NAME => '501st-' . uniqid(),
            Squad::IMAGE_PATH_LG => '',
            Squad::IMAGE_PATH_SM => '',
            Squad::ACTIVE => true,
            Squad::CLUB_ID => Club::factory()
        ];
    }
}
