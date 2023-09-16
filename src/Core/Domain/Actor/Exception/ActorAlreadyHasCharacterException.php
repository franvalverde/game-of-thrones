<?php

declare(strict_types=1);

namespace Whalar\Core\Domain\Actor\Exception;

use Whalar\Shared\Domain\Exception\Http\ConflictException;

final class ActorAlreadyHasCharacterException extends ConflictException
{
    /** @return static */
    public static function from(): self
    {
        return new self('The actor already has a character assigned');
    }
}
