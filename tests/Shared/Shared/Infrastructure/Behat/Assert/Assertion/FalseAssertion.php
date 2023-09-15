<?php

declare(strict_types=1);

namespace Whalar\Tests\Shared\Shared\Infrastructure\Behat\Assert\Assertion;

use Assert\Assertion;

final class FalseAssertion implements Assertable
{
    // @phpstan-ignore-next-line
    public function __invoke($actual): void
    {
        Assertion::false($actual);
    }
}
