<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\Reset;

use Symfony\Contracts\Service\ResetInterface;
use Warp\Container\DefinitionAggregateInterface;

/**
 * @implements \IteratorAggregate<ResetInterface>
 */
final class ResetAggregate implements ResetInterface, \IteratorAggregate
{
    private DefinitionAggregateInterface $container;

    private string $tag;

    public function __construct(DefinitionAggregateInterface $container, string $tag)
    {
        $this->container = $container;
        $this->tag = $tag;
    }

    /**
     * @return \Generator<ResetInterface>
     */
    public function getIterator(): \Generator
    {
        foreach ($this->container->getTagged($this->tag) as $resetter) {
            if ($resetter instanceof ResetInterface) {
                yield $resetter;
            }
        }
    }

    public function reset(): void
    {
        foreach ($this->getIterator() as $resetter) {
            $resetter->reset();
        }
    }
}
