<?php

declare(strict_types=1);

namespace Whalar\Tests\Shared\Shared\Infrastructure\PHPUnit;

use Carbon\Carbon;
use Carbon\CarbonImmutable;

trait InteractsWithTime
{
    /** Travel to another time. */
    public function travelTo(\DateTimeInterface $date): void
    {
        Carbon::setTestNow($date);
        CarbonImmutable::setTestNow($date);
    }

    public function clearTime(): void
    {
        Carbon::setTestNow();
        CarbonImmutable::setTestNow();
    }
}
