<?php

declare(strict_types=1);

namespace App\Domain;

use Cycle\ORM\Promise\ReferenceInterface;
use PhpOption\Option;
use Warp\Bridge\Cycle\EntityReference;
use Warp\DataSource\EntityReferenceInterface;

abstract class EntityReferenceHelper
{
    /**
     * @template T of object
     * @param T|null $entity
     * @param ReferenceInterface|null $reference
     * @return Option<EntityReferenceInterface<T>>
     */
    public static function fromEntity(?object $entity, ?ReferenceInterface $reference = null): Option
    {
        /** @phpstan-var EntityReferenceInterface<T>|null $ref */
        $ref = null === $entity ? null : EntityReference::fromEntity($entity, $reference);
        /** @phpstan-var Option<EntityReferenceInterface<T>> $option */
        $option = Option::fromValue($ref);
        \assert($option instanceof Option);
        return $option;
    }

    /**
     * @template E of object
     * @param EntityReferenceInterface<E>|null $left
     * @param EntityReferenceInterface<E>|null $right
     * @return bool
     */
    public static function equals(?EntityReferenceInterface $left, ?EntityReferenceInterface $right): bool
    {
        if (null === $left || null === $right) {
            return $left === $right;
        }

        return $left->equals($right);
    }
}
