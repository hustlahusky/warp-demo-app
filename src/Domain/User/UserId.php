<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\IdentityInterface;
use spaceonfire\ValueObject\UuidValue;

final class UserId extends UuidValue implements IdentityInterface
{
    public const ROLE = 'user';

    public function __role(): string
    {
        return self::ROLE;
    }

    /**
     * @inheritDoc
     * @return array{id:string}
     */
    public function __scope(): array
    {
        return [
            'id' => $this->value(),
        ];
    }
}
