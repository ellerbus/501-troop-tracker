<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\AuthenticationStatus;
use App\Contracts\AuthenticationInterface;
use Illuminate\Support\Facades\Http;

/**
 * Service to interact with the Xenforo API.
 */
class XenforoAuthenticationService implements AuthenticationInterface
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
        $message = $this->getMessage($username, $password);

        if (isset($message) && isset($message->success) && $message->success)
        {
            if (isset($message->user->is_banned) && $message->user->is_banned)
            {
                //  banned flag in the message
                return AuthenticationStatus::BANNED;
            }

            return AuthenticationStatus::SUCCESS;
        }

        //  no idea but don't let them in
        return AuthenticationStatus::FAILURE;
    }

    /**
     * Verifies a user against the Xenforo API.
     *
     * @param string $username The user's forum username.
     * @param string $password The user's password.
     * @return mixed The identifier the verification attempt or null if it fails.
     */
    public function verify(string $username, string $password): mixed
    {
        $message = $this->getMessage($username, $password);

        if (isset($message) && isset($message->success) && $message->success)
        {
            if (isset($message->user->is_banned) && $message->user->is_banned)
            {
                return null;
            }

            return $message->user->id;
        }

        //  no idea but don't let them in
        return null;
    }

    private function getMessage(string $username, string $password): mixed
    {
        $credentials = [
            'login' => $username,
            'password' => $password,
        ];

        $headers = [
            'XF-Api-Key' => config('tracker.auth.xenforo.key'),
            'XF-Api-User' => config('tracker.auth.xenforo.user'),
        ];

        $response = Http::withHeaders($headers)->post(config('tracker.auth.xenforo.url'), $credentials);

        $message = json_decode($response->body(), false);

        return $message;
    }
}