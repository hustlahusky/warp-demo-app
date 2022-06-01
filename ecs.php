<?php

declare(strict_types=1);

namespace App;

use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Option;

return static function (ECSConfig $containerConfigurator): void {
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

    $containerConfigurator->import(__DIR__ . '/vendor/getwarp/easy-coding-standard-bridge/resources/config/warp.php', null, 'not_found');
};
