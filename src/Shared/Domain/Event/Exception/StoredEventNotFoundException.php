<?php

declare(strict_types=1);

namespace Whalar\Shared\Domain\Event\Exception;

use Whalar\Shared\Domain\Exception\Http\NotFoundException;
use JetBrains\PhpStorm\Pure;

final class StoredEventNotFoundException extends NotFoundException
{
    /**
     * @param string $identifier
     * @return StoredEventNotFoundException
     */
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
