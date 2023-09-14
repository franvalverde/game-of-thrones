<?php

declare(strict_types=1);

namespace Whalar\Tests\Shared\Shared\Infrastructure\Behat\Assert;

use Illuminate\Support\Arr;
use Whalar\Tests\Shared\Shared\Infrastructure\Behat\Assert\Assertion\ArrayAssertion;
use Whalar\Tests\Shared\Shared\Infrastructure\Behat\Assert\Assertion\Assertable;
use Whalar\Tests\Shared\Shared\Infrastructure\Behat\Assert\Assertion\DateTimeAssertion;
use Whalar\Tests\Shared\Shared\Infrastructure\Behat\Assert\Assertion\FalseAssertion;
use Whalar\Tests\Shared\Shared\Infrastructure\Behat\Assert\Assertion\NotNullAssertion;
use Whalar\Tests\Shared\Shared\Infrastructure\Behat\Assert\Assertion\NumericAssertion;
use Whalar\Tests\Shared\Shared\Infrastructure\Behat\Assert\Assertion\RegexAssertion;
use Whalar\Tests\Shared\Shared\Infrastructure\Behat\Assert\Assertion\TrueAssertion;
use Whalar\Tests\Shared\Shared\Infrastructure\Behat\Assert\Assertion\UuidAssertion;

final class Assertion
{
    public static function get(string $assertionId, array $args = []): Assertable
    {
        $assertion = Arr::get(self::assertions(), $assertionId);

        if (null === $assertion) {
            throw new \RuntimeException("Assertion $assertionId not found.");
        }

        return \call_user_func($assertion, $args);
    }

    /** @return array<string, callable> */
    private static function assertions(): array
    {
        return [
            'array' => static fn ($args) => new ArrayAssertion((int) $args[0]),
            'dateTime' => static fn ($args) => new DateTimeAssertion(...$args),
            'dateTimeIso8601Zulu' => static fn () => new DateTimeAssertion(DateTimeAssertion::ISO8601_ZULU),
            'dateTimeIso8601ZuluMicrosecond' => static fn () => new DateTimeAssertion(
                DateTimeAssertion::ISO8601_ZULU_MICROSECOND,
            ),
            'false' => static fn () => new FalseAssertion(),
            'notNull' => static fn () => new NotNullAssertion(),
            'numeric' => static fn () => new NumericAssertion(),
            'regexp' => static fn ($args) => new RegexAssertion(...$args),
            'true' => static fn () => new TrueAssertion(),
            'uuid' => static fn () => new UuidAssertion(),
        ];
    }
}
