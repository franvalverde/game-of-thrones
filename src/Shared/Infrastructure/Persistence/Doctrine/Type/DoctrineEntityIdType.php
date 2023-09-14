<?php

declare(strict_types=1);

namespace Whalar\Shared\Infrastructure\Persistence\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

abstract class DoctrineEntityIdType extends GuidType
{
    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return null === $value || \is_string($value)
            ? $value
            : (string) $value->id();
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $className = $this->getClassName();

        return $className::from($value);
    }

    abstract public function getClassName(): string;
}
