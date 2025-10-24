<?php

declare(strict_types=1);

namespace App\Domain\Results;

/**
 * Represents the possible statuses after a login attempt.
 */
enum LoginStatus: string
{
    case Failed = 'failed';
    case Success = 'success';
    case Approved = 'approved';
    case NotApproved = 'not_approved';
    case Banned = 'banned';
    case NotFound = 'not_found';
    case Retired = 'retired';
}