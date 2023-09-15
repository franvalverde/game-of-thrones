<?php

declare(strict_types=1);

namespace Whalar\Core\Infrastructure\Persistence\Doctrine\Type;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType as BaseStringType;
use Whalar\Core\Domain\Actor\ValueObject\SeasonsActive;

final class SeasonsActiveType extends BaseStringType
{
    private const FIELD = 'seasonsActive';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value instanceof SeasonsActive) {
            return $value->value();
        }

        return $value;
    }

    /** @throws AssertionFailedException */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?SeasonsActive
    {
        if (!\is_scalar($value)) {
            return null;
        }

        Assertion::string($value);

        return SeasonsActive::from($value);
    }

    public function getName(): string
    {
        return self::FIELD;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
