<?php

declare(strict_types=1);

namespace App\Infrastructure\DependencyInjection;

use App\Infrastructure\Service\Bootstrap\RouterBootstrap;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Handlers\ErrorHandler;
use Slim\Interfaces\ErrorHandlerInterface;
use spaceonfire\Container\ServiceProvider\AbstractServiceProvider;

final class HttpProvider extends AbstractServiceProvider
{
    public function provides(): array
    {
        return [
            App::class,
            RequestHandlerInterface::class,
            ErrorHandlerInterface::class,
            RouterBootstrap::class,
            DefinitionTag::BOOTSTRAP,
        ];
    }

    public function register(): void
    {
        $this->getContainer()->define(RequestHandlerInterface::class, App::class);
        $this->getContainer()->define(App::class, $app = AppFactory::createFromContainer($this->getContainer()), true);

        $this->getContainer()->define(ResponseFactoryInterface::class, static fn () => $app->getResponseFactory());

        $this->getContainer()->define(ErrorHandlerInterface::class, ErrorHandler::class);
        $this->getContainer()->define(ErrorHandler::class, shared: true)
            ->addArgument('callableResolver', $app->getCallableResolver());

        $this->getContainer()->define(RouterBootstrap::class)->addTag(DefinitionTag::BOOTSTRAP);
    }
}
