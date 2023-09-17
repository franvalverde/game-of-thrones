<?php

declare(strict_types=1);

namespace Whalar\Core\Domain\Character\ValueObject;

use Assert\Assertion;
use Assert\AssertionFailedException;

final class CharacterId implements \JsonSerializable, \Stringable
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

    public function value(): string
    {
        return $this->value;
    }

    public function equalsTo(self $other): bool
    {
        return $this->value() === $other->value();
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
            1 === preg_match("/^ch\d{7}$/i", $value),
            'The character Id must start with ch followed by 7 numbers',
        );

        $this->value = $value;
    }
}
