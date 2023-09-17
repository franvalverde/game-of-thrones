<?php

declare(strict_types=1);

namespace Whalar\Core\Infrastructure\Persistence\Doctrine\Repository;

use Assert\Assertion;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ObjectRepository;
use Whalar\Core\Domain\House\Aggregate\House;
use Whalar\Core\Domain\House\Repository\HouseRepository;
use Whalar\Shared\Domain\ValueObject\AggregateId;
use Whalar\Shared\Domain\ValueObject\Name;
use Whalar\Shared\Domain\ValueObject\PaginatorOrder;
use Whalar\Shared\Domain\ValueObject\PaginatorPage;
use Whalar\Shared\Domain\ValueObject\PaginatorSize;

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

    public function paginate(PaginatorPage $page, PaginatorSize $size, PaginatorOrder $order): array
    {
        $criteria = Criteria::create()
            ->setFirstResult(($page->value() - 1) * $size->value())
            ->setMaxResults($size->value());

        $results = $this->entityManager
            ->createQueryBuilder()
            ->select('house')
            ->from(House::class, 'house')
            ->addCriteria($criteria)
            ->orderBy('house.name', $order->value)
            ->getQuery()
            ->getResult();

        Assertion::isArray($results);

        $total = $this->entityManager
            ->createQueryBuilder()
            ->select('count(house.id)')
            ->from(House::class, 'house')
            ->addCriteria($criteria)
            ->getQuery()
            ->getSingleScalarResult();

        return [
            'meta' => [
                'currentPage' => $page->value(),
                'lastPage' => (int) \max(\ceil($total / $size->value()), 1),
                'size' => $size->value(),
                'total' => $total,
            ],
            'houses' => $results,
        ];
    }
}
