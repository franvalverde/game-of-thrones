<?php

declare(strict_types=1);

namespace Whalar\Core\Infrastructure\Persistence\Doctrine\Type;

use Assert\AssertionFailedException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\BooleanType;
use Whalar\Core\Domain\Character\ValueObject\CharacterRoyal;

final class RoyalType extends BooleanType
{
    private const FIELD_ID = 'royal';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): bool
    {
        if ($value instanceof CharacterRoyal) {
            return $value->value();
        }

        return $value;
    }

    /** @throws AssertionFailedException */
    public function convertToPHPValue($value, AbstractPlatform $platform): CharacterRoyal
    {
        if (!\is_scalar($value)) {
            $value = false;
        }

        return CharacterRoyal::from($value);
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
