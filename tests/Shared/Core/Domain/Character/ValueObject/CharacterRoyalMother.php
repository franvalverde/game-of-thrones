<?php

declare(strict_types=1);

namespace Whalar\Tests\Shared\Core\Domain\Character\ValueObject;

use Whalar\Core\Domain\Character\ValueObject\CharacterRoyal;
use Whalar\Tests\Shared\Shared\Domain\ValueObject\MotherCreator;

final class CharacterRoyalMother
{
    public static function create(): bool
    {
        return MotherCreator::random()->boolean;
    }

    public static function random(?bool $value = null): CharacterRoyal
    {
        return CharacterRoyal::from($value ?? self::create());
    }
}
