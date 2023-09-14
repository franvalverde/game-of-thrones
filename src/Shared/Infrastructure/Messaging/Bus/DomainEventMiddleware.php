<?php

declare(strict_types=1);

namespace Whalar\Shared\Infrastructure\Messaging\Bus;

use Whalar\Shared\Domain\Event\Repository\StoredEventRepository;
use Whalar\Shared\Infrastructure\Messaging\DomainEventPublisher;
use Whalar\Shared\Infrastructure\Messaging\Subscriber\InMemoryStoredEventSubscriber;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

final class DomainEventMiddleware implements MiddlewareInterface
{
    private StoredEventRepository $events;
    private InMemoryStoredEventSubscriber $inMemoryStoredEventSubscriber;

    public function __construct(
        StoredEventRepository $events,
        InMemoryStoredEventSubscriber $inMemoryStoredEventSubscriber,
    ) {
        $this->events = $events;
        $this->inMemoryStoredEventSubscriber = $inMemoryStoredEventSubscriber;
    }

    /* @throws Throwable */
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $eventsCollector = $this->subscribeToEvents();
        $envelope = $stack->next()->handle($envelope, $stack);

        $this->events->saveAll($eventsCollector->events());

        return $envelope;
    }

    private function subscribeToEvents(): InMemoryStoredEventSubscriber
    {
        $domainEventPublisher = DomainEventPublisher::instance();
        $domainEventPublisher->resetEvents();
        $domainEventPublisher->subscribe($this->inMemoryStoredEventSubscriber);

        return $this->inMemoryStoredEventSubscriber;
    }
}
