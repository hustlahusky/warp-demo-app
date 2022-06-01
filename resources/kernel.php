<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Infrastructure\Kernel;
use function Warp\Common\Env\env;

defined('ROOT_DIR') or define('ROOT_DIR', dirname(__DIR__));
defined('CONFIG_DIR') or define('CONFIG_DIR', __DIR__ . '/config');
defined('MIGRATIONS_DIR') or define('MIGRATIONS_DIR', __DIR__ . '/migrations');
defined('SOF_ENV_PATH') or define('SOF_ENV_PATH', ROOT_DIR);
defined('APPLICATION_ENV') or define('APPLICATION_ENV', env('APPLICATION_ENV', 'production'));

return static fn () => new Kernel('production' === APPLICATION_ENV, 'development' === APPLICATION_ENV);
