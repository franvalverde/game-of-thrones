<?php

declare(strict_types=1);

namespace Whalar\Tests\Shared\Core\Domain\Character\ValueObject;

use Whalar\Core\Domain\Character\ValueObject\CharacterKingsGuard;
use Whalar\Tests\Shared\Shared\Domain\ValueObject\MotherCreator;

final class CharacterKingsGuardMother
{
    public static function create(): bool
    {
        return MotherCreator::random()->boolean;
    }

    public static function random(?bool $value = null): CharacterKingsGuard
    {
        return CharacterKingsGuard::from($value ?? self::create());
    }
}
