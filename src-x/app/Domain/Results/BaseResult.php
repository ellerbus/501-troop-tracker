<?php

declare(strict_types=1);

namespace App\Domain\Results;

/**
 * Represents the result of a login attempt from the AuthenticationService.
 */
abstract class BaseResult
{
    /**
     * Result constructor.
     *
     * @param bool        $is_success      Whether the login attempt was successful.
     * @param string|null $message   An error message if the login failed.
     */
    protected function __construct(
        private readonly bool $is_success,
        private readonly ?string $message = null
    ) {
    }

    /**
     * Checks if the result was successful.
     */
    public function isSuccess(): bool
    {
        return $this->is_success;
    }

    /**
     */
    public function isFailure(): bool
    {
        return $this->is_success;
    }

    /**
     * Gets the error message if the login attempt failed.
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }
}