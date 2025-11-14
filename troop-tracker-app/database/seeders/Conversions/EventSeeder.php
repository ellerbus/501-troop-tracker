<?php

declare(strict_types=1);

namespace Database\Seeders\Conversions;

use App\Models\Event;
use Database\Seeders\Conversions\Traits\HasSquadMaps;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{
    use HasSquadMaps;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $squad_maps = $this->getSquadMap();

        $cases = collect($squad_maps)->map(function ($value, $legacy)
        {
            return "WHEN '{$legacy}' THEN {$value['id']}";
        })->implode(' ');

        $squad_case_map = DB::raw("CASE squad {$cases} ELSE NULL END");

        // Copy data from legacy events to tt_events
        DB::table('tt_events')->insertUsing([
            Event::ID,
            Event::NAME,
            Event::SQUAD_ID,
            Event::STARTS_AT,
            Event::ENDS_AT,
            Event::LIMIT_PARTICIPANTS,
            Event::TOTAL_TROOPERS_ALLOWED,
            Event::TOTAL_HANDLERS_ALLOWED,

        ], function ($query) use ($squad_case_map)
        {
            $columns = [
                'id',
                'name',
                $squad_case_map,
                'dateStart',
                'dateEnd',
                DB::raw('CASE WHEN limitedEvent IS NULL THEN 0 ELSE limitedEvent END'),
                'limitTotalTroopers',
                'limitHandlers',
            ];

            $query->select($columns)
                ->from('events')
                ->whereNotExists(function ($sub)
                {
                    $sub->select(DB::raw(1))
                        ->from('tt_events')
                        ->whereColumn('tt_events.id', 'events.id');
                });

        });
    }
}