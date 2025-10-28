<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\AuthenticationStatus;
use App\Contracts\AuthenticationInterface;
use Illuminate\Support\Facades\Http;

/**
 * Service to interact with the Xenforo API.
 */
class StandaloneAuthenticationService implements AuthenticationInterface
{
    /**
     * Authenticates a user against the Xenforo API.
     *
     * @param string $username The user's forum username.
     * @param string $password The user's password.
     * @return AuthenticationStatus The result of the authentication attempt.
     */
    public function authenticate(string $username, string $password): AuthenticationStatus
    {
        //  no idea but don't let them in
        return AuthenticationStatus::FAILURE;
    }
}