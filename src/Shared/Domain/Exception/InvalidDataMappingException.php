<?php

declare(strict_types=1);

namespace Whalar\Shared\Domain\Exception;

use Whalar\Shared\Domain\Exception\Http\BadRequestException;
use JetBrains\PhpStorm\Pure;

final class InvalidDataMappingException extends BadRequestException
{
    #[Pure]
    public static function fromMissingKey(string $key, ?string $arrayName = null): self
    {
        if (null === $arrayName) {
            return new self(sprintf('Expected array to have a key "%s".', $key));
        }

        return new self(sprintf('Expected array "%s" to have a key "%s".', $arrayName, $key));
    }

    #[Pure]
    public static function fromNonScalar(string $key, ?string $arrayName = null): self
    {
        if (null === $arrayName) {
            return new self(sprintf('Expected array key "%s" to have a scalar value.', $key));
        }

        return new self(sprintf('Expected array key "%s[\'%s\']" to have a scalar value.', $arrayName, $key));
    }
}
