<?php

declare(strict_types=1);

namespace Whalar\Core\Domain\Actor\Repository;

use Whalar\Core\Domain\Actor\Aggregate\Actor;
use Whalar\Core\Domain\Actor\ValueObject\ActorId;
use Whalar\Shared\Domain\ValueObject\AggregateId;
use Whalar\Shared\Domain\ValueObject\Name;

interface ActorRepository
{
    public function ofId(AggregateId $id): ?Actor;

    public function ofName(Name $name): ?Actor;

    public function ofInternalId(ActorId $actorId): ?Actor;

    public function save(Actor $actor): void;
}
