<?php

declare(strict_types=1);

namespace Whalar\Shared\Infrastructure\Persistence\InMemory;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Carbon\CarbonImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Whalar\Shared\Domain\Event\Aggregate\StoredEvent;
use Whalar\Shared\Domain\Event\Exception\StoredEventNotFoundException;
use Whalar\Shared\Domain\Event\Repository\StoredEventRepository;
use Whalar\Shared\Domain\Event\ValueObject\MessageId;
use Whalar\Shared\Infrastructure\Generator\UuidGenerator;

final class InMemoryStoredEventRepository implements StoredEventRepository
{
    /** @var Collection<string, StoredEvent> */
    private Collection $storedEvents;
    private UuidGenerator $generator;

    /** @param array<string, StoredEvent> $storedEvents */
    public function __construct(UuidGenerator $generator, array $storedEvents = [])
    {
        $this->generator = $generator;
        $this->storedEvents = new ArrayCollection($storedEvents);
    }

    /**
     * @return array<StoredEvent>
     * @throws \Exception|AssertionFailedException
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

        $results = $this->resultsFilteredBy($from, $to);

        $criteria = Criteria::create()
            ->setFirstResult(($numPage - 1) * $limit)
            ->setMaxResults($limit);

        $resultPaginated = $results->matching($criteria);

        return $this->sortResultsByUrl($resultPaginated, $orderBy);
    }

    public function searchCount(?string $from, ?string $to): int
    {
        $results = $this->resultsFilteredBy($from, $to);

        return $results->count();
    }

    public function save(StoredEvent $storedEvent): void
    {
        $id = $storedEvent->messageId()->id();
        $this->storedEvents->set($id, $storedEvent);
    }

    public function nextIdentity(): string
    {
        return $this->generator->generate();
    }

    /** @param Collection<string, StoredEvent> $storedEvents */
    public function saveAll(Collection $storedEvents): void
    {
        $events = $storedEvents->toArray();
        array_walk($events, [$this, 'save']);
    }

    public function ofIdOrFail(MessageId $messageId): StoredEvent
    {
        if (null === $this->storedEvents->get($messageId->id())) {
            throw StoredEventNotFoundException::from($messageId->id());
        }

        $storedEvent = $this->storedEvents->get($messageId->id());
        \assert($storedEvent instanceof StoredEvent);

        return StoredEvent::create(
            $storedEvent->messageId(),
            $storedEvent->messageName(),
            $storedEvent->messageBody(),
            $storedEvent->aggregateId(),
            $storedEvent->occurredAt(),
        );
    }

    /** @return Collection<string, StoredEvent> */
    private function resultsFilteredBy(?string $from, ?string $to): Collection
    {
        return $this->storedEvents->filter(
            static function (StoredEvent $storedEvent) use ($from, $to) {
                $isFiltered = true;

                if (null !== $from) {
                    $isFiltered = $storedEvent->occurredAt() >= CarbonImmutable::createFromFormat('Y-m-d H:i:s', $from);
                }

                if (null !== $to) {
                    $isFiltered = $isFiltered && $storedEvent->occurredAt() <= CarbonImmutable::createFromFormat(
                        'Y-m-d H:i:s',
                        $to,
                    );
                }

                return $isFiltered;
            },
        );
    }

    /**
     * @param Collection<string, StoredEvent> $resultPaginated
     * @return array<StoredEvent>
     */
    private function sortResultsByUrl(Collection $resultPaginated, string $orderBy): array
    {
        /** @var \ArrayIterator<int, StoredEvent> $iterator */
        $iterator = $resultPaginated->getIterator();
        $iterator->uasort(static function (StoredEvent $a, StoredEvent $b) use ($orderBy) {
            $query = 'desc' === $orderBy
                ? $a->occurredAt() > $b->occurredAt()
                : $a->occurredAt() < $b->occurredAt();

            return $query
                ? -1
                : 1;
        });

        return iterator_to_array($iterator);
    }
}
