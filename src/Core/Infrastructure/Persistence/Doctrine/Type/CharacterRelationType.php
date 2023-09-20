<?php

declare(strict_types=1);

namespace Whalar\Core\Infrastructure\Persistence\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Whalar\Core\Domain\CharacterRelate\ValueObject\CharacterRelation;

final class CharacterRelationType extends StringType
{
    private const FIELD_ID = 'character_relation';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        if ($value instanceof CharacterRelation) {
            return $value->value();
        }

        return $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?CharacterRelation
    {
        if (!\is_scalar($value)) {
            return null;
        }

        return CharacterRelation::from((string) $value);
    }

    public function getName(): string
    {
        return self::FIELD_ID;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
