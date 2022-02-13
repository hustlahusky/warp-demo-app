<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\Reset;

use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Symfony\Contracts\Service\ResetInterface;

final class MonologResetter implements ResetInterface
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function reset(): void
    {
        if (!$this->container->has(Logger::class)) {
            return;
        }

        $this->container->get(Logger::class)->reset();
    }
}
