<?php

declare(strict_types=1);

namespace Whalar\Shared\Infrastructure\Persistence\Doctrine\Type;

use Assert\AssertionFailedException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Whalar\Shared\Domain\ValueObject\Name;
use Doctrine\DBAL\Types\StringType as BaseStringType;
use function is_scalar;

final class NameType extends BaseStringType
{
    private const FIELD = 'name';

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof Name) {
            return $value->name();
        }

        return $value;
    }

    /** @throws AssertionFailedException */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (!is_scalar($value)) {
            return null;
        }

        return Name::from($value);
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
