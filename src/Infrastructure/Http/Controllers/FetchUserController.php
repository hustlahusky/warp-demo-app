<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controllers;

use App\Application\Response\ItemResponse;
use App\Application\User\FetchUserQuery;
use App\Domain\User\User;
use App\Domain\User\UserId;
use App\Infrastructure\Http\RequestOption;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use spaceonfire\Common\CQRS\Query\QueryBusInterface;

final class FetchUserController implements RequestHandlerInterface
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

        /** @var ItemResponse<User> $item */
        $item = $this->queryBus->ask(new FetchUserQuery($userId));
        $user = $item->getItem();
        \assert(null !== $user);

        $response = $this->responseFactory->createResponse();
        $response->getBody()->write(\sprintf('Hello, %s!', $user->getName()));
        return $response;
    }
}
