<?php

declare(strict_types=1);

namespace Whalar\Shared\Application\Query;

use Whalar\Shared\Domain\ValueObject\PaginatorPage;
use Whalar\Shared\Domain\ValueObject\PaginatorSize;

abstract class PaginatorResponse
{
    public function __construct(
        private readonly array $results,
        private readonly int $numberOfResults,
        private readonly PaginatorPage $pageNumber,
        private readonly PaginatorSize $pageSize,
    ) {
    }

    public function results(): array
    {
        return $this->results;
    }

    public function currentPage(): int
    {
        return $this->pageNumber->value();
    }

    public function lastPage(): int
    {
        try {
            $numberOfPages = (int) ceil($this->total() / $this->size());
        } catch (\Throwable) {
            $numberOfPages = 0;
        }

        return $numberOfPages;
    }

    public function size(): int
    {
        return $this->pageSize->value();
    }

    public function total(): int
    {
        return $this->numberOfResults;
    }

    /** @return array<string, mixed> */
    protected function meta(): array
    {
        return [
            'currentPage' => $this->currentPage(),
            'lastPage' => $this->lastPage(),
            'size' => $this->size(),
            'total' => $this->total(),
        ];
    }
}
