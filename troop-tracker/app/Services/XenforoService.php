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
class XenforoService
{
    /**
     * XenforoService constructor.
     */
    public function __construct()
    {
    }


    /**
     * Authenticates a user against the Xenforo API.
     *
     * @param string $user_name The user's forum username.
     * @param string $password The user's password.
     * @param Trooper $trooper The trooper model associated with the user. Currently unused.
     * @return AuthenticationStatus The result of the authentication attempt.
     */
    public function authenticate(string $user_name, string $password, Trooper $trooper): AuthenticationStatus
    {
        $credentials = [
            'login' => $user_name,
            'password' => $password,
        ];

        $headers = [
            'XF-Api-Key' => config('auth.xenforo.key'),
            'XF-Api-User' => config('auth.xenforo.user'),
        ];

        $response = Http::withHeaders($headers)->post(config('auth.xenforo.url'), $credentials);

        $message = json_decode($response->body(), false);

        if (!isset($message))
        {
            //  no message
            return AuthenticationStatus::FAILURE;
        }

        if (isset($message->success) && $message->success)
        {
            //  success flag in the message
            return AuthenticationStatus::SUCCESS;
        }

        if (isset($message->user->is_banned) && $message->user->is_banned)
        {
            //  banned flag in the message
            return AuthenticationStatus::BANNED;
        }

        //  no idea but don't let them in
        return AuthenticationStatus::FAILURE;
    }
}