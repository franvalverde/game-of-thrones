<?php

declare(strict_types=1);

namespace Whalar\Shared\Domain\Event;

use Doctrine\Common\Collections\Collection;
use Whalar\Shared\Domain\Event\Aggregate\StoredEvent;

interface DomainEventSubscriber
{
    /** @return Collection<string, StoredEvent> */
    public function events(): Collection;

    /** @throws \Throwable */
    public function notify(DomainEvent $domainEvent): void;

    public function isSubscribedTo(DomainEvent $domainEvent): bool;
}
