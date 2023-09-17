<?php

declare(strict_types=1);

namespace Whalar\Shared\Domain\ValueObject;

use Assert\Assertion;
use Assert\AssertionFailedException;

final class PaginatorSize
{
    private int $size;

    /** @throws AssertionFailedException */
    private function __construct(int $size)
    {
        Assertion::greaterThan($size, 0, 'Size must be greater than zero');
        $this->size = $size;
    }

    /** @throws AssertionFailedException */
    public static function from(int $size): self
    {
        return new self($size);
    }

    public function value(): int
    {
        return $this->size;
    }
}
