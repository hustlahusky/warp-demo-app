<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\Bootstrap;

use Cycle\ORM\ORMInterface;
use Psr\Container\ContainerInterface;

final class CycleOrmWarmBootstrap implements BootstrapInterface
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function boot(): void
    {
        if (!$this->container->has(ORMInterface::class)) {
            return;
        }

        // trigger orm building, warm orm schema cache
        $this->container->get(ORMInterface::class);
    }
}
