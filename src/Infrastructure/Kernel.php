<?php

declare(strict_types=1);

namespace App\Infrastructure;

use spaceonfire\Common\Kernel\AbstractKernel;
use spaceonfire\Common\Kernel\ConsoleApplicationConfiguratorTrait;

final class Kernel extends AbstractKernel
{
    use ConsoleApplicationConfiguratorTrait;

    private bool $productionMode;

    public function __construct(bool $productionMode = true, bool $debugModeEnabled = false)
    {
        $this->productionMode = $productionMode;

        parent::__construct(null, $debugModeEnabled && !$productionMode);

        $this->container->define('kernel.production', [$this, 'isInProductionMode']);
    }

    public function isInProductionMode(): bool
    {
        return $this->productionMode;
    }

    protected function loadServiceProviders(): iterable
    {
        yield DependencyInjection\ConfigProvider::class;
        yield DependencyInjection\ConsoleProvider::class;
        yield DependencyInjection\LockProvider::class;
        yield DependencyInjection\CycleOrmProvider::class;
        yield DependencyInjection\MonologProvider::class;
        yield DependencyInjection\CqrsBusProvider::class;
        yield DependencyInjection\HttpProvider::class;
        yield DependencyInjection\EventDispatcherProvider::class;
        yield DependencyInjection\EventSubscribersProvider::class;
        yield DependencyInjection\ServiceBootstrapResetProvider::class;
        yield DependencyInjection\ClockProvider::class;
    }
}
