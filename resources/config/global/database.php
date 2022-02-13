<?php

declare(strict_types=1);

use Spiral\Database\Driver\Postgres\PostgresDriver;
use function spaceonfire\Common\Connection\buildDsn;
use function spaceonfire\Common\Env\env;

return [
    'database' => [
        'default' => 'default',
        'databases' => [
            'default' => [
                'connection' => 'pgsql',
            ],
        ],
        'connections' => [
            'pgsql' => [
                'driver' => PostgresDriver::class,
                'options' => [
                    'connection' => buildDsn([
                        'host' => env('DB_HOST', 'database'),
                        'port' => env('DB_PORT', '5432'),
                        'dbname' => env('DB_NAME', 'demo'),
                    ], 'pgsql'),
                    'username' => env('DB_USER', 'demo'),
                    'password' => env('DB_PASSWORD', 'password'),
                ],
            ],
        ],
    ],
];
