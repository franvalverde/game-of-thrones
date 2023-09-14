<?php

declare(strict_types=1);

namespace Whalar\Tests\Shared\Shared\Domain\ValueObject;

use Assert\AssertionFailedException;
use Whalar\Shared\Domain\ValueObject\Name;

final class NameMother
{
    public static function create(): string
    {
        return MotherCreator::random()->text();
    }

    /** @throws AssertionFailedException */
    public static function random(?string $value = null): Name
    {
        return Name::from($value ?? self::create());
    }
}
