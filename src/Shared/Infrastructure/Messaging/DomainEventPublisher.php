<?php

declare(strict_types=1);

namespace Whalar\Shared\Infrastructure\Messaging;

use BadMethodCallException;
use Whalar\Shared\Domain\Event\DomainEvent;
use Whalar\Shared\Domain\Event\DomainEventSubscriber;
use Throwable;

final class DomainEventPublisher
{
    private static ?DomainEventPublisher $instance = null;

    /** @var array<DomainEventSubscriber> */
    private array $subscribers;

    /** @var array<DomainEvent> */
    private array $events;

    private int $id = 0;

    private function __construct()
    {
        $this->subscribers = [];
        $this->events = [];
    }

    public static function instance(): self
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /** @throws BadMethodCallException */
    public function __clone()
    {
        throw new BadMethodCallException(sprintf('%s is not supported.', __FUNCTION__));
    }

    public function subscribe(DomainEventSubscriber $subscriber): int
    {
        $identifier = $this->id;
        $this->subscribers[$identifier] = $subscriber;
        ++$this->id;

        return $identifier;
    }

    /** @throws Throwable */
    public function publish(DomainEvent $domainEvent): void
    {
        foreach ($this->subscribers as $subscriber) {
            if (!$subscriber->isSubscribedTo($domainEvent)) {
                continue;
            }

            $subscriber->notify($domainEvent);
        }

        $this->events[] = $domainEvent;
    }

    /** @return array<DomainEvent> */
    public function events(): array
    {
        return $this->events;
    }

    public function resetEvents(): void
    {
        $this->events = [];
    }

    public function ofId(int $id): ?DomainEventSubscriber
    {
        return $this->subscribers[$id] ?? null;
    }
}
