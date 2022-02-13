<?php

declare(strict_types=1);

namespace App\Application\User;

use App\Application\Response\ItemResponse;
use App\Domain\User\User;
use spaceonfire\DataSource\EntityReaderAggregateInterface;

final class FetchUserQueryHandler
{
    public function __construct(
        private EntityReaderAggregateInterface $readerAggregate,
    ) {
    }

    /**
     * @param FetchUserQuery $query
     * @return ItemResponse<User>
     */
    public function __invoke(FetchUserQuery $query): ItemResponse
    {
        $user = $this->readerAggregate->getReader(User::class)->findByPrimary($query->getUserId());

        return new ItemResponse($user);
    }
}
