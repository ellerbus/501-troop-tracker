<?php

declare(strict_types=1);

namespace App\Domain\Results;

use App\Utilities\PayloadableTrait;

/**
 * Represents the result of a login attempt from the AuthenticationService.
 */
final class LoginResult extends BaseResult
{
    use PayloadableTrait;

    /**
     * LoginResult constructor.
     *
     * @param LoginStatus $status The status of the login attempt.
     * @param string|null $error_message An error message if the login failed.
     * @param string $username The username from the login attempt.
     * @param int $user_id The user ID from the login attempt.
     * @param string $email The email address from the login attempt.
     */
    private function __construct(
        public readonly LoginStatus $status,
        public readonly string $message = '',
        public readonly string $username = '',
        public readonly int $user_id = 0,
        public readonly string $email = '',
    ) {
        parent::__construct($status == LoginStatus::Success, $message);
    }

    /**
     * Creates a success LoginResult.
     *
     * @param string $error_message The reason for the failure.
     * @return self
     */
    public static function success(string $username, string $email, int $user_id): self
    {
        $result = new LoginResult(
            LoginStatus::Success,
            '',
            $username,
            $user_id,
            $email
        );

        return $result;
    }

    /**
     * Creates a failed LoginResult.
     *
     * @param string $error_message The reason for the failure.
     * @return self
     */
    public static function failed(string $error_message): self
    {
        return new self(LoginStatus::Failed, $error_message);
    }

    /**
     * Creates a banned LoginResult.
     *
     * @return self
     */
    public static function banned(): self
    {
        return new self(LoginStatus::Banned, 'You are currently banned. Please refer to command staff for additional information.');
    }

    /**
     * Creates a not-approved LoginResult.
     *
     * @return self
     */
    public static function notApproved(): self
    {
        return new self(LoginStatus::NotApproved, 'Your access has not been approved yet.');
    }

    /**
     * Creates a not-found LoginResult.
     *
     * @return self
     */
    public static function notFound(): self
    {
        return new self(LoginStatus::NotFound, 'User not found.');
    }

    /**
     * Creates a retired LoginResult.
     *
     * @return self
     */
    public static function retired(): self
    {
        return new self(LoginStatus::Retired, 'You cannot use this account.');
    }

}