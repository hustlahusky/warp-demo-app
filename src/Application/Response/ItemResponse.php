<?php

declare(strict_types=1);

namespace App\Application\Response;

use spaceonfire\Common\CQRS\Query\ResponseInterface;

/**
 * @template T
 */
final class ItemResponse implements ResponseInterface
{
    /**
     * @var T|null
     */
    private mixed $item;

    /**
     * @param T|null $item
     */
    public function __construct(mixed $item)
    {
        $this->item = $item;
    }

    /**
     * @return T|null
     */
    public function getItem(): mixed
    {
        return $this->item;
    }
}
