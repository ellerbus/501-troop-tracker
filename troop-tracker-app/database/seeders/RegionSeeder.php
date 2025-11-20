<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\Region;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $regions = [
            // 501st Legion
            ['org_slug' => '501st', 'name' => 'Florida Garrison'],

            // Rebel Legion
            ['org_slug' => 'rebel-legion', 'name' => 'Ra Kura Base'],

            // Mandalorian Mercs
            ['org_slug' => 'mercs', 'name' => 'House Buurenaar Verda'],

            // Dark Empire
            ['org_slug' => 'dark-empire', 'name' => 'Dark Empire Florida'],

            // Saber Guild
            ['org_slug' => 'saber-guild', 'name' => 'Saber Guild - Talon Temple'],

            // Droid Builders (optional regional naming)
            ['org_slug' => 'droid-builders', 'name' => 'Florida Droid Builders'],
        ];

        foreach ($regions as $data)
        {
            $organization = once(fn() => Organization::where('slug', $data['org_slug'])->first());

            if ($organization)
            {
                Region::updateOrCreate(
                    ['name' => $data['name'], 'organization_id' => $organization->id],
                    ['active' => true]
                );
            }
        }

    }
}
