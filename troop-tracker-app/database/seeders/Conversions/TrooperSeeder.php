<?php

declare(strict_types=1);

namespace Database\Seeders\Conversions;

use App\Models\Trooper;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrooperSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Copy data from legacy troopers to tt_troopers
        DB::table('tt_troopers')->insertUsing([
            Trooper::ID,
            Trooper::NAME,
            Trooper::EMAIL,
            Trooper::PHONE,
            Trooper::USERNAME,
            Trooper::PASSWORD,
            Trooper::LAST_ACTIVE_AT,
            Trooper::APPROVED,
            Trooper::CREATED_AT,
            Trooper::INSTANT_NOTIFICATION,
            Trooper::ATTENDANCE_NOTIFICATION,
            Trooper::COMMAND_STAFF_NOTIFICATION,
            Trooper::PERMISSIONS,
        ], function ($query)
        {
            $columns = [
                'id',
                'name',
                'email',
                'phone',
                'forum_id',
                DB::raw("
                    CASE
                        WHEN password IS NULL THEN UUID()
                        ELSE password
                    END AS password
                "),
                'last_active',
                'approved',
                'datecreated',
                'efast',
                'econfirm',
                'ecommandnotify',
                DB::raw("
                    CASE permissions
                        WHEN 0 THEN 'member'
                        WHEN 1 THEN 'admin'
                        WHEN 2 THEN 'moderator'
                        WHEN 3 THEN 'retired'
                        ELSE 'none'
                    END AS permissions
                "),

            ];

            $query->select($columns)
                ->from('troopers')
                ->whereNotNull('email')->whereNotExists(function ($sub)
                {
                    $sub->select(DB::raw(1))
                        ->from('tt_troopers')
                        ->whereColumn('tt_troopers.id', 'troopers.id');
                });

        });
    }
}