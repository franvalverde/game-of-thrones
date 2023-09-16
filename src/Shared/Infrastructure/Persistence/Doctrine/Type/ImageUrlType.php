<?php

declare(strict_types=1);

namespace Whalar\Shared\Infrastructure\Persistence\Doctrine\Type;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType as BaseStringType;
use Whalar\Shared\Domain\ValueObject\ImageUrl;

final class ImageUrlType extends BaseStringType
{
    private const FIELD = 'image_url';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value instanceof ImageUrl) {
            return $value->value();
        }

        return $value;
    }

    /** @throws AssertionFailedException */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?ImageUrl
    {
        if (!\is_scalar($value)) {
            return null;
        }

        Assertion::string($value);

        return ImageUrl::from($value);
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
