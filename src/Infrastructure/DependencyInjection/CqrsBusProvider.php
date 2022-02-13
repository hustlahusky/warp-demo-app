<?php

declare(strict_types=1);

namespace App\Infrastructure\DependencyInjection;

use App\Infrastructure\CQRS\CommandBus;
use App\Infrastructure\CQRS\QueryBus;
use spaceonfire\Common\CQRS\Command\CommandBusInterface;
use spaceonfire\Common\CQRS\Query\QueryBusInterface;
use spaceonfire\Container\ServiceProvider\AbstractServiceProvider;

final class CqrsBusProvider extends AbstractServiceProvider
{
    public function provides(): array
    {
        return [
            CommandBusInterface::class,
            CommandBus::class,
            QueryBusInterface::class,
            QueryBus::class,
        ];
    }

    public function register(): void
    {
        $this->getContainer()->define(CommandBusInterface::class, CommandBus::class);
        $this->getContainer()->define(CommandBus::class, shared: true);

        $this->getContainer()->define(QueryBusInterface::class, QueryBus::class);
        $this->getContainer()->define(QueryBus::class, shared: true);
    }
}
