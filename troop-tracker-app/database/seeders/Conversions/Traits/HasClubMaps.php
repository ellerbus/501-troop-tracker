<?php

declare(strict_types=1);

namespace Database\Seeders\Conversions\Traits;

use App\Models\Organization;
use Exception;

trait HasClubMaps
{
    protected function getClubMap(): array
    {
        // Hardcoded squadID → club name
        $legacy_clubs = [
            0 => ['name' => '501st', 'status' => 'p501', 'identity' => 'tkid'],
            6 => ['name' => 'rebel-legion', 'status' => 'pRebel', 'identity' => 'rebelforum'],
            7 => ['name' => 'droid-builders', 'status' => 'pDroid', 'identity' => ''],
            8 => ['name' => 'mercs', 'status' => 'pMando', 'identity' => 'mandoid'],
            //9 => ['name' => 'Other', 'status' => 'pOther', 'identity' => ''],
            10 => ['name' => 'saber-guild', 'status' => 'pSG', 'identity' => 'sgid'],
            13 => ['name' => 'dark-empire', 'status' => 'pDE', 'identity' => 'de_id'],
        ];

        // Build final map: squadID → club_id
        $map = [];

        foreach ($legacy_clubs as $legacy_id => $meta)
        {
            $club = Organization::firstWhere(Organization::SLUG, $meta['name']);

            if ($club)
            {
                $map[$meta['status']] = [
                    'id' => $club->id,
                    'value' => $legacy_id,
                    'identity' => $meta['identity'],
                ];
            }
            else
            {
                throw new Exception("Organization not found for name: {$meta['name']}");
            }
        }

        return $map;
    }
}