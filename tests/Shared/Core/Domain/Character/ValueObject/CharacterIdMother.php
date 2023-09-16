<?php

declare(strict_types=1);

namespace Whalar\Tests\Shared\Core\Domain\Character\ValueObject;

use Assert\AssertionFailedException;
use Whalar\Core\Domain\Character\ValueObject\CharacterId;
use Whalar\Tests\Shared\Shared\Domain\ValueObject\MotherCreator;

final class CharacterIdMother
{
    public static function create(): string
    {
        return sprintf('ch%s', MotherCreator::random()->numberBetween(1000000, 9999999));
    }

    /** @throws AssertionFailedException */
    public static function random(?string $value = null): CharacterId
    {
        return CharacterId::from($value ?? self::create());
    }
}
