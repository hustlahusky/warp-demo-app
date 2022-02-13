<?php

declare(strict_types=1);

namespace App\Infrastructure\DependencyInjection;

use App\Infrastructure\Service\Bootstrap\BootstrapAggregate;
use App\Infrastructure\Service\Reset\CycleOrmResetter;
use App\Infrastructure\Service\Reset\MonologResetter;
use App\Infrastructure\Service\Reset\ResetAggregate;
use spaceonfire\Container\ServiceProvider\AbstractServiceProvider;

final class ServiceBootstrapResetProvider extends AbstractServiceProvider
{
    public function provides(): iterable
    {
        return [
            CycleOrmResetter::class,
            MonologResetter::class,
            DefinitionTag::RESET,
            ResetAggregate::class,
            BootstrapAggregate::class,
        ];
    }

    public function register(): void
    {
        $this->getContainer()->define(CycleOrmResetter::class)->addTag(DefinitionTag::RESET);
        $this->getContainer()->define(MonologResetter::class)->addTag(DefinitionTag::RESET);
        $this->getContainer()->define(ResetAggregate::class)
            ->addArgument('container', $this->getContainer())
            ->addArgument('tag', DefinitionTag::RESET);

        $this->getContainer()->define(BootstrapAggregate::class)
            ->addArgument('container', $this->getContainer())
            ->addArgument('tag', DefinitionTag::BOOTSTRAP);
    }
}
