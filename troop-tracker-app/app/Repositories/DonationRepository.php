<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Donation;
use Carbon\Carbon;

/**
 * Repository for handling business logic related to donations.
 */
class DonationRepository
{
    /**
     * Calculates the total amount of donations for the current month.
     *
     * Note: The $id parameter is not currently used in the calculation.
     *
     * @return float The total donation amount for the current month.
     */
    public function getCurrentMonthTotal(): float
    {
        $startOfMonth = Carbon::now()->startOfMonth();

        return (float) Donation::where(Donation::DONATED_AT, '>', $startOfMonth)->sum('amount');
    }
}