<?php

declare(strict_types=1);

namespace App\Infrastructure\Http;

use App\Infrastructure\Service\Bootstrap\BootstrapAggregate;
use App\Infrastructure\Service\Bootstrap\BootstrapInterface;
use App\Infrastructure\Service\Reset\ResetAggregate;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\ResponseEmitter;
use Symfony\Contracts\Service\ResetInterface;
use Warp\Common\Factory\StaticConstructorInterface;

final class DefaultWorker implements BootstrapInterface, ResetInterface, RequestHandlerInterface, StaticConstructorInterface
{
    private RequestHandlerInterface $handler;

    private ResponseEmitter $responseEmitter;

    private bool $resetBeforeRespond = false;

    public function __construct(
        private ContainerInterface $container,
    ) {
        $this->handler = $this->container->get(RequestHandlerInterface::class);
        $this->responseEmitter = new ResponseEmitter();
    }

    public static function new(
        ContainerInterface $container,
    ): self {
        return new self($container);
    }

    public function boot(): void
    {
        if ($this->container->has(BootstrapAggregate::class)) {
            $this->container->get(BootstrapAggregate::class)->boot();
        }
    }

    public function reset(): void
    {
        if ($this->container->has(ResetAggregate::class)) {
            $this->container->get(ResetAggregate::class)->reset();
        }

        \gc_collect_cycles();
    }

    public function respond(ResponseInterface $response): void
    {
        $this->responseEmitter->emit($response);
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $response = $this->handler->handle($request);

        if ($this->resetBeforeRespond) {
            $this->reset();
        }

        return $response;
    }
}
