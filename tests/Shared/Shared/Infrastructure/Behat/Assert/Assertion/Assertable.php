<?php

declare(strict_types=1);

namespace Whalar\Tests\Shared\Shared\Infrastructure\Behat\Assert\Assertion;

use Assert\AssertionFailedException;

interface Assertable
{
    /**
     * @param mixed $actual
     * @throws AssertionFailedException
     */
    public function __invoke($actual): void;
}
