<?php

declare(strict_types=1);

namespace App\Infrastructure\DependencyInjection;

use App\Domain\ClockFacade;
use spaceonfire\Container\ServiceProvider\AbstractServiceProvider;

final class ClockProvider extends AbstractServiceProvider
{
    public function provides(): iterable
    {
        return [
            ClockFacade::class,
            DefinitionTag::RESET,
        ];
    }

    public function register(): void
    {
        $this->getContainer()->define(ClockFacade::class, shared: true)->addTag(DefinitionTag::RESET);
    }
}
