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
            'troopers_status_field' => 'p501',
            'troopers_identifier_field' => 'tkid',
            'troopers_notification_field' => 'x',
            'identifier_display' => 'TKID',
            'image_path' => '',
            'active' => true
        ];
    }
}
