<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\Pet\Pet;
use Warp\Collection\Collection;
use Warp\Collection\CollectionInterface;
use Warp\DataSource\Blame\BlamableInterface;
use Warp\DataSource\Blame\Blame;
use Warp\DataSource\Blame\BlameImmutable;
use Warp\DataSource\Blame\BlameImmutableInterface;
use Warp\DataSource\Blame\BlameInterface;
use Warp\DataSource\EntityEventsInterface;
use Warp\DataSource\EntityEventsTrait;
use Warp\ValueObject\EmailValue;

/**
 * @implements BlamableInterface<User>
 */
final class User implements BlamableInterface, EntityEventsInterface
{
    use EntityEventsTrait;

    /**
     * @var CollectionInterface<Pet>
     */
    private CollectionInterface $pets;

    /**
     * @var BlameInterface<self>
     */
    private BlameInterface $blame;

    private function __construct(
        private UserId $id,
        private EmailValue $email,
        private UserName $name,
    ) {
        $this->pets = Collection::new();
        // @phpstan-ignore-next-line
        $this->blame = Blame::new(self::class);

        $this->recordEvent(new Events\UserCreatedEvent($this->id));
    }

    public static function new(
        EmailValue $email,
        ?UserName $name = null,
        ?UserId $id = null,
    ): self {
        return new self($id ?? UserId::random(), $email, $name ?? new UserName());
    }

    public function getId(): UserId
    {
        return $this->id;
    }

    public function getEmail(): EmailValue
    {
        return $this->email;
    }

    public function changeEmail(EmailValue $email): void
    {
        if ($email === $this->email) {
            return;
        }

        $this->email = $email;
        $this->blame(null, true);
    }

    public function getName(): UserName
    {
        return $this->name;
    }

    public function changeName(UserName $name): void
    {
        if ($this->name->equals($name)) {
            return;
        }

        $this->name = $name;
        $this->blame(null, true);
    }

    /**
     * @return CollectionInterface<Pet>
     */
    public function getPets(): CollectionInterface
    {
        return $this->pets;
    }

    public function getBlame(): BlameImmutableInterface
    {
        return new BlameImmutable($this->blame);
    }

    public function blame(?object $by, bool $force = false): void
    {
        if (!($force || $this->blame->isNew() || $this->blame->isTouched())) {
            return;
        }

        $this->blame->touch($by);
    }
}
