<?php

declare(strict_types=1);

namespace App\Enums;

enum AuthenticationStatus: string
{
    case SUCCESS = 'success';
    case FAILURE = 'failure';
    case BANNED = 'banned';
}