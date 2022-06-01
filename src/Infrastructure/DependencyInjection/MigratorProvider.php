<?php

declare(strict_types=1);

namespace App\Infrastructure\DependencyInjection;

use Cycle\Migrations\Config\MigrationConfig;
use Warp\Bridge\Cycle\Migrator\AbstractMigratorProvider;

final class MigratorProvider extends AbstractMigratorProvider
{
    public function makeMigrationConfig(): MigrationConfig
    {
        $config = $this->getContainer()->get(ConfigProvider::ID);
        return new MigrationConfig($config[MigrationConfig::CONFIG]);
    }
}
