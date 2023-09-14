<?php

declare(strict_types=1);

namespace Whalar\Tests\Shared\Shared\Infrastructure\Domain\Messaging\Event;

use Whalar\Shared\Domain\Messaging\Event\DomainEvent;
use Whalar\Shared\Domain\Messaging\Event\DomainEventSubscriber;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

final class SpyEventSubscriber implements DomainEventSubscriber
{
    /** @var Collection<string, DomainEvent> */
    private Collection $events;

    public function __construct()
    {
        $this->events = new ArrayCollection();
    }

    public function notify(DomainEvent $domainEvent): void
    {
        $this->events[] = $domainEvent;
    }

    public function isSubscribedTo(DomainEvent $domainEvent): bool
    {
        return true;
    }

    public function events(): Collection
    {
        return $this->events;
    }
}
