<?php

declare(strict_types=1);

namespace Whalar\Shared\Domain\ValueObject;

use Assert\Assertion;
use Assert\AssertionFailedException;

final class PaginatorPage
{
    private int $page;

    /** @throws AssertionFailedException */
    private function __construct(int $page)
    {
        Assertion::greaterThan($page, 0, 'Page must be greater than zero');
        $this->page = $page;
    }

    /** @throws AssertionFailedException */
    public static function from(int $page): self
    {
        return new self($page);
    }

    public function value(): int
    {
        return $this->page;
    }
}
