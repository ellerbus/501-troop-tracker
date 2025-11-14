<?php

namespace App\Models;

use App\Models\Base\Donation as BaseDonation;
use Carbon\Carbon;

class Donation extends BaseDonation
{
    protected $fillable = [
        self::TROOPER_ID,
        self::AMOUNT,
        self::TXN_ID,
        self::TXN_TYPE
    ];


    /**
     * Calculates the total amount of donations for the current month.
     *
     * Note: The $id parameter is not currently used in the calculation.
     *
     * @return float The total donation amount for the current month.
     */
    public static function getMonthlyTotal(Carbon $date = null): float
    {
        if ($date == null)
        {
            $date = Carbon::now()->startOfMonth();
        }

        return (float) self::where(self::DONATED_AT, '>', $date)->sum('amount');
    }
}
