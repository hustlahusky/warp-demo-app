<?php

declare(strict_types=1);

namespace App\Domain\Pet;

use App\Domain\IdentityInterface;
use Warp\ValueObject\UuidValue;

final class PetId extends UuidValue implements IdentityInterface
{
    public const ROLE = 'pet';

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
