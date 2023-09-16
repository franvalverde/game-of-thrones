<?php

declare(strict_types=1);

namespace Whalar\Core\Infrastructure\Persistence\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\BooleanType;
use Whalar\Core\Domain\Character\ValueObject\CharacterKingsGuard;

final class KingsGuardType extends BooleanType
{
    private const FIELD_ID = 'kings_guard';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): bool
    {
        if ($value instanceof CharacterKingsGuard) {
            return $value->value();
        }

        return $value;
    }

    // @phpstan-ignore-next-line
    public function convertToPHPValue($value, AbstractPlatform $platform): CharacterKingsGuard
    {
        return CharacterKingsGuard::from(!\is_bool($value) ? false : $value);
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
