<?php

declare(strict_types=1);

namespace Whalar\Tests\Shared\Shared\Domain\ValueObject;

use Assert\AssertionFailedException;
use Whalar\Shared\Domain\ValueObject\ImageUrl;

final class ImageUrlMother
{
    public static function create(): string
    {
        return MotherCreator::random()->url();
    }

    /** @throws AssertionFailedException */
    public static function random(?string $value = null): ImageUrl
    {
        return ImageUrl::from($value ?? self::create());
    }
}
