<?php

declare(strict_types=1);

namespace Whalar\Core\Domain\Actor\Exception;

use Whalar\Shared\Domain\Exception\Http\NotFoundException;

final class ActorNotFoundException extends NotFoundException
{
    /** @return static */
    public static function from(string $identifier): self
    {
        return new self(
            sprintf(
                'Actor %s not found.',
                $identifier,
            ),
        );
    }
}
