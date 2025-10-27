<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Defines the types of accounts a user can register for.
 */
enum AccountType: int
{
    /**
     * A regular, costumed member account.
     */
    case Regular = 1;

    /**
     * A non-costumed handler account.
     */
    case Handler = 4;
}
