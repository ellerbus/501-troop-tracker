<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Trooper>
 */
class TrooperFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'forum_id' => '55555',
            'name' => 'Fives',
            'squad' => 0,
            'tkid' => '99999',
            'approved' => 0,
            // 'username' => $this->faker->userName,
            // 'email' => $this->faker->unique()->safeEmail,
            // 'password' => bcrypt('secret'), // or Hash::make('secret')
        ];
    }
}
