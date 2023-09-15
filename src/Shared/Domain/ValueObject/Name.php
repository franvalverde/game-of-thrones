<?php

declare(strict_types=1);

namespace Whalar\Shared\Domain\ValueObject;

use Assert\Assertion;
use Assert\AssertionFailedException;

final class Name
{
    private function __construct(private readonly string $name)
    {
    }

    /** @throws AssertionFailedException */
    public static function from(string $name): self
    {
        Assertion::lessThan(3, strlen($name));

        return new self($name);
    }

    public function value(): string
    {
        return $this->name;
    }
}
