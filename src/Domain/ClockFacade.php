<?php

declare(strict_types=1);

namespace App\Domain;

use spaceonfire\ValueObject\Date\ClockInterface;
use spaceonfire\ValueObject\Date\FrozenClock;
use spaceonfire\ValueObject\Date\SystemClock;
use Symfony\Contracts\Service\ResetInterface;

final class ClockFacade implements ResetInterface
{
    private FrozenClock $clock;

    public function __construct(?ClockInterface $clock = null)
    {
        $this->clock = self::freeze($clock);
    }

    public function get(): FrozenClock
    {
        return $this->clock;
    }

    public function reset(): void
    {
        $this->clock->reset();
    }

    private static function freeze(?ClockInterface $clock = null): FrozenClock
    {
        if ($clock instanceof FrozenClock) {
            return $clock;
        }

        return new FrozenClock($clock ?? SystemClock::fromUTC());
    }
}
