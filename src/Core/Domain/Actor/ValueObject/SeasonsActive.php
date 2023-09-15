<?php

declare(strict_types=1);

namespace Whalar\Core\Domain\Actor\ValueObject;

final class SeasonsActive
{
    private function __construct(private readonly string $seasonsActive)
    {
    }

    public static function from(string $seasonsActive): self
    {
        return new self($seasonsActive);
    }

    public static function fromArray(array $seasonsActive): self
    {
        return new self(json_encode($seasonsActive));
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
