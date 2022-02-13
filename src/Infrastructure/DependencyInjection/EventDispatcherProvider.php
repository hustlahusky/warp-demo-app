<?php

declare(strict_types=1);

namespace App\Infrastructure\DependencyInjection;

use Psr\EventDispatcher\EventDispatcherInterface as PsrEventDispatcherInterface;
use spaceonfire\Container\ServiceProvider\AbstractServiceProvider;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface as SymfonyContractsEventDispatcherInterface;

final class EventDispatcherProvider extends AbstractServiceProvider
{
    private EventDispatcher|null $dispatcher = null;

    public function provides(): array
    {
        return [
            PsrEventDispatcherInterface::class,
            SymfonyContractsEventDispatcherInterface::class,
            EventDispatcherInterface::class,
            EventDispatcher::class,
        ];
    }

    public function register(): void
    {
        $this->getContainer()->define(PsrEventDispatcherInterface::class, EventDispatcher::class);
        $this->getContainer()->define(SymfonyContractsEventDispatcherInterface::class, EventDispatcher::class);
        $this->getContainer()->define(EventDispatcherInterface::class, EventDispatcher::class);
        $this->getContainer()->define(EventDispatcher::class, [$this, 'makeEventDispatcher'], true);
    }

    public function makeEventDispatcher(): EventDispatcher
    {
        if (null !== $this->dispatcher) {
            return $this->dispatcher;
        }

        $this->dispatcher = new EventDispatcher();

        $subscribers = $this->getContainer()->getTagged(DefinitionTag::EVENT_SUBSCRIBER);

        foreach ($subscribers as $subscriber) {
            if ($subscriber instanceof EventSubscriberInterface) {
                $this->dispatcher->addSubscriber($subscriber);
            }
        }

        return $this->dispatcher;
    }
}
