<?php

declare(strict_types=1);

namespace Whalar\Core\Domain\Actor\Exception;

use Whalar\Shared\Domain\Exception\Http\ConflictException;

final class ActorAlreadyExistsException extends ConflictException
{
    /** @return static */
    public static function from(string $identifier): self
    {
        return new self(
            sprintf(
                'Actor %s already exists.',
                $identifier,
            ),
        );
    }
}
