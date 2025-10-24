<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Session;

/**
 * Manages flash messages stored in the session.
 * Flash messages are messages that are stored for one subsequent request.
 */
class FlashMessageService
{
    /**
     * The session key for storing flash messages.
     *
     * @var string
     */
    private const FLASH_KEY = 'flash_messages';

    /**
     * Adds a success flash message.
     *
     * @param string $message The message content.
     */
    public function success(string $message): void
    {
        $this->addMessage('success', $message);
    }

    /**
     * Adds a warning flash message.
     *
     * @param string $message The message content.
     */
    public function warning(string $message): void
    {
        $this->addMessage('warning', $message);
    }

    /**
     * Adds a danger/error flash message.
     *
     * @param string $message The message content.
     */
    public function danger(string $message): void
    {
        $this->addMessage('danger', $message);
    }

    /**
     * Adds a message to the session flash data.
     *
     * @param string $type The type of message (e.g., 'success', 'warning').
     * @param string $message The message content.
     */
    private function addMessage(string $type, string $message): void
    {
        $messages = Session::get(self::FLASH_KEY, []);

        $messages[$type][] = $message;

        Session::flash(self::FLASH_KEY, $messages);
    }

    /**
     * Retrieves all flash messages from the session.
     *
     * @return array<string, array<string>> An associative array where keys are message types
     *                                      and values are arrays of messages.
     *                                      Example: ['success' => ['Profile updated!']]
     */
    public function getMessages(): array
    {
        return Session::get(self::FLASH_KEY, []);
    }

}