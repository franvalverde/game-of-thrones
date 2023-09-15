<?php

declare(strict_types=1);

namespace Whalar\Core\Domain\Actor\ValueObject;

use Assert\Assertion;

final class SeasonsActive
{
    private function __construct(private readonly string $seasonsActive)
    {
    }

    public static function fromArray(array $seasonsActive): self
    {
        $value = json_encode($seasonsActive);
        Assertion::string($value);

        return new self($value);
    }

    public function value(): string
    {
        return $this->seasonsActive;
    }

    public function toArray(): array
    {
        return json_decode($this->seasonsActive);
    }
}
