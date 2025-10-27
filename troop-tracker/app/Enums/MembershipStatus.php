<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Defines the membership status of a trooper within a club.
 */
enum MembershipStatus: int
{
    /**
     * Not a member of the club.
     */
    case None = 0;

    /**
     * An active, regular member.
     */
    case Member = 1;

    /**
     * A member on reserve status.
     */
    case Reserve = 2;

    /**
     * A retired member.
     */
    case Retired = 3;

    /**
     * A non-costumed handler.
     */
    case Handler = 4;
}