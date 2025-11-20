<?php

namespace Database\Seeders;

use App\Models\Organization;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organizations = [
            [
                'name' => '501st Legion',
                'slug' => '501st',
                'description' => 'Imperial costuming organization focused on Star Wars villains.',
                'identifier_display' => 'TKID',
                'identifier_validation' => 'integer|between:1000,99999',
            ],
            [
                'name' => 'Rebel Legion',
                'slug' => 'rebel-legion',
                'description' => 'Rebel-aligned Star Wars costuming group.',
                'identifier_display' => 'Forum Username',
            ],
            [
                'name' => 'Mandalorian Mercs',
                'slug' => 'mercs',
                'description' => 'Custom Mandalorian armor builders and costumers.',
                'identifier_display' => 'CAT #',
                'identifier_validation' => 'integer',
            ],
            [
                'name' => 'Dark Empire',
                'slug' => 'dark-empire',
                'description' => 'Expanded universe costuming group for dark side characters.',
                'identifier_display' => '#',
                'identifier_validation' => 'integer',
            ],
            [
                'name' => 'Droid Builders',
                'slug' => 'droid-builders',
                'description' => 'Star Wars droid construction and robotics enthusiast group.',
                'identifier_display' => '#',
                'identifier_validation' => 'integer',
            ],
            [
                'name' => 'Saber Guild',
                'slug' => 'saber-guild',
                'description' => 'Lightsaber performance and Jedi/Sith costuming group.',
                'identifier_display' => 'SG #',
                'identifier_validation' => 'integer',
            ],
        ];

        foreach ($organizations as $data)
        {
            Organization::updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'active' => true,
                    'identifier_display' => $data['identifier_display'] ?? '',
                    'identifier_validation' => $data['identifier_validation'] ?? '',
                ]
            );
        }
    }
}
