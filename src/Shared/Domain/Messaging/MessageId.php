<?php

declare(strict_types=1);

namespace Whalar\Shared\Domain\Messaging;

use Assert\AssertionFailedException;
use Whalar\Shared\Domain\ValueObject\Uuid;

final class MessageId extends Uuid
{
    /** @throws AssertionFailedException */
    public static function from(string $value): self
    {
        return new self($value);
    }
}
