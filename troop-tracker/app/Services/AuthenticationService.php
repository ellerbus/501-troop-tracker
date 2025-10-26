<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\AuthenticationStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Trooper;

/**
 * Service to interact with the Xenforo API.
 */
class AuthenticationService
{
    /**
     * XenforoService constructor.
     */
    public function __construct(private readonly XenforoService $xenforo)
    {
    }


    /**
     * Authenticates a user against the Xenforo API.
     *
     * @param string $username The user's forum username.
     * @param string $password The user's password.
     * @return AuthenticationStatus The result of the authentication attempt.
     */
    public function authenticate(string $username, string $password): AuthenticationStatus
    {
        return $this->xenforo->authenticate($username, $password);
    }
}