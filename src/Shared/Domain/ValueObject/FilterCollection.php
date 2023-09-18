<?php

declare(strict_types=1);

namespace Whalar\Shared\Domain\ValueObject;

final class FilterCollection
{
    public function __construct(
        public readonly string $operator,
        public readonly string $key,
        public readonly string $value,
    ) {
    }
}
