<?php

declare(strict_types=1);

use Cycle\Database\Driver\Postgres\PostgresDriver;
use function Warp\Common\Connection\buildDsn;
use function Warp\Common\Env\env;

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
