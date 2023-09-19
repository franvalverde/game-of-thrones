<?php

declare(strict_types=1);

namespace Whalar\Core\Application\Command\Character\RelateCharacters;

use Whalar\Core\Domain\Character\Service\CharacterFinder;
use Whalar\Core\Domain\Character\ValueObject\CharacterId;
use Whalar\Core\Domain\CharacterRelate\Service\CharacterLinker;
use Whalar\Core\Domain\CharacterRelate\ValueObject\CharacterRelation;
use Whalar\Shared\Application\Command\CommandHandler;
use Whalar\Shared\Domain\ValueObject\AggregateId;

final class RelateCharactersCommandHandler implements CommandHandler
{
    public function __construct(private readonly CharacterFinder $finder, private readonly CharacterLinker $linker)
    {
    }

    /** @throws \Throwable */
    public function __invoke(RelateCharactersCommand $command): void
    {
        $this->linker->execute(
            id: AggregateId::from($command->relationId),
            character: $this->finder->ofCharacterIdOrFail(characterId: CharacterId::from($command->characterId)),
            relatedTo: $this->finder->ofCharacterIdOrFail(characterId: CharacterId::from($command->relatedTo)),
            relation: CharacterRelation::from($command->relationType),
        );
    }
}
