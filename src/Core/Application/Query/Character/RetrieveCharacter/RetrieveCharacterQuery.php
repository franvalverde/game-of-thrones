<?php

declare(strict_types=1);

namespace Whalar\Core\Application\Query\Character\RetrieveCharacter;

use Whalar\Shared\Application\Query\Query;

final class RetrieveCharacterQuery implements Query
{
    public function __construct(public readonly string $characterId)
    {
    }
}
