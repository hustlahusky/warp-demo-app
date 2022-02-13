<?php

declare(strict_types=1);

namespace App\Application\Response;

use spaceonfire\Common\CQRS\Query\ResponseInterface;

/**
 * @template T
 * @implements \IteratorAggregate<array-key,T>
 */
final class ListResponse implements ResponseInterface, \IteratorAggregate
{
    /**
     * @var iterable<T>
     */
    private iterable $items;

    private int $totalCount;

    /**
     * @param iterable<T> $items
     * @param int|null $totalCount
     */
    public function __construct(iterable $items, ?int $totalCount = null)
    {
        [$this->items, $this->totalCount] = $this->resolveItemsAndCount($items, $totalCount);
    }

    /**
     * @return T[]
     */
    public function getItems(): iterable
    {
        return $this->items;
    }

    public function getTotalCount(): int
    {
        return $this->totalCount;
    }

    /**
     * @return \Traversable<T>
     */
    public function getIterator(): \Traversable
    {
        if ($this->items instanceof \Traversable) {
            return $this->items;
        }

        return new \ArrayIterator($this->items);
    }

    /**
     * @param iterable<T> $items
     * @param int|null $totalCount
     * @return array{iterable<T>,int}
     */
    private function resolveItemsAndCount(iterable $items, ?int $totalCount = null): array
    {
        if (null !== $totalCount) {
            return [$items, $totalCount];
        }

        if ($items instanceof \Traversable && !$items instanceof \Countable) {
            $items = \iterator_to_array($items);
        }

        \assert(\is_countable($items));

        return [$items, \count($items)];
    }
}
