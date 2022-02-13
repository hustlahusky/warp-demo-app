<?php

declare(strict_types=1);

namespace App\Infrastructure\DependencyInjection;

use App\Infrastructure\Console\GreetingCommand;
use App\Infrastructure\Console\RefreshConfigCommand;
use spaceonfire\Container\ServiceProvider\AbstractServiceProvider;

final class ConsoleProvider extends AbstractServiceProvider
{
    public function provides(): iterable
    {
        return [
            GreetingCommand::class,
            RefreshConfigCommand::class,
            DefinitionTag::CONSOLE_COMMAND,
        ];
    }

    public function register(): void
    {
        $this->getContainer()->define(GreetingCommand::class)->addTag(DefinitionTag::CONSOLE_COMMAND);
        $this->getContainer()->define(RefreshConfigCommand::class)->addTag(DefinitionTag::CONSOLE_COMMAND);
    }
}
