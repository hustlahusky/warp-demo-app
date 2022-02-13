<?php

declare(strict_types=1);

namespace App\Application\User;

use App\Domain\User\UserId;
use spaceonfire\Common\CQRS\Query\QueryInterface;

final class FetchUserQuery implements QueryInterface
{
    public function __construct(
        private UserId $userId,
    ) {
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }
}
