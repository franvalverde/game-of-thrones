<?php

declare(strict_types=1);

namespace Whalar\Core\Infrastructure\Persistence\Doctrine\Type;

use Assert\AssertionFailedException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Whalar\Core\Domain\Character\ValueObject\CharacterId;

final class CharacterIdType extends StringType
{
    private const FIELD_ID = 'character_id';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        if ($value instanceof CharacterId) {
            return $value->value();
        }

        return $value;
    }

    /** @throws AssertionFailedException */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?CharacterId
    {
        if (!\is_scalar($value)) {
            return null;
        }

        return CharacterId::from((string) $value);
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
