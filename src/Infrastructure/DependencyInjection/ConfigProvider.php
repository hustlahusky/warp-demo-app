<?php

declare(strict_types=1);

namespace App\Infrastructure\DependencyInjection;

use App\Infrastructure\ConfigFacade;
use Laminas\ConfigAggregator\PhpFileProvider;
use PhpOption\Some;
use Warp\Container\ServiceProvider\AbstractServiceProvider;

final class ConfigProvider extends AbstractServiceProvider
{
    public const ID = 'config';

    public function provides(): array
    {
        return [
            ConfigFacade::class,
            self::ID,
        ];
    }

    public function register(): void
    {
        $facade = new ConfigFacade([
            new PhpFileProvider(CONFIG_DIR . '/global/*.php'),
            new PhpFileProvider(CONFIG_DIR . '/' . APPLICATION_ENV . '/*.php'),
        ]);

        $this->getContainer()->define(ConfigFacade::class, new Some($facade), true);
        $this->getContainer()->define(self::ID, [$facade, 'getMergedConfig'], true);
    }
}
