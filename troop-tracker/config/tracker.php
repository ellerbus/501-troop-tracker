<?php

declare(strict_types=1);

return [
    'auth' => [
        'type' => env('TRACKER_AUTH_TYPE', 'standalone'),
        'xenforo' => [
            'url' => env('TRACKER_XENFORO_URL', ''),
            'key' => env('TRACKER_XENFORO_KEY', ''),
            'user' => env('TRACKER_XENFORO_USER', ''),
        ],
    ],
    'forum' => [
        'display_name' => env('TRACKER_FORUM_NAME', 'Default Forum'),
        'url' => env('TRACKER_FORUM_URL', 'https://example.com'),
        'webmaster' => env('TRACKER_WEBMASTER', 'anyone@example.com'),
    ]
];