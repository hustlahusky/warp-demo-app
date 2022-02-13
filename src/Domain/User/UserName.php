<?php

declare(strict_types=1);

namespace App\Domain\User;

final class UserName
{
    public function __construct(
        private ?string $firstName = null,
        private ?string $lastName = null,
    ) {
    }

    public function __toString(): string
    {
        return \implode(' ', \array_filter([
            \trim($this->firstName ?? ''),
            \trim($this->lastName ?? ''),
        ], static fn (string $s) => '' !== $s));
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function equals(self $other): bool
    {
        return $this->firstName === $other->firstName
            && $this->lastName === $other->lastName;
    }
}
