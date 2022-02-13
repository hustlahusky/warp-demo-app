<?php

declare(strict_types=1);

namespace App\Domain\User\Events;

use App\Domain\User\UserId;
use Symfony\Contracts\EventDispatcher\Event;

final class UserCreatedEvent extends Event
{
    private UserId $userId;

    public function __construct(UserId $userId)
    {
        $this->userId = $userId;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }
}
