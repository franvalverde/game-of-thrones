<?php

declare(strict_types=1);

namespace Whalar\Tests\Shared\Core\Infrastructure\Persistence\InMemory;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JetBrains\PhpStorm\Pure;
use Whalar\Core\Domain\Actor\Aggregate\Actor;
use Whalar\Core\Domain\Actor\Repository\ActorRepository;
use Whalar\Core\Domain\Actor\ValueObject\ActorId;
use Whalar\Shared\Domain\ValueObject\AggregateId;
use Whalar\Shared\Domain\ValueObject\Name;

final class InMemoryActorRepository implements ActorRepository
{
    /** @var Collection<string, Actor> */
    private Collection $actors;

    #[Pure]
    public function __construct()
    {
        $this->actors = new ArrayCollection();
    }

    public function ofId(AggregateId $id): ?Actor
    {
        return $this->actors->get($id->id());
    }

    public function ofName(Name $name): ?Actor
    {
        foreach ($this->actors as $actor) {
            if ($actor->name()->value() === $name->value()) {
                return $actor;
            }
        }

        return null;
    }

    public function save(Actor $actor): void
    {
        $this->actors->set($actor->id()->id(), $actor);
    }

    public function ofInternalId(ActorId $actorId): ?Actor
    {
        foreach ($this->actors as $actor) {
            if ($actor->internalId()->equalsTo($actorId)) {
                return $actor;
            }
        }

        return null;
    }
}
