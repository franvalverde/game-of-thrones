<?php

declare(strict_types=1);

namespace Whalar\Shared\Domain\Exception;

use Whalar\Shared\Domain\Exception\Http\BadRequestException;

final class InvalidContentTypeException extends BadRequestException
{
    public static function from(string $type): self
    {
        return new self(sprintf('The content type of request should by %s', $type));
    }
}
