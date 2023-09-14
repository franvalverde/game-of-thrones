<?php

declare(strict_types=1);

namespace Whalar\Shared\Domain\Event\ValueObject;

use Assert\Assertion;
use Assert\AssertionFailedException;
use JetBrains\PhpStorm\Pure;
use JsonSerializable;

final class MessageId implements JsonSerializable
{
    private string $id;

    /** @throws AssertionFailedException */
    private function __construct(string $id)
    {
        Assertion::uuid($id);
        $this->id = $id;
    }

    /** @throws AssertionFailedException */
    public static function from(string $messageId): self
    {
        return new self($messageId);
    }

    #[Pure]
    public function __toString(): string
    {
        return $this->id();
    }

    public function id(): string
    {
        return $this->id;
    }

    #[Pure]
    public function jsonSerialize(): string
    {
        return $this->id();
    }
}
