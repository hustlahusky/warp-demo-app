<?php

declare(strict_types=1);

namespace App\Infrastructure\Event;

use App\Domain\Pet\Events\PetCreatedEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class PetCreatedEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private LoggerInterface $logger
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PetCreatedEvent::class => 'handle',
        ];
    }

    public function handle(PetCreatedEvent $event): void
    {
        $this->logger->info(\sprintf('Pet created: %s.', $event->getPetId()));
    }
}
