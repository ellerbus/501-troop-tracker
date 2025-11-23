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
            0 => ['name' => '501st Legion', 'status' => 'p501', 'identity' => 'tkid'],
            6 => ['name' => 'Rebel Legion', 'status' => 'pRebel', 'identity' => 'rebelforum'],
            7 => ['name' => 'Droid Builders', 'status' => 'pDroid', 'identity' => ''],
            8 => ['name' => 'Mandalorian Mercs', 'status' => 'pMando', 'identity' => 'mandoid'],
            //9 => ['name' => 'Other', 'status' => 'pOther', 'identity' => ''],
            10 => ['name' => 'Saber Guild', 'status' => 'pSG', 'identity' => 'sgid'],
            13 => ['name' => 'Dark Empire', 'status' => 'pDE', 'identity' => 'de_id'],
        ];

        // Build final map: squadID → club_id
        $map = [];

        foreach ($legacy_clubs as $legacy_id => $meta)
        {
            $organization = Organization::firstWhere(Organization::NAME, $meta['name']);

            if ($organization)
            {
                $map[$meta['status']] = [
                    'id' => $organization->id,
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