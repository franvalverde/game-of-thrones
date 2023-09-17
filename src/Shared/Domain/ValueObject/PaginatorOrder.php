<?php

declare(strict_types=1);

namespace Whalar\Shared\Domain\ValueObject;

enum PaginatorOrder: string implements \JsonSerializable
{
    case ASC = 'asc';
    case DESC = 'desc';

    public function jsonSerialize(): string
    {
        return $this->value;
    }
}
