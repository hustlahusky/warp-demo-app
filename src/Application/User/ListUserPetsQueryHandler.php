<?php

declare(strict_types=1);

namespace App\Application\User;

use App\Application\Response\ListResponse;
use App\Application\Response\ListResponseFactory;
use App\Domain\Pet\Pet;
use App\Domain\Pet\Specification\FindByAge;
use App\Domain\User\User;
use Warp\Criteria\Criteria;
use Warp\DataSource\EntityReaderAggregateInterface;

final class ListUserPetsQueryHandler
{
    public function __construct(
        private EntityReaderAggregateInterface $readerAggregate,
        private ListResponseFactory $responseFactory,
    ) {
    }

    /**
     * @return ListResponse<Pet>
     */
    public function __invoke(ListUserPetsQuery $query): ListResponse
    {
        $user = $this->readerAggregate->getReader(User::class)->findByPrimary($query->getUserId());

        $criteria = null !== $query->getAge() ? Criteria::new(FindByAge::greaterThan($query->getAge())) : null;

        return $this->responseFactory->fromCollection($user->getPets(), $criteria);
    }
}
