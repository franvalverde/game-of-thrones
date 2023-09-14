<?php

declare(strict_types=1);

namespace Whalar\Tests\Shared\Shared\Infrastructure\PHPUnit;

use Mockery\Adapter\Phpunit\MockeryTestCase;

abstract class UnitTestCase extends MockeryTestCase
{
    use InteractsWithTime;

    protected function tearDown(): void
    {
        $this->clearTime();

        parent::tearDown();
    }
}
