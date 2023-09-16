<?php

declare(strict_types=1);

namespace Whalar\Core\Application\Command\Character\CreateCharacter;

use Whalar\Core\Domain\Character\Service\CharacterCreator;
use Whalar\Core\Domain\Character\ValueObject\CharacterId;
use Whalar\Core\Domain\Character\ValueObject\CharacterKingsGuard;
use Whalar\Core\Domain\Character\ValueObject\CharacterRoyal;
use Whalar\Shared\Application\Command\CommandHandler;
use Whalar\Shared\Domain\ValueObject\AggregateId;
use Whalar\Shared\Domain\ValueObject\ImageUrl;
use Whalar\Shared\Domain\ValueObject\Name;

final class CreateCharacterCommandHandler implements CommandHandler
{
    public function __construct(private readonly CharacterCreator $creator)
    {
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
            nickname: null !== $command->nickname ? Name::from($command->nickname) : null,
            imageThumb: null !== $command->imageThumb ? ImageUrl::from($command->imageThumb) : null,
            imageFull: null !== $command->imageFull ? ImageUrl::from($command->imageFull) : null,
        );
    }
}
