<?php

declare(strict_types=1);

namespace App\Infrastructure\DependencyInjection;

use spaceonfire\Container\ServiceProvider\AbstractServiceProvider;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Lock\PersistingStoreInterface;
use Symfony\Component\Lock\Store\FlockStore;

final class LockProvider extends AbstractServiceProvider
{
    public function provides(): iterable
    {
        return [
            PersistingStoreInterface::class,
            FlockStore::class,
            LockFactory::class,
        ];
    }

    public function register(): void
    {
        $this->getContainer()->define(PersistingStoreInterface::class, FlockStore::class);
        $this->getContainer()->define(FlockStore::class, shared: true);
        $this->getContainer()->define(LockFactory::class, shared: true);
    }
}
