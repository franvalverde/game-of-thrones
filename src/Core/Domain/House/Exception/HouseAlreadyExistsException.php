<?php

declare(strict_types=1);

namespace Whalar\Core\Domain\House\Exception;

use Whalar\Shared\Domain\Exception\Http\ConflictException;

final class HouseAlreadyExistsException extends ConflictException
{
    /** @return static */
    public static function from(string $identifier): self
    {
        return new self(
            sprintf(
                'House %s already exists.',
                $identifier,
            ),
        );
    }
}
