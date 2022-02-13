<?php

declare(strict_types=1);

namespace App\Domain\Pet;

use spaceonfire\ValueObject\AbstractEnumValue;

/**
 * @method static self dog()
 * @method static self cat()
 * @method static self hamster()
 * @extends AbstractEnumValue<'dog'|'cat'|'hamster'>
 */
final class PetType extends AbstractEnumValue
{
    public const DOG = 'dog';

    public const CAT = 'cat';

    public const HAMSTER = 'hamster';
}
