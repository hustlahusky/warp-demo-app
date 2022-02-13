<?php

declare(strict_types=1);

namespace App\Application\Response;

use spaceonfire\Collection\CollectionInterface;
use spaceonfire\Criteria\CriteriaInterface;
use spaceonfire\DataSource\EntityReaderInterface;

final class ListResponseFactory
{
    /**
     * @template T
     * @param CollectionInterface<T> $collection
     * @param CriteriaInterface|null $criteria
     * @return ListResponse<T>
     */
    public function fromCollection(CollectionInterface $collection, ?CriteriaInterface $criteria = null): ListResponse
    {
        $count = $this->getTotalCount($collection, $criteria);
        $items = 0 === $count ? [] : $this->getItems($collection, $criteria);
        return new ListResponse($items, $count);
    }

    /**
     * @template T of object
     * @param EntityReaderInterface<T> $reader
     * @param CriteriaInterface|null $criteria
     * @return ListResponse<T>
     */
    public function fromReader(EntityReaderInterface $reader, ?CriteriaInterface $criteria = null): ListResponse
    {
        $count = $reader->count($criteria);
        $items = 0 === $count ? [] : $reader->findAll($criteria);
        return new ListResponse($items, $count);
    }

    /**
     * @template T
     * @param CollectionInterface<T> $collection
     * @param CriteriaInterface|null $criteria
     * @return CollectionInterface<T>
     */
    private function getItems(CollectionInterface $collection, ?CriteriaInterface $criteria): CollectionInterface
    {
        return null === $criteria ? $collection : $collection->matching($criteria);
    }

    /**
     * @param CollectionInterface<mixed> $collection
     * @param CriteriaInterface|null $criteria
     * @return int
     */
    private function getTotalCount(CollectionInterface $collection, ?CriteriaInterface $criteria): int
    {
        if (null === $criteria) {
            return $collection->count();
        }

        return $collection->matching($criteria->limit(null)->offset(null))->count();
    }
}
