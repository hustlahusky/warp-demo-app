<?php

declare(strict_types=1);

namespace App\Infrastructure\DependencyInjection;

use App\Infrastructure\Event\PetCreatedEventSubscriber;
use Warp\Container\ServiceProvider\AbstractServiceProvider;

final class EventSubscribersProvider extends AbstractServiceProvider
{
    public function provides(): iterable
    {
        yield PetCreatedEventSubscriber::class;
        yield DefinitionTag::EVENT_SUBSCRIBER;
    }

    public function register(): void
    {
        $this->getContainer()->define(PetCreatedEventSubscriber::class)->addTag(DefinitionTag::EVENT_SUBSCRIBER);
    }
}
