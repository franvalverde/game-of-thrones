<?php

declare(strict_types=1);

namespace Whalar\Shared\Domain\Event\Repository;

use Doctrine\Common\Collections\Collection;
use Whalar\Shared\Domain\Event\Aggregate\StoredEvent;
use Whalar\Shared\Domain\Event\ValueObject\MessageId;
use Throwable;

interface StoredEventRepository
{
    /** @throws Throwable */
    public function save(StoredEvent $storedEvent): void;

    /**
     * @param Collection<string, StoredEvent> $storedEvents
     * @throws Throwable
     */
    public function saveAll(Collection $storedEvents): void;

    /**
     * @return array<StoredEvent>
     * @throws Throwable
     */
    public function search(
        ?string $from,
        ?string $to,
        int $numPage = 0,
        int $limit = 100,
        string $orderBy = 'asc',
    ): array;

    /** @throws Throwable */
    public function searchCount(?string $from, ?string $to): int;

    /** @throws Throwable */
    public function nextIdentity(): string;

    /** @throws Throwable */
    public function ofIdOrFail(MessageId $messageId): StoredEvent;
}
