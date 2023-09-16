<?php

declare(strict_types=1);

namespace Whalar\Core\Domain\Character\Exception;

use Whalar\Shared\Domain\Exception\Http\ConflictException;

final class CharacterAlreadyExistsException extends ConflictException
{
    /** @return static */
    public static function from(string $identifier): self
    {
        return new self(
            sprintf(
                'Character %s already exists.',
                $identifier,
            ),
        );
    }
}
