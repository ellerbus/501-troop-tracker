<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Defines the different permission levels for users within the application.
 *
 * These roles determine what actions a user can perform.
 */
enum TrooperPermissions: string
{
    /**
     * Standard user permissions. Can view content and manage their own profile.
     */
    case None = 'none';

    /**
     * Standard user permissions. Can view content and manage their own profile.
     */
    case Member = 'member';

    /**
     * Administrator permissions. Has full access to all application features and settings.
     */
    case Admin = 'admin';

    /**
     * Moderator permissions. Can manage content and users within specific areas.
     */
    case Moderator = 'moderator';

    /**
     * Retired member permissions. Typically has limited or read-only access.
     */
    case Retired = 'retired';
}