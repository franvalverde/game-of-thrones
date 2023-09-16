<?php

declare(strict_types=1);

namespace Whalar\Core\Domain\Character\Repository;

use Whalar\Core\Domain\Character\Aggregate\Character;
use Whalar\Core\Domain\Character\ValueObject\CharacterId;
use Whalar\Shared\Domain\ValueObject\Name;

interface CharacterRepository
{
    public function ofInternalId(CharacterId $id): ?Character;

    public function ofName(Name $name): ?Character;

    public function save(Character $character): void;
}
