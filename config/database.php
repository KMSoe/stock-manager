<?php

return [
    'default_connection' => $_ENV['DB_CONNECTION'] ?? 'mysql',

    'mysql'              => [
        'host'     => $_ENV['DB_HOST'] ?? '127.0.0.1',
        'port'     => $_ENV['DB_PORT'] ?? 3306,
        'database' => $_ENV['DB_DATABASE'],
        'user'     => $_ENV['DB_USERNAME'],
        'password' => $_ENV['DB_PASSWORD'],
    ],
];
