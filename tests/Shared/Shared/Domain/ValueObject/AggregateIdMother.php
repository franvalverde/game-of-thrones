<?php

declare(strict_types=1);

namespace Whalar\Tests\Shared\Shared\Domain\ValueObject;

use Assert\AssertionFailedException;
use Whalar\Shared\Domain\ValueObject\AggregateId;

final class AggregateIdMother
{
    public static function create(): string
    {
        return MotherCreator::random()->unique()->uuid();
    }

    /** @throws AssertionFailedException */
    public static function withUuid(string $id): AggregateId
    {
        return AggregateId::from($id);
    }

    /** @throws AssertionFailedException */
    public static function random(?string $value = null): AggregateId
    {
        return AggregateId::from($value ?? self::create());
    }

    /** @throws AssertionFailedException */
    public static function dummy(): AggregateId
    {
        return AggregateId::from('b25e3443-806d-4b25-891c-bbc4cea4acdd');
    }

    /** @throws AssertionFailedException */
    public static function withInvalidId(): AggregateId
    {
        return AggregateId::from('invalid');
    }
}
