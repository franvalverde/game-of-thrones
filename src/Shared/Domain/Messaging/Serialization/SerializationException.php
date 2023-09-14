<?php

declare(strict_types=1);

namespace Whalar\Shared\Domain\Messaging\Serialization;

use Whalar\Shared\Domain\DomainException;
use JetBrains\PhpStorm\Pure;
use Throwable;

final class SerializationException extends DomainException
{
    #[Pure]
    public static function from(string $message, ?Throwable $previous = null): self
    {
        return new self($message, 0, $previous);
    }

    public function title(): string
    {
        return 'HTTP_BAD_REQUEST';
    }
}
