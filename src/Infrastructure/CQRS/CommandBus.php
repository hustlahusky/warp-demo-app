<?php

declare(strict_types=1);

namespace App\Infrastructure\CQRS;

use Psr\Container\ContainerInterface;
use spaceonfire\CommandBus\CommandBus as MessageBus;
use spaceonfire\CommandBus\Mapping\ClassName\SuffixClassNameMapping;
use spaceonfire\CommandBus\Mapping\CompositeMapping;
use spaceonfire\CommandBus\Mapping\Method\StaticMethodNameMapping;
use spaceonfire\CommandBus\Middleware\Logger\LoggerMiddleware;
use spaceonfire\Common\CQRS\Command\AbstractCommandBus;
use spaceonfire\Container\FactoryAggregateInterface;
use spaceonfire\Container\InstanceOfAliasContainer;

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
