<?php

declare(strict_types=1);

namespace Whalar\Core\Application\Command\Character\RelateCharacters;

use Whalar\Shared\Application\Command\Command;

final class RelateCharactersCommand implements Command
{
    public function __construct(
        public readonly string $relationId,
        public readonly string $characterId,
        public readonly string $relationType,
        public readonly string $relatedTo,
    ) {
    }
}
