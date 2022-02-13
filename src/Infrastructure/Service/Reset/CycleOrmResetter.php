<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\Reset;

use Cycle\ORM\ORMInterface;
use Psr\Container\ContainerInterface;
use Symfony\Contracts\Service\ResetInterface;

final class CycleOrmResetter implements ResetInterface
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function reset(): void
    {
        if (!$this->container->has(ORMInterface::class)) {
            return;
        }

        $this->container->get(ORMInterface::class)->getHeap()->clean();
    }
}
