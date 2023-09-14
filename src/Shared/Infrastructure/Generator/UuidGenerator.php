<?php

declare(strict_types=1);

namespace Whalar\Shared\Infrastructure\Generator;

interface UuidGenerator
{
    public function generate(): string;
}
