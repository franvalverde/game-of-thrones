<?php

declare(strict_types=1);

namespace Whalar\Tests\Shared\Core\Domain\Actor\ValueObject;

use Assert\AssertionFailedException;
use Whalar\Core\Domain\Actor\ValueObject\ActorId;
use Whalar\Tests\Shared\Shared\Domain\ValueObject\MotherCreator;

final class ActorIdMother
{
    public static function create(): string
    {
        return sprintf('nm%s', MotherCreator::random()->numberBetween(1000000, 9999999));
    }

    /** @throws AssertionFailedException */
    public static function random(?string $value = null): ActorId
    {
        return ActorId::from($value ?? self::create());
    }
}
