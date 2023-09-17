<?php

declare(strict_types=1);

namespace Whalar\Core\Application\Query\Character\ListCharacters;

use Whalar\Shared\Application\Query\Query;

final class ListCharactersQuery implements Query
{
    public function __construct(public readonly int $page, public readonly int $size, public readonly string $order)
    {
    }
}
