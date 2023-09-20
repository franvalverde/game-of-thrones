<?php

declare(strict_types=1);

namespace Whalar\Shared\Infrastructure\Persistence\InMemory;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\QueryException;
use Doctrine\Persistence\ObjectRepository;
use Whalar\Shared\Domain\Event\Aggregate\StoredEvent;
use Whalar\Shared\Domain\Event\Exception\StoredEventNotFoundException;
use Whalar\Shared\Domain\Event\Repository\StoredEventRepository;
use Whalar\Shared\Domain\Event\ValueObject\MessageId;
use Whalar\Shared\Infrastructure\Generator\UuidGenerator;

final class DoctrineStoredEventRepository implements StoredEventRepository
{
    /** @var EntityRepository<StoredEvent>|ObjectRepository<StoredEvent> */
    private ObjectRepository $storedEvents;

    private EntityManagerInterface $entityManager;

    private UuidGenerator $uuidGenerator;

    public function __construct(EntityManagerInterface $entityManager, UuidGenerator $uuidGenerator)
    {
        $this->entityManager = $entityManager;
        $this->storedEvents = $entityManager->getRepository(StoredEvent::class);
        $this->uuidGenerator = $uuidGenerator;
    }

    public function save(StoredEvent $storedEvent): void
    {
        $this->entityManager->persist($storedEvent);
        $this->entityManager->flush();
    }

    public function nextIdentity(): string
    {
        return $this->uuidGenerator->generate();
    }

    /**
     * @return array<StoredEvent>
     * @throws QueryException
     * @throws AssertionFailedException
     */
    public function search(
        ?string $from,
        ?string $to,
        int $numPage = 0,
        int $limit = 100,
        string $orderBy = 'asc',
    ): array {
        Assertion::greaterOrEqualThan($numPage, 1, 'Page must be greater or equal than 1');
        Assertion::greaterOrEqualThan($limit, 1, 'Limit must be greater or equal than 1');

        $field = 'occurredAt';

        $criteria = $this->applyFilters($from, $to, $field);
        $this->applyPaginator($criteria, $numPage, $limit);
        $this->applySorter($criteria, $field, $orderBy);

        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('s')->from(StoredEvent::class, 's');
        $queryBuilder->addCriteria($criteria);

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @throws NoResultException
     * @throws NonUniqueResultException
     * @throws QueryException
     */
    public function searchCount(?string $from, ?string $to): int
    {
        $field = 'occurredAt';
        $criteria = $this->applyFilters($from, $to, $field);
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('count(s.messageId)')->from(StoredEvent::class, 's');
        $queryBuilder->addCriteria($criteria);

        return $queryBuilder->getQuery()->getSingleScalarResult();
    }

    /** @param Collection<string, StoredEvent> $storedEvents */
    public function saveAll(Collection $storedEvents): void
    {
        $events = $storedEvents->toArray();
        array_walk($events, [$this, 'save']);
    }

    public function ofIdOrFail(MessageId $messageId): StoredEvent
    {
        $storedEvent = $this->storedEvents->find($messageId->id());

        if (!($storedEvent instanceof StoredEvent)) {
            throw StoredEventNotFoundException::from($messageId->id());
        }

        return $storedEvent;
    }

    private function applyFilters(?string $from, ?string $to, string $field): Criteria
    {
        $criteria = Criteria::create();

        if (null !== $from) {
            $criteria->where(Criteria::expr()->gte($field, $from));
        }

        if (null === $from && null !== $to) {
            $criteria->where(Criteria::expr()->lte($field, $to));
        }

        if (null !== $from && null !== $to) {
            $criteria->andWhere(Criteria::expr()->lte($field, $to));
        }

        return $criteria;
    }

    private function applyPaginator(Criteria $criteria, int $numPage, int $limit): void
    {
        $criteria->setFirstResult(($numPage - 1) * $limit)
            ->setMaxResults($limit);
    }

    private function applySorter(Criteria $criteria, string $field, string $orderBy): void
    {
        $orderDirection = 'asc' === $orderBy
            ? Criteria::ASC
            : Criteria::DESC;
        $criteria->orderBy([$field => $orderDirection]);
    }
}
