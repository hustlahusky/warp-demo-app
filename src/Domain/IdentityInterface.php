<?php

declare(strict_types=1);

namespace App\Domain;

use Cycle\ORM\Promise\ReferenceInterface;

interface IdentityInterface extends ReferenceInterface, \Stringable
{
    /**
     * @return mixed
     */
    public function value();
}
