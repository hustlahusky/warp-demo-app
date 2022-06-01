<?php

declare(strict_types=1);

return [
    'migration' => [
        'directory' => MIGRATIONS_DIR,
        'table' => 'migrations',
        'safe' => false,
        'namespace' => 'Migration',
    ],
];
