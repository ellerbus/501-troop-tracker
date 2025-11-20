<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Defines the membership status of a trooper within a organization.
 */
enum MembershipStatus: string
{
    /**
     * Not a member of the organization.
     */
    case None = 'none';

    /**
     * An active, regular member.
     */
    case Member = 'member';

    /**
     * A member on reserve status.
     */
    case Reserve = 'reserve';

    /**
     * A retired member.
     */
    case Retired = 'retired';

    /**
     * A non-costumed handler.
     */
    case Handler = 'handler';
}