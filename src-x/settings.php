<?php

declare(strict_types=1);

return [
    'db' => [
        'host' => 'localhost',
        'name' => 'trooptracker',
        'user' => 'root',
        'pass' => 'sapass',
    ],
    'forum' => [
        'name' => 'Florida Garrison',
        'url' => 'https://www.fl501st.com/boards/'
    ],
    'twig' => [
        'cache' => false
    ],
    'auth' => 'xenforo',
    'plugins' => [
        'xenforo' => [
            'url' => 'https://www.fl501st.com/boards/api/auth',
            'key' => 'R3PTGQKOypU16bY3aaUlfhmNwK43d7zD',
            'user' => '1',
        ]
    ]
];