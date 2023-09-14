<?php

declare(strict_types=1);

namespace Whalar\Tests\Shared\Shared\Infrastructure\Behat\Assert\Assertion;

use Assert\Assertion;

final class NumericAssertion implements Assertable
{
    public function __invoke($actual): void
    {
        Assertion::numeric($actual);
    }
}
