<?php

declare(strict_types=1);

namespace App\Domain\Pet;

use App\Domain\EntityReferenceHelper;
use App\Domain\User\User;
use Warp\Clock\DateTimeImmutableValue;
use Warp\DataSource\Blame\BlamableInterface;
use Warp\DataSource\Blame\Blame;
use Warp\DataSource\Blame\BlameImmutable;
use Warp\DataSource\Blame\BlameImmutableInterface;
use Warp\DataSource\Blame\BlameInterface;
use Warp\DataSource\EntityEventsInterface;
use Warp\DataSource\EntityEventsTrait;
use Warp\DataSource\EntityReferenceInterface;

/**
 * @implements BlamableInterface<User>
 */
final class Pet implements BlamableInterface, EntityEventsInterface
{
    use EntityEventsTrait;

    /**
     * @var BlameInterface<User>
     */
    private BlameInterface $blame;

    /**
     * @param EntityReferenceInterface<User> $owner
     */
    private function __construct(
        private PetId $id,
        private PetType $type,
        private string $name,
        private DateTimeImmutableValue $birthdate,
        private EntityReferenceInterface $owner,
    ) {
        // @phpstan-ignore-next-line
        $this->blame = Blame::new(User::class);

        $this->recordEvent(new Events\PetCreatedEvent($this->id));
    }

    public static function new(
        PetType $type,
        string $name,
        User $owner,
        DateTimeImmutableValue $birthdate,
        ?PetId $id = null,
    ): self {
        return new self(
            $id ?? PetId::random(),
            $type,
            $name,
            $birthdate,
            EntityReferenceHelper::fromEntity($owner, $owner->getId())->get(),
        );
    }

    public function getId(): PetId
    {
        return $this->id;
    }

    public function getType(): PetType
    {
        return $this->type;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getBirthdate(): DateTimeImmutableValue
    {
        return $this->birthdate;
    }

    public function getOwner(): User
    {
        return $this->owner->getEntity();
    }

    public function changeName(string $name): void
    {
        if ($name === $this->name) {
            return;
        }

        $this->name = $name;
        $this->blame(null, true);
    }

    public function changeBirthdate(DateTimeImmutableValue $birthdate): void
    {
        if ($birthdate === $this->birthdate) {
            return;
        }

        $this->birthdate = $birthdate;
        $this->blame(null, true);
    }

    public function changeOwner(User $owner): void
    {
        $ref = EntityReferenceHelper::fromEntity($owner, $owner->getId())->get();

        if (EntityReferenceHelper::equals($this->owner, $ref)) {
            return;
        }

        $this->owner = $ref;
        $this->blame(null, true);
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
