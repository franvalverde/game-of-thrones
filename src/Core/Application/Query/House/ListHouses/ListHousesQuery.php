<?php

declare(strict_types=1);

namespace Whalar\Core\Application\Query\House\ListHouses;

use Whalar\Shared\Application\Query\Query;

final class ListHousesQuery implements Query
{
    public function __construct(public readonly int $page, public readonly int $size, public readonly string $order)
    {
    }
}
