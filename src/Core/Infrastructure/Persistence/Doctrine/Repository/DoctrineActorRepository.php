<?php

declare(strict_types=1);

namespace Whalar\Core\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ObjectRepository;
use Whalar\Core\Domain\Actor\Aggregate\Actor;
use Whalar\Core\Domain\Actor\Repository\ActorRepository;
use Whalar\Core\Domain\Actor\ValueObject\ActorId;
use Whalar\Shared\Domain\ValueObject\AggregateId;
use Whalar\Shared\Domain\ValueObject\Name;

final class DoctrineActorRepository implements ActorRepository
{
    private EntityManagerInterface $entityManager;

    /** @var EntityRepository<Actor>|ObjectRepository<Actor> */
    private ObjectRepository|EntityRepository $actors;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->actors = $entityManager->getRepository(Actor::class);
    }

    public function ofId(AggregateId $id): ?Actor
    {
        return $this->actors->find($id->id());
    }

    public function ofName(Name $name): ?Actor
    {
        return $this->actors->findOneBy(['name' => $name->value()]);
    }

    public function ofInternalId(ActorId $actorId): ?Actor
    {
        return $this->actors->findOneBy(['internalId' => $actorId->value()]);
    }

    public function save(Actor $actor): void
    {
        $this->entityManager->persist($actor);
        $this->entityManager->flush();
    }
}
