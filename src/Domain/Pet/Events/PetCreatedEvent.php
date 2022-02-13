<?php

declare(strict_types=1);

namespace App\Domain\Pet\Events;

use App\Domain\Pet\PetId;
use Symfony\Contracts\EventDispatcher\Event;

final class PetCreatedEvent extends Event
{
    private PetId $petId;

    public function __construct(PetId $petId)
    {
        $this->petId = $petId;
    }

    public function getPetId(): PetId
    {
        return $this->petId;
    }
}
