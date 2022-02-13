<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controllers;

use App\Application\Response\ListResponse;
use App\Application\User\ListUserPetsQuery;
use App\Domain\Pet\Pet;
use App\Domain\User\UserId;
use App\Infrastructure\Http\RequestOption;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use spaceonfire\Common\CQRS\Query\QueryBusInterface;

final class ListUserPetsController implements RequestHandlerInterface
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private ResponseFactoryInterface $responseFactory,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $userId = RequestOption::attribute($request, 'userId')
            ->map(static fn ($v) => UserId::new($v))
            ->get();
        $age = RequestOption::query($request, 'age')
            ->map(static fn ($v) => (int)$v)
            ->getOrElse(null);

        /** @var ListResponse<Pet> $list */
        $list = $this->queryBus->ask(new ListUserPetsQuery($userId, $age));

        $response = $this->responseFactory->createResponse();
        /** @var Pet $pet */
        foreach ($list as $pet) {
            $response->getBody()->write($pet->getName() . \PHP_EOL);
        }
        return $response;
    }
}
