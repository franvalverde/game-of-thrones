<?php

declare(strict_types=1);

namespace Whalar\Core\Application\Command\Character\CreateCharacter;

use Assert\AssertionFailedException;
use Whalar\Core\Domain\Actor\Aggregate\Actor;
use Whalar\Core\Domain\Actor\Exception\ActorAlreadyHasCharacterException;
use Whalar\Core\Domain\Actor\Exception\ActorNotFoundException;
use Whalar\Core\Domain\Actor\Service\ActorFinder;
use Whalar\Core\Domain\Actor\ValueObject\ActorId;
use Whalar\Core\Domain\Actor\ValueObject\ActorsCollection;
use Whalar\Core\Domain\Character\Exception\CharacterMustHaveActorException;
use Whalar\Core\Domain\Character\Service\CharacterCreator;
use Whalar\Core\Domain\Character\ValueObject\CharacterId;
use Whalar\Core\Domain\Character\ValueObject\CharacterKingsGuard;
use Whalar\Core\Domain\Character\ValueObject\CharacterRoyal;
use Whalar\Core\Domain\House\Service\HouseFinder;
use Whalar\Shared\Application\Command\CommandHandler;
use Whalar\Shared\Domain\ValueObject\AggregateId;
use Whalar\Shared\Domain\ValueObject\ImageUrl;
use Whalar\Shared\Domain\ValueObject\Name;

final class CreateCharacterCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly CharacterCreator $creator,
        private readonly HouseFinder $houseFinder,
        private readonly ActorFinder $actorFinder,
    ) {
    }

    /** @throws \Throwable */
    public function __invoke(CreateCharacterCommand $command): void
    {
        $this->creator->execute(
            id: AggregateId::from($command->id),
            internalId: CharacterId::from($command->characterId),
            name: Name::from($command->name),
            royal: CharacterRoyal::from($command->royal),
            kingsGuard: CharacterKingsGuard::from($command->kingsGuard),
            actors: $this->getActors($command->actors),
            house: null !== $command->houseId ? $this->houseFinder->ofIdOrFail(
                AggregateId::from($command->houseId),
            ) : null,
            nickname: null !== $command->nickname ? Name::from($command->nickname) : null,
            imageThumb: null !== $command->imageThumb ? ImageUrl::from($command->imageThumb) : null,
            imageFull: null !== $command->imageFull ? ImageUrl::from($command->imageFull) : null,
        );
    }

    /** @throws \Throwable|AssertionFailedException|ActorNotFoundException|CharacterMustHaveActorException */
    private function getActors(array $actorIds): ActorsCollection
    {
        if (empty($actorIds)) {
            throw CharacterMustHaveActorException::from();
        }

        return ActorsCollection::from(
            array_map(
                fn (string $actorId) => $this->getUnassignedActorOrFail(ActorId::from($actorId)),
                $actorIds,
            ),
        );
    }

    /** @throws ActorAlreadyHasCharacterException|ActorNotFoundException|\Throwable */
    private function getUnassignedActorOrFail(ActorId $actorId): Actor
    {
        $actor = $this->actorFinder->ofActorIdOrFail($actorId);

        if (null !== $actor->character()) {
            throw ActorAlreadyHasCharacterException::from();
        }

        return $actor;
    }
}
