<?php

declare(strict_types=1);

namespace App\Domain\Pet\Specification;

use Warp\Clock\DateTimeImmutableValue;
use Warp\Criteria\Expression\AbstractExpressionDecorator;
use Warp\Criteria\Expression\ExpressionFactory;
use Webmozart\Expression\Expression;

/**
 * Decorated expressions allows us to create meaningful filters and take advantage of typehints to design domain layer.
 */
final class FindByAge extends AbstractExpressionDecorator
{
    private function __construct(Expression $expr)
    {
        parent::__construct($expr);
    }

    public static function greaterThan(int $years): self
    {
        $expr = ExpressionFactory::new();

        $date = DateTimeImmutableValue::from(\sprintf('now - %d years', $years))->setTime(0, 0);

        return new self($expr->property('birthdate', $expr->lessThanEqual($date)));
    }
}
