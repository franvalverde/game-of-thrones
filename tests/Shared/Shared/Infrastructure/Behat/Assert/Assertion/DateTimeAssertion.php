<?php

declare(strict_types=1);

namespace Whalar\Tests\Shared\Shared\Infrastructure\Behat\Assert\Assertion;

use Assert\Assertion;
use Carbon\Carbon;

final class DateTimeAssertion implements Assertable
{
    public const ISO8601_ZULU = 'Y-m-d\TH:i:s\Z';
    public const ISO8601_ZULU_MICROSECOND = 'Y-m-d\TH:i:s.u\Z';

    public function __construct(private readonly string $format)
    {
    }

    // @phpstan-ignore-next-line
    public function __invoke($actual): void
    {
        Assertion::notNull($actual);

        $dateTime = Carbon::createFromFormat($this->format, $actual);

        Assertion::isInstanceOf($dateTime, \DateTimeInterface::class);
    }
}
