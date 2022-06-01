<?php

declare(strict_types=1);

namespace App\Application\User;

use App\Domain\User\UserId;
use Warp\Common\CQRS\Query\QueryInterface;

final class ListUserPetsQuery implements QueryInterface
{
    public function __construct(
        private UserId $userId,
        private ?int $age = null,
    ) {
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }
}
