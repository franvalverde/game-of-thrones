<?php

declare(strict_types=1);

namespace Whalar\Shared\Infrastructure\Persistence\Doctrine\Type;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType as BaseStringType;
use Whalar\Shared\Domain\ValueObject\Link;

final class LinkType extends BaseStringType
{
    private const FIELD = 'link';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value instanceof Link) {
            return $value->value();
        }

        return $value;
    }

    /** @throws AssertionFailedException */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?Link
    {
        if (!\is_scalar($value)) {
            return null;
        }

        Assertion::string($value);

        return Link::from($value);
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
