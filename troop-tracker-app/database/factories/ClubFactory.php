<?php

namespace Database\Factories;

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
            'name' => '501st-' . uniqid(),
            'identifier_display' => 'TKID',
            'image_path_lg' => '',
            'image_path_sm' => '',
            'active' => true
        ];
    }
}
