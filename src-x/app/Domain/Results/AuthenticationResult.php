<?php

declare(strict_types=1);

namespace App\Domain\Results;

use App\Utilities\PayloadableTrait;

/**
 * Represents the result of a login attempt from the AuthenticationService.
 */
final class AuthenticationResult extends BaseResult
{
    use PayloadableTrait;

    /**
     * LoginResult constructor.
     *
     */
    private function __construct(
        private readonly bool $is_success,
        private readonly ?string $message = null,
        public readonly ?string $username = null,
        public readonly ?int $user_id = null,
        public readonly ?string $email = null,
        public readonly ?bool $is_banned = null,
    ) {
    }


    /**
     * Creates a success LoginResult.
     *
     * @param string $message The reason for the failure.
     * @return self
     */
    public static function success(string $username, string $email, int $user_id): self
    {
        $result = new AuthenticationResult(
            is_success: true,
            username: $username,
            user_id: $user_id,
            email: $email
        );

        return $result;
    }

    /**
     * Creates a failed LoginResult.
     *
     * @param string $message The reason for the failure.
     * @return self
     */
    public static function failed(string $message): self
    {
        return new self(
            false,
            $message,
        );
    }

    /**
     * Creates a banned LoginResult.
     *
     * @return self
     */
    public static function banned(string $username, string $email, int $user_id): self
    {
        return new self(
            is_success: true,
            is_banned: true,
            username: $username,
            user_id: $user_id,
            email: $email,
            message: 'You are currently banned. Please refer to command staff for additional information.'
        );
    }
}