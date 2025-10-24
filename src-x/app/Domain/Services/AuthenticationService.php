<?php

declare(strict_types=1);

namespace App\Domain\Services;

use App\Domain\Models\TrooperModel;
use App\Domain\Repositories\TrooperRepository;
use App\Payloads\LoginPayload;
use App\Domain\Results\AuthenticationResult;
use App\Domain\Results\LoginStatus;
use App\Utilities\Configuration;
use App\Utilities\HttpClient;
use Exception;
use PDO;

/**
 * Class AuthenticationService.
 *
 * Handles user authentication logic, including login and logout processes.
 */
class AuthenticationService
{
    /**
     * AuthenticationService constructor.
     *
     * @param PDO $db The database connection.
     */
    public function __construct(
        private readonly PDO $db,
        private readonly Configuration $configuration,
        private readonly HttpClient $client,
    ) {
    }

    /**
     * Logs the user out.
     *
     * This method clears all session data, destroys the session, and removes
     * any authentication-related cookies.
     */
    public function logout(): void
    {
        // Unset all of the session variables.
        $_SESSION = [];

        // Destroy any 'remember me' cookies that may have been set.
        if (isset($_COOKIE['TroopTrackerUsername']) && isset($_COOKIE['TroopTrackerPassword']))
        {
            setcookie('TroopTrackerUsername', '', time() - 3600);
            setcookie('TroopTrackerPassword', '', time() - 3600);
        }

        // Finally, destroy the session.
        session_destroy();
    }

    /**
     * Attempts to log a user in with the provided credentials.
     *
     * @param LoginPayload $payload The data object containing login credentials.
     *
     * @return AuthenticationResult The result of the login attempt.
     */
    public function login(LoginPayload $payload): AuthenticationResult
    {
        try
        {
            //  TODO figure out plugins
            $result = $this->loginXenforo($payload);

            return $result;
        }
        catch (Exception $e)
        {
            return AuthenticationResult::failed($e->getMessage());
        }
    }

    /**
     * Attempts to log a user in with the provided credentials.
     */
    public function isLoggedIn(): bool
    {
        return isset($_SESSION['id']);
    }

    private function loginXenforo(LoginPayload $payload): AuthenticationResult
    {
        $url = $this->configuration->get('plugins.xenforo.url');

        $data = [
            'login' => $payload->getUsername(),
            'password' => $payload->getPassword(),
        ];

        $headers = [
            'XF-Api-Key: ' . $this->configuration->get('plugins.xenforo.key'),
            'XF-Api-User: ' . $this->configuration->get('plugins.xenforo.user'),
        ];

        $response = $this->client->post($url, $data, $headers);

        $message = json_decode($response);

        if (!$message->success)
        {
            return AuthenticationResult::failed($message->message);
        }

        if ($message->user->is_banned)
        {
            return AuthenticationResult::banned(
                username: $message->user->username,
                email: $message->user->email,
                user_id: $message->user->user_id
            );
        }

        $result = AuthenticationResult::success(
            username: $message->user->username,
            email: $message->user->email,
            user_id: $message->user->user_id
        );

        return $result;
    }
}