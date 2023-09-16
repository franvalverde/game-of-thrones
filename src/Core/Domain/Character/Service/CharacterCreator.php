<?php

declare(strict_types=1);

namespace Whalar\Core\Domain\Character\Service;

use Whalar\Core\Domain\Character\Aggregate\Character;
use Whalar\Core\Domain\Character\Exception\CharacterAlreadyExistsException;
use Whalar\Core\Domain\Character\Repository\CharacterRepository;
use Whalar\Core\Domain\Character\ValueObject\CharacterId;
use Whalar\Core\Domain\Character\ValueObject\CharacterKingsGuard;
use Whalar\Core\Domain\Character\ValueObject\CharacterRoyal;
use Whalar\Shared\Domain\ValueObject\AggregateId;
use Whalar\Shared\Domain\ValueObject\ImageUrl;
use Whalar\Shared\Domain\ValueObject\Name;

final class CharacterCreator
{
    public function __construct(private readonly CharacterRepository $characters)
    {
    }

    /** @throws CharacterAlreadyExistsException|\Throwable */
    public function execute(
        AggregateId $id,
        CharacterId $internalId,
        Name $name,
        CharacterRoyal $royal,
        CharacterKingsGuard $kingsGuard,
        ?Name $nickname,
        ?ImageUrl $imageThumb,
        ?ImageUrl $imageFull,
    ): void {
        $this->ensureCharacterNotExists(internalId: $internalId, name: $name);

        $this->characters->save(
            Character::create(
                id: $id,
                internalId: $internalId,
                name: $name,
                royal: $royal,
                kingsGuard: $kingsGuard,
                nickname: $nickname,
                imageThumb: $imageThumb,
                imageFull: $imageFull,
            ),
        );
    }

    /** @throws CharacterAlreadyExistsException */
    private function ensureCharacterNotExists(CharacterId $internalId, Name $name): void
    {
        if (null !== $this->characters->ofName($name) || null !== $this->characters->ofInternalId($internalId)) {
            throw CharacterAlreadyExistsException::from($name->value());
        }
    }
}
