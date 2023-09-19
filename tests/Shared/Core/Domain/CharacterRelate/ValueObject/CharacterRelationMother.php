<?php

declare(strict_types=1);

namespace Whalar\Tests\Shared\Core\Domain\CharacterRelate\ValueObject;

use Whalar\Core\Domain\CharacterRelate\Exception\InvalidCharacterRelationTypeException;
use Whalar\Core\Domain\CharacterRelate\ValueObject\CharacterRelation;

final class CharacterRelationMother
{
    public static function create(): string
    {
        $values = array_column(CharacterRelation::cases(), 'name');

        return lcfirst($values[array_rand($values)]);
    }

    /** @throws InvalidCharacterRelationTypeException */
    public static function random(?string $value = null): CharacterRelation
    {
        return CharacterRelation::from($value ?? self::create());
    }
}
