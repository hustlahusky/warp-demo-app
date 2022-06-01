<?php

declare(strict_types=1);

namespace App\Infrastructure\CQRS;

use Psr\Container\ContainerInterface;
use Warp\CommandBus\CommandBus as MessageBus;
use Warp\CommandBus\Mapping\ClassName\SuffixClassNameMapping;
use Warp\CommandBus\Mapping\CompositeMapping;
use Warp\CommandBus\Mapping\Method\StaticMethodNameMapping;
use Warp\CommandBus\Middleware\Logger\LoggerMiddleware;
use Warp\Common\CQRS\Command\AbstractCommandBus;
use Warp\Container\FactoryAggregateInterface;
use Warp\Container\InstanceOfAliasContainer;

final class CommandBus extends AbstractCommandBus
{
    public function __construct(ContainerInterface $container)
    {
        parent::__construct(new MessageBus(
            new CompositeMapping(
                new SuffixClassNameMapping('Handler'),
                new StaticMethodNameMapping('__invoke')
            ),
            [
                $container->get(LoggerMiddleware::class),
            ],
            InstanceOfAliasContainer::wrap($container)->get(FactoryAggregateInterface::class),
        ));
    }
}
