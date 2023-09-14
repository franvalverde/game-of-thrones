<?php

declare(strict_types=1);

namespace Whalar\Shared\Domain\Messaging\Serialization;

use JetBrains\PhpStorm\Pure;
use Whalar\Shared\Domain\DomainException;

final class SerializationException extends DomainException
{
    #[Pure]
    public static function from(string $message, ?\Throwable $previous = null): self
    {
        return new self($message, 0, $previous);
    }

    public function title(): string
    {
        return 'HTTP_BAD_REQUEST';
    }
}
