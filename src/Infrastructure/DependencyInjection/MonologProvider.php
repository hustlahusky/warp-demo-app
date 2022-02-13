<?php

declare(strict_types=1);

namespace App\Infrastructure\DependencyInjection;

use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use spaceonfire\Container\ServiceProvider\AbstractServiceProvider;

final class MonologProvider extends AbstractServiceProvider
{
    public function provides(): array
    {
        return [
            LoggerInterface::class,
            Logger::class,
        ];
    }

    public function register(): void
    {
        $this->getContainer()->define(LoggerInterface::class, Logger::class);
        $this->getContainer()->define(Logger::class, [$this, 'makeLogger'], true);
    }

    public function makeLogger(): Logger
    {
        $monolog = new Logger('app');

        $handler = new StreamHandler('php://stderr');
        $handler->setFormatter(
            new JsonFormatter(JsonFormatter::BATCH_MODE_NEWLINES, true, true)
        );

        $monolog->pushHandler($handler);

        foreach ($this->getContainer()->getTagged(DefinitionTag::MONOLOG_PROCESSOR) as $processor) {
            if (\is_callable($processor)) {
                $monolog->pushProcessor($processor);
            }
        }

        return $monolog;
    }
}
