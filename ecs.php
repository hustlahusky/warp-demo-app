<?php

declare(strict_types=1);

namespace App;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\EasyCodingStandard\ValueObject\Option;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();

    $parameters->set(Option::CACHE_DIRECTORY, \sys_get_temp_dir() . '/.ecs_cache');
    $parameters->set(Option::PARALLEL, true);

    $parameters->set(Option::PATHS, [
        __DIR__ . '/bin/console',
        __DIR__ . '/resources',
        __DIR__ . '/src',
    ]);

    $parameters->set(Option::SKIP, [
        'Unused variable $_.' => null,
    ]);

    $containerConfigurator->import(__DIR__ . '/vendor/spaceonfire/easy-coding-standard-bridge/resources/config/spaceonfire.php', null, 'not_found');
};
