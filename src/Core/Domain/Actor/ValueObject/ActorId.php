<?php

declare(strict_types=1);

namespace Whalar\Core\Domain\Actor\ValueObject;

use Assert\Assertion;
use Assert\AssertionFailedException;

final class ActorId implements \JsonSerializable, \Stringable
{
    private string $value;

    /** @throws AssertionFailedException */
    private function __construct(string $value)
    {
        $this->setValue($value);
    }

    /** @throws AssertionFailedException */
    public static function from(string $value): self
    {
        return new self($value);
    }

    public function id(): string
    {
        return $this->value;
    }

    public function equalsTo(self $other): bool
    {
        return $this->id() === $other->id();
    }

    public function jsonSerialize(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    /** @throws AssertionFailedException */
    private function setValue(string $value): void
    {
        Assertion::true(
            1 === preg_match("/^nm\d{7}$/i", $value),
            'The actor Id must start with nm followed by 7 numbers',
        );

        $this->value = $value;
    }
}
