<?php

declare(strict_types=1);

namespace Whalar\Core\Application\Command\Actor\CreateActor;

use Whalar\Core\Domain\Actor\Service\ActorCreator;
use Whalar\Core\Domain\Actor\ValueObject\ActorId;
use Whalar\Core\Domain\Actor\ValueObject\SeasonsActive;
use Whalar\Shared\Application\Command\CommandHandler;
use Whalar\Shared\Domain\ValueObject\AggregateId;
use Whalar\Shared\Domain\ValueObject\Name;

final class CreateActorCommandHandler implements CommandHandler
{
    public function __construct(private readonly ActorCreator $creator)
    {
    }

    /** @throws \Throwable */
    public function __invoke(CreateActorCommand $command): void
    {
        $this->creator->execute(
            id: AggregateId::from($command->id),
            internalId: ActorId::from($command->actorId),
            name: Name::from($command->name),
            seasonsActive: null !== $command->seasonsActive ? SeasonsActive::fromArray($command->seasonsActive) : null,
        );
    }
}
