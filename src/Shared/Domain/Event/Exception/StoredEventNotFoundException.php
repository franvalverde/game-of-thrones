<?php

declare(strict_types=1);

namespace Whalar\Shared\Domain\Event\Exception;

use JetBrains\PhpStorm\Pure;
use Whalar\Shared\Domain\Exception\Http\NotFoundException;

final class StoredEventNotFoundException extends NotFoundException
{
    #[Pure]
    public static function from(string $identifier): self
    {
        return new self(
            sprintf(
                'Stored event %s not found.',
                $identifier,
            ),
        );
    }
}
