<?php

declare(strict_types=1);

namespace App\Domain\User\Specification;

use spaceonfire\Criteria\Expression\AbstractExpressionDecorator;
use spaceonfire\Criteria\Expression\ExpressionFactory;
use spaceonfire\ValueObject\EmailValue;

final class FindByEmail extends AbstractExpressionDecorator
{
    public function __construct(EmailValue $email)
    {
        $expr = ExpressionFactory::new();

        // Expressions implement PHP native filtration and also can be used to generate SQL where clause.
        // Basically you want to use such expressions that can be used in both cases: PHP and SQL.
        parent::__construct($expr->property('email', $expr->same($email)));
    }
}
