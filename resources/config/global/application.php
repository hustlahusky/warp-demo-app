<?php

declare(strict_types=1);

use Laminas\ConfigAggregator\ConfigAggregator;
use function spaceonfire\Common\Env\env;

return [
    ConfigAggregator::ENABLE_CACHE => 'production' === APPLICATION_ENV,

    'application' => [
        'name' => env('APPLICATION_NAME', 'Demo App'),
        'domain' => env('APPLICATION_DOMAIN', 'localhost'),
    ],
];
