<?php

declare(strict_types=1);

namespace Whalar\Core\Domain\Character\Exception;

use Whalar\Shared\Domain\Exception\Http\NotFoundException;

final class CharacterNotFoundException extends NotFoundException
{
    /** @return static */
    public static function from(string $identifier): self
    {
        return new self(
            sprintf(
                'Character %s not found.',
                $identifier,
            ),
        );
    }
}
