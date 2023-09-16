<?php

declare(strict_types=1);

namespace Whalar\Core\Domain\Actor\Service;

use Whalar\Core\Domain\Actor\Aggregate\Actor;
use Whalar\Core\Domain\Actor\Exception\ActorNotFoundException;
use Whalar\Core\Domain\Actor\Repository\ActorRepository;
use Whalar\Core\Domain\Actor\ValueObject\ActorId;

final class ActorFinder
{
    public function __construct(private readonly ActorRepository $actors)
    {
    }

    /** @throws ActorNotFoundException|\Throwable */
    public function ofActorIdOrFail(ActorId $actorId): Actor
    {
        $actor = $this->actors->ofInternalId($actorId);

        if (null === $actor) {
            throw ActorNotFoundException::from($actorId->value());
        }

        return $actor;
    }
}
