<?php

declare(strict_types=1);

namespace Whalar\Shared\Infrastructure\Persistence\Doctrine\Type;

use Assert\AssertionFailedException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;
use Whalar\Shared\Domain\ValueObject\AggregateId;

use function is_scalar;

final class AggregateIdType extends GuidType
{
    private const FIELD_ID = 'aggregate_id';

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof AggregateId) {
            return $value->id();
        }

        return $value;
    }

    /** @throws AssertionFailedException */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (!is_scalar($value)) {
            return null;
        }

        return AggregateId::from((string) $value);
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
