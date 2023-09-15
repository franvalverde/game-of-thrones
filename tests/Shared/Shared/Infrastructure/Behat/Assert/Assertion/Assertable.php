<?php

declare(strict_types=1);

namespace Whalar\Tests\Shared\Shared\Infrastructure\Behat\Assert\Assertion;

interface Assertable
{
    // @phpstan-ignore-next-line
    public function __invoke($actual): void;
}
