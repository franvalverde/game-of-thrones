<?php

declare(strict_types=1);

namespace Whalar\Core\Domain\CharacterRelate\Exception;

use Whalar\Shared\Domain\Exception\Http\BadRequestException;

final class CharacterRelateInvalidExistsException extends BadRequestException
{
    /** @return static */
    public static function from(): self
    {
        return new self('Cannot relate a character to the same one');
    }
}
