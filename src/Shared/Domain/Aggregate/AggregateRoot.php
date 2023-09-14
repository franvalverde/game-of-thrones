<?php

declare(strict_types=1);

namespace Whalar\Shared\Domain\Aggregate;

use Whalar\Shared\Domain\Event\DomainEvent;

abstract class AggregateRoot implements \JsonSerializable
{
    private int $aggregateVersion = 0;

    /** @var array<DomainEvent> */
    private array $events = [];

    final public function aggregateVersion(): int
    {
        return $this->aggregateVersion;
    }

    /** @return array<DomainEvent> */
    final public function events(): array
    {
        return $this->events;
    }

    final public function reset(): void
    {
        $this->events = [];
        $this->aggregateVersion = 0;
    }

    final protected function recordThat(DomainEvent $event): void
    {
        $this->events[] = $event;
        ++$this->aggregateVersion;
    }
}
