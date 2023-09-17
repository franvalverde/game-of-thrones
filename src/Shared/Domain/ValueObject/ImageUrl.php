<?php

declare(strict_types=1);

namespace Whalar\Shared\Domain\ValueObject;

use Assert\Assertion;

final class ImageUrl implements \JsonSerializable
{
    private function __construct(private readonly string $imageUrl)
    {
    }

    public static function from(string $imageUrl): self
    {
        Assertion::url($imageUrl);

        return new self($imageUrl);
    }

    public function value(): string
    {
        return $this->imageUrl;
    }

    public function jsonSerialize(): string
    {
        return $this->value();
    }
}
