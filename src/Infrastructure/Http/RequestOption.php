<?php

declare(strict_types=1);

namespace App\Infrastructure\Http;

use PhpOption\None;
use PhpOption\Option;
use PhpOption\Some;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @template T
 * @extends Option<T>
 */
final class RequestOption extends Option
{
    private const ATTRIBUTE = 'attribute';

    private const QUERY = 'query';

    /**
     * @phpstan-param Option<T> $op
     * @phpstan-param self::* $type
     */
    private function __construct(
        private Option $op,
        private string $type,
        private string $argument,
    ) {
    }

    /**
     * @return self<mixed>
     */
    public static function attribute(ServerRequestInterface $request, string $attribute): self
    {
        return new self(
            Option::ensure(static fn () => $request->getAttribute($attribute)),
            self::ATTRIBUTE,
            $attribute,
        );
    }

    /**
     * @return self<mixed>
     */
    public static function query(ServerRequestInterface $request, string $query): self
    {
        return new self(
            Option::ensure(static function () use ($request, $query) {
                $qp = $request->getQueryParams();

                if (isset($qp[$query]) || \array_key_exists($query, $qp)) {
                    return new Some($qp[$query]);
                }

                return None::create();
            }),
            self::QUERY,
            $query,
        );
    }

    #[\ReturnTypeWillChange]
    public function getIterator()
    {
        return $this->op->getIterator();
    }

    public function get()
    {
        $this->require();

        return $this->op->get();
    }

    public function getOrElse($default)
    {
        return $this->op->getOrElse($default);
    }

    public function getOrCall($callable)
    {
        return $this->op->getOrCall($callable);
    }

    public function getOrThrow(\Exception $ex)
    {
        return $this->op->getOrThrow($ex);
    }

    public function isEmpty()
    {
        return $this->op->isEmpty();
    }

    public function isDefined()
    {
        return $this->op->isDefined();
    }

    public function orElse(Option $else)
    {
        return $this->op->orElse($else);
    }

    public function ifDefined($callable): void
    {
        $this->op->ifDefined($callable);
    }

    public function forAll($callable)
    {
        return $this->op->forAll($callable);
    }

    public function map($callable)
    {
        return $this->op->map($callable);
    }

    public function flatMap($callable)
    {
        return $this->op->flatMap($callable);
    }

    public function filter($callable)
    {
        return $this->op->filter($callable);
    }

    public function filterNot($callable)
    {
        return $this->op->filterNot($callable);
    }

    public function select($value)
    {
        return $this->op->select($value);
    }

    public function reject($value)
    {
        return $this->op->reject($value);
    }

    public function foldLeft($initialValue, $callable)
    {
        return $this->op->foldLeft($initialValue, $callable);
    }

    public function foldRight($initialValue, $callable)
    {
        return $this->op->foldRight($initialValue, $callable);
    }

    private function require(): void
    {
        if ($this->isEmpty()) {
            throw match ($this->type) {
                self::ATTRIBUTE => new \InvalidArgumentException(
                    <<<EOT
                    Request missing required attribute "{$this->argument}".
                    EOT,
                ),
                self::QUERY => new \InvalidArgumentException(
                    <<<EOT
                    Request missing required query parameter "{$this->argument}".
                    EOT,
                ),
            };
        }
    }
}
