<?php

declare(strict_types=1);

namespace Whalar\Shared\Infrastructure\Messaging\Subscriber;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Whalar\Shared\Domain\Event\Aggregate\StoredEvent;
use Whalar\Shared\Domain\Event\DomainEvent;
use Whalar\Shared\Domain\Event\DomainEventSubscriber;
use Whalar\Shared\Domain\Event\ValueObject\MessageId;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DispatchAfterCurrentBusStamp;
use Throwable;

final class InMemoryStoredEventSubscriber implements DomainEventSubscriber
{
    private MessageBusInterface $eventBus;

    /** @var Collection<string, StoredEvent> */
    private Collection $events;

    #[Pure]
    public function __construct(MessageBusInterface $eventBus)
    {
        $this->eventBus = $eventBus;
        $this->events = new ArrayCollection();
    }

    /** @throws Throwable */
    public function notify(DomainEvent $domainEvent): void
    {
        $this->eventBus->dispatch(
            (new Envelope($domainEvent))->with(new DispatchAfterCurrentBusStamp()),
        );

        $this->events->set($domainEvent->messageId(), StoredEvent::create(
            MessageId::from($domainEvent->messageId()),
            $domainEvent->messageDefinition(),
            $domainEvent->messagePayload(),
            $domainEvent->messageAggregateId(),
            $domainEvent->occurredOn(),
        ));
    }

    public function events(): Collection
    {
        return $this->events;
    }

    public function resetEvents(): void
    {
        $this->events = new ArrayCollection();
    }

    public function isSubscribedTo(DomainEvent $domainEvent): bool
    {
        return true;
    }
}
