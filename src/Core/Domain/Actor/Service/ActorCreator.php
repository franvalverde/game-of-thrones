<?php

declare(strict_types=1);

namespace Whalar\Core\Domain\Actor\Service;

use Whalar\Core\Domain\Actor\Aggregate\Actor;
use Whalar\Core\Domain\Actor\Exception\ActorAlreadyExistsException;
use Whalar\Core\Domain\Actor\Repository\ActorRepository;
use Whalar\Core\Domain\Actor\ValueObject\ActorId;
use Whalar\Core\Domain\Actor\ValueObject\SeasonsActive;
use Whalar\Shared\Domain\ValueObject\AggregateId;
use Whalar\Shared\Domain\ValueObject\Name;

final class ActorCreator
{
    public function __construct(private readonly ActorRepository $actors)
    {
    }

    /** @throws ActorAlreadyExistsException|\Throwable */
    public function execute(AggregateId $id, ActorId $internalId, Name $name, ?SeasonsActive $seasonsActive): void
    {
        $this->ensureActorNotExists(id: $id, name: $name, internalId: $internalId);

        $this->actors->save(Actor::create($id, $internalId, $name, $seasonsActive));
    }

    /** @throws ActorAlreadyExistsException */
    private function ensureActorNotExists(AggregateId $id, Name $name, ActorId $internalId): void
    {
        if (
            null !== $this->actors->ofId($id) ||
            null !== $this->actors->ofName($name) ||
            null !== $this->actors->ofInternalId($internalId)
        ) {
            throw ActorAlreadyExistsException::from($name->value());
        }
    }
}
