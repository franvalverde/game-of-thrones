<?php

declare(strict_types=1);

namespace Whalar\Core\Domain\Actor\ValueObject;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Symfony\Component\Uid\Uuid;

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

    /** @throws AssertionFailedException */
    public static function random(): self
    {
        return new self(Uuid::v4()->toRfc4122());
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
            preg_match("/^nm\d{7}$/i", $value),
            'The actor Id must start with nm followed by 7 numbers',
        );

        $this->value = $value;
    }
}
