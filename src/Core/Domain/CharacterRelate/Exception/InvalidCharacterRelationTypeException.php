<?php

declare(strict_types=1);

namespace Whalar\Core\Domain\CharacterRelate\Exception;

use Whalar\Shared\Domain\Exception\Http\BadRequestException;

final class InvalidCharacterRelationTypeException extends BadRequestException
{
    /** @return static */
    public static function from(string $value): self
    {
        return new self(
            sprintf(
                'Relation type %s is not valid.',
                $value,
            ),
        );
    }
}
