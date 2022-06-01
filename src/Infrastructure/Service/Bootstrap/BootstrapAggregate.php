<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\Bootstrap;

use Warp\Container\DefinitionAggregateInterface;

/**
 * @implements \IteratorAggregate<BootstrapInterface>
 */
final class BootstrapAggregate implements BootstrapInterface, \IteratorAggregate
{
    private DefinitionAggregateInterface $container;

    private string $tag;

    public function __construct(DefinitionAggregateInterface $container, string $tag)
    {
        $this->container = $container;
        $this->tag = $tag;
    }

    /**
     * @return \Generator<BootstrapInterface>
     */
    public function getIterator(): \Generator
    {
        foreach ($this->container->getTagged($this->tag) as $service) {
            if ($service instanceof BootstrapInterface) {
                yield $service;
            }
        }
    }

    public function boot(): void
    {
        foreach ($this->getIterator() as $service) {
            $service->boot();
        }
    }
}
