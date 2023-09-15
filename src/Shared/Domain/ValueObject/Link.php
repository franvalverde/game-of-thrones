<?php

declare(strict_types=1);

namespace Whalar\Shared\Domain\ValueObject;

use Assert\Assertion;
use Assert\AssertionFailedException;

final class Link
{
    private function __construct(private readonly string $link)
    {
    }

    /** @throws AssertionFailedException */
    public static function from(string $link): self
    {
        Assertion::true(strchr($link, '/') !== null);
        return new self($link);
    }

    public function value(): string
    {
        return $this->link;
    }
}
