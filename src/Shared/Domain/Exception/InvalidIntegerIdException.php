<?php

declare(strict_types=1);

namespace Whalar\Shared\Domain\Exception;

use Whalar\Shared\Domain\Exception\Http\BadRequestException;

final class InvalidIntegerIdException extends BadRequestException
{
    public static function from(): self
    {
        return new self('The id is invalid');
    }
}
