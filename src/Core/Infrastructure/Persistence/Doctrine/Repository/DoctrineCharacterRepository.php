<?php

declare(strict_types=1);

namespace Whalar\Core\Infrastructure\Persistence\Doctrine\Repository;

use Assert\Assertion;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ObjectRepository;
use Whalar\Core\Domain\Character\Aggregate\Character;
use Whalar\Core\Domain\Character\Repository\CharacterRepository;
use Whalar\Core\Domain\Character\ValueObject\CharacterId;
use Whalar\Shared\Domain\ValueObject\AggregateId;
use Whalar\Shared\Domain\ValueObject\FilterCollection;
use Whalar\Shared\Domain\ValueObject\Name;
use Whalar\Shared\Domain\ValueObject\PaginatorOrder;
use Whalar\Shared\Domain\ValueObject\PaginatorPage;
use Whalar\Shared\Domain\ValueObject\PaginatorSize;

final class DoctrineCharacterRepository implements CharacterRepository
{
    private EntityManagerInterface $entityManager;

    /** @var EntityRepository<Character>|ObjectRepository<Character> */
    private ObjectRepository|EntityRepository $characters;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->characters = $entityManager->getRepository(Character::class);
    }

    public function ofId(AggregateId $id): ?Character
    {
        return $this->characters->find($id->id());
    }

    public function ofName(Name $name): ?Character
    {
        return $this->characters->findOneBy(['name' => $name->value()]);
    }

    public function ofInternalId(CharacterId $id): ?Character
    {
        return $this->characters->findOneBy(['internalId' => $id->value()]);
    }

    public function save(Character $character): void
    {
        $this->entityManager->persist($character);
        $this->entityManager->flush();
    }

    public function paginate(
        PaginatorPage $page,
        PaginatorSize $size,
        PaginatorOrder $order,
        ?FilterCollection $filter,
    ): array {
        $criteria = Criteria::create()
            ->setFirstResult(($page->value() - 1) * $size->value())
            ->setMaxResults($size->value());

        if (null !== $filter) {
            $criteria->andWhere(Criteria::expr()->{$filter->operator}($filter->key, $filter->value));
        }

        $results = $this->entityManager
            ->createQueryBuilder()
            ->select('character')
            ->from(Character::class, 'character')
            ->addCriteria($criteria)
            ->orderBy('character.name', $order->value)
            ->getQuery()
            ->getResult();

        Assertion::isArray($results);

        $total = $this->entityManager
            ->createQueryBuilder()
            ->select('count(character.id)')
            ->from(Character::class, 'character')
            ->addCriteria($criteria)
            ->getQuery()
            ->getSingleScalarResult();

        return [
            'total' => $total,
            'characters' => $results,
        ];
    }
}
