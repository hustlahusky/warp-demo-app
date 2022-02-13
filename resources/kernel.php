<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Infrastructure\Kernel;
use function spaceonfire\Common\Env\env;

defined('ROOT_DIR') or define('ROOT_DIR', dirname(__DIR__));
defined('SOF_ENV_PATH') or define('SOF_ENV_PATH', ROOT_DIR);
defined('CONFIG_DIR') or define('CONFIG_DIR', __DIR__ . '/config');
defined('APPLICATION_ENV') or define('APPLICATION_ENV', env('APPLICATION_ENV', 'production'));

// @phpstan-ignore-next-line
return static fn () => new Kernel('production' === APPLICATION_ENV, 'development' === APPLICATION_ENV);
