<?php

declare(strict_types=1);

namespace Whalar\Shared\Infrastructure\Persistence\Doctrine\Type;

use Carbon\CarbonImmutable;
use Carbon\Doctrine\CarbonDoctrineType;
use Carbon\Doctrine\CarbonTypeConverter;
use DateTimeInterface;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateTimeTzImmutableType;

final class CarbonTzImmutableType extends DateTimeTzImmutableType implements CarbonDoctrineType
{
    /* @use CarbonTypeConverter<CarbonImmutable> */
    // @phpstan-ignore-next-line
    use CarbonTypeConverter;

    public function getName(): string
    {
        return 'carbontz_immutable';
    }

    /** @throws ConversionException */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        if ($value instanceof DateTimeInterface) {
            return $value->format('Y-m-d H:i:s.uO');
        }

        throw ConversionException::conversionFailedInvalidType(
            $value,
            $this->getName(),
            ['null', 'DateTime', 'Carbon'],
        );
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }

    /** @return class-string<CarbonImmutable> */
    protected function getCarbonClassName(): string
    {
        return CarbonImmutable::class;
    }
}
