<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controllers;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class HomepageController implements RequestHandlerInterface
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $response = $this->responseFactory->createResponse();
        $response->getBody()->write('Hello, World!');
        return $response;
    }
}
