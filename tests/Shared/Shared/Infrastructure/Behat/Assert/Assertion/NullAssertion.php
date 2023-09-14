<?php

declare(strict_types=1);

namespace Whalar\Tests\Shared\Shared\Infrastructure\Behat\Assert\Assertion;

use Assert\Assertion;

final class NullAssertion implements Assertable
{
    public function __invoke($actual): void
    {
        Assertion::null($actual);
    }
}
