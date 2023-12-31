<?php

declare(strict_types=1);

namespace Whalar\Core\Infrastructure\Persistence\Doctrine\Type;

use Assert\AssertionFailedException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Whalar\Core\Domain\Actor\ValueObject\ActorId;

final class ActorIdType extends StringType
{
    private const FIELD_ID = 'actor_id';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        if ($value instanceof ActorId) {
            return $value->value();
        }

        return $value;
    }

    /** @throws AssertionFailedException */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?ActorId
    {
        if (!\is_scalar($value)) {
            return null;
        }

        return ActorId::from((string) $value);
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
