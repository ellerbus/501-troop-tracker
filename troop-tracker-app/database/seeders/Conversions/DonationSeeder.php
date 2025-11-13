<?php

declare(strict_types=1);

namespace Database\Seeders\Conversions;

use App\Models\Donation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DonationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Copy data from legacy troopers to tt_troopers
        DB::table('tt_donations')->insertUsing([
            Donation::TROOPER_ID,
            Donation::AMOUNT,
            Donation::TXN_ID,
            Donation::TXN_TYPE,
            Donation::DONATED_AT,
        ], function ($query)
        {
            $columns = [
                'trooperid',
                'amount',
                'txn_id',
                'txn_type',
                'datetime',
            ];

            $query->select($columns)
                ->from('donations')
                ->join('tt_troopers', 'donations.trooperid', '=', 'tt_troopers.id')
                ->whereNotExists(function ($sub)
                {
                    $sub->select(DB::raw(1))
                        ->from('tt_donations')
                        ->whereColumn('tt_donations.txn_id', 'donations.txn_id');
                });
        });
    }
}