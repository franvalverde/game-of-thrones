<?php

declare(strict_types=1);

namespace Whalar\Core\Domain\Character\Service;

use Whalar\Core\Domain\Character\Aggregate\Character;
use Whalar\Core\Domain\Character\Exception\CharacterNotFoundException;
use Whalar\Core\Domain\Character\Repository\CharacterRepository;
use Whalar\Core\Domain\Character\ValueObject\CharacterId;

final class CharacterFinder
{
    public function __construct(private readonly CharacterRepository $characters)
    {
    }

    /** @throws CharacterNotFoundException|\Throwable */
    public function ofCharacterIdOrFail(CharacterId $characterId): Character
    {
        $character = $this->characters->ofInternalId($characterId);

        if (null === $character) {
            throw CharacterNotFoundException::from($characterId->value());
        }

        return $character;
    }
}
