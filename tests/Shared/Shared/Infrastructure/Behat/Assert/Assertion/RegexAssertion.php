<?php

declare(strict_types=1);

namespace Whalar\Tests\Shared\Shared\Infrastructure\Behat\Assert\Assertion;

use Assert\Assertion;

final class RegexAssertion implements Assertable
{
    public function __construct(private readonly string $pattern)
    {
    }

    // @phpstan-ignore-next-line
    public function __invoke($actual): void
    {
        Assertion::regex($actual, $this->pattern);
    }
}
