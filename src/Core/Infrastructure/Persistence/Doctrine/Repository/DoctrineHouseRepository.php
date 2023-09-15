<?php

declare(strict_types=1);

namespace Whalar\Core\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ObjectRepository;
use Whalar\Core\Domain\House\Aggregate\House;
use Whalar\Core\Domain\House\Repository\HouseRepository;
use Whalar\Shared\Domain\ValueObject\AggregateId;
use Whalar\Shared\Domain\ValueObject\Name;

final class DoctrineHouseRepository implements HouseRepository
{
    private EntityManagerInterface $entityManager;

    /** @var EntityRepository<House>|ObjectRepository<House> */
    private ObjectRepository|EntityRepository $houses;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->houses = $entityManager->getRepository(House::class);
    }

    public function ofId(AggregateId $id): ?House
    {
        return $this->houses->find($id->id());
    }

    public function ofName(Name $name): ?House
    {
        return $this->houses->findOneBy(['name' => $name->value()]);
    }

    public function save(House $house): void
    {
        $this->entityManager->persist($house);
        $this->entityManager->flush();
    }
}
