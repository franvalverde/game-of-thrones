<?php

declare(strict_types=1);

namespace Whalar\Tests\Shared\Core\Application\Command\Actor\CreateActor;

use Assert\AssertionFailedException;
use Whalar\Core\Application\Command\Actor\CreateActor\CreateActorCommand;
use Whalar\Tests\Shared\Core\Domain\Actor\ValueObject\ActorIdMother;
use Whalar\Tests\Shared\Shared\Domain\ValueObject\AggregateIdMother;
use Whalar\Tests\Shared\Shared\Domain\ValueObject\NameMother;

final class CreateActorCommandMother
{
    /** @throws AssertionFailedException */
    public static function create(
        ?string $id = null,
        ?string $internalId = null,
        ?string $name = null,
        ?array $seasonsActive = null,
    ): CreateActorCommand {
        return new CreateActorCommand(
            id: $id ?? AggregateIdMother::random()->id(),
            actorId: $internalId ?? ActorIdMother::create(),
            name: $name ?? NameMother::random()->value(),
            seasonsActive: $seasonsActive,
        );
    }
}
