<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\Bootstrap;

use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Handlers\Strategies\RequestHandler;
use Symfony\Component\Finder\Finder;

final class RouterBootstrap implements BootstrapInterface
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function boot(): void
    {
        if (!$this->container->has(App::class)) {
            return;
        }

        $app = $this->container->get(App::class);

        $app->getRouteCollector()->setDefaultInvocationStrategy(new RequestHandler(true));

        $this->loadRoutes($app);

        $app->addRoutingMiddleware();
        $app->addErrorMiddleware($this->isDebugModeEnabled(), true, true);
    }

    private function loadRoutes(App $app): void
    {
        $files = Finder::create()->files()->in(ROOT_DIR . '/resources/routes')->name('*.php');
        foreach ($files as $file) {
            $callback = require $file->getPathname();
            \assert(\is_callable($callback));
            $callback($app);
        }
    }

    private function isDebugModeEnabled(): bool
    {
        return $this->container->has('kernel.debug') && $this->container->get('kernel.debug');
    }
}
