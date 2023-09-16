<?php

declare(strict_types=1);

namespace Whalar\Core\Domain\Character\Exception;

use Whalar\Shared\Domain\Exception\Http\BadRequestException;

final class CharacterMustHaveActorException extends BadRequestException
{
    /** @return static */
    public static function from(): self
    {
        return new self('The character must have at least one actor');
    }
}
