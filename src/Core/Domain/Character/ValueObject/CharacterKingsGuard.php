<?php

declare(strict_types=1);

namespace Whalar\Core\Domain\Character\ValueObject;

final class CharacterKingsGuard implements \JsonSerializable, \Stringable
{
    private bool $value;

    private function __construct(bool $value)
    {
        $this->value = $value;
    }

    public static function from(bool $value): self
    {
        return new self($value);
    }

    public function value(): bool
    {
        return $this->value;
    }

    public function jsonSerialize(): string
    {
        return $this->__toString();
    }

    public function __toString(): string
    {
        return $this->value
            ? 'true'
            : 'false';
    }
}
