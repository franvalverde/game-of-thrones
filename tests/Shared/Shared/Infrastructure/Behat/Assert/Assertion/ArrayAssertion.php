<?php

declare(strict_types=1);

namespace Whalar\Tests\Shared\Shared\Infrastructure\Behat\Assert\Assertion;

use Assert\Assertion;

final class ArrayAssertion implements Assertable
{
    public function __construct(private readonly int $count)
    {
    }

    // @phpstan-ignore-next-line
    public function __invoke($actual): void
    {
        Assertion::isArray($actual);
        Assertion::count($actual, $this->count);
    }
}
