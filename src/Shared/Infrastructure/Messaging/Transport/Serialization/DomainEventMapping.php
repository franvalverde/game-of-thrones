<?php

declare(strict_types=1);

namespace Whalar\Shared\Infrastructure\Messaging\Transport\Serialization;

use Whalar\Shared\Application\Event\EventHandler;
use Whalar\Shared\Domain\Event\DomainEvent;

use function Lambdish\Phunctional\map;
use function Lambdish\Phunctional\reduce;
use function Lambdish\Phunctional\reindex;

final class DomainEventMapping
{
    /** @var array<string, class-string<DomainEvent>> */
    private array $mapping;

    /** @param iterable<array-key, EventHandler> $eventHandlers */
    public function __construct(iterable $eventHandlers)
    {
        // @phpstan-ignore-next-line
        $this->mapping = reduce($this->domainEventsExtractor(), $eventHandlers, []);
    }

    /** @return class-string<DomainEvent> */
    public function for(string $name): string
    {
        if (!isset($this->mapping[$name])) {
            throw new \RuntimeException("The DomainEvent class for <$name> doesn't exist.");
        }

        return $this->mapping[$name];
    }

    private function domainEventsExtractor(): callable
    {
        /*
         * @param array<EventHandler> $eventHandlers
         * @return array<string, class-string<DomainEvent>>
         */
        return fn (array $eventHandlers, EventHandler $eventHandler): array => array_merge(
            $eventHandlers,
            reindex(
                $this->domainEventNameExtractor(),
                map($this->prepareEventHandlerMessages(), $eventHandler::getHandledMessages()),
            ),
        );
    }

    private function domainEventNameExtractor(): callable
    {
        /*
         * @param class-string<DomainEvent> $eventClass
         * @throws Throwable
         */
        return static fn ($value, string $eventClass): string => $eventClass::messageName();
    }

    private function prepareEventHandlerMessages(): callable
    {
        /*
         * @param class-string<DomainEvent> $eventClass
         * @return class-string<DomainEvent>
         */
        return static fn ($value, string $eventClass): string => $eventClass;
    }
}
