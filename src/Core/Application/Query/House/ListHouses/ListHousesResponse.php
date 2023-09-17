<?php

declare(strict_types=1);

namespace Whalar\Core\Application\Query\House\ListHouses;

use Whalar\Shared\Application\Query\PaginatorResponse;

final class ListHousesResponse extends PaginatorResponse implements \JsonSerializable
{
    public function jsonSerialize(): array
    {
        return [
            'houses' => $this->results(),
            'meta' => $this->meta(),
        ];
    }
}
