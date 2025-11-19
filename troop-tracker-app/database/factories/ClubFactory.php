<?php

namespace Database\Factories;

use App\Models\Club;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Club>
 */
class ClubFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            Club::NAME => '501st-' . uniqid(),
            Club::IDENTIFIER_DISPLAY => 'TKID',
            Club::IMAGE_PATH_LG => '',
            Club::IMAGE_PATH_SM => '',
            Club::ACTIVE => true
        ];
    }
}
