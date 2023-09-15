<?php

declare(strict_types=1);

namespace Whalar\Shared\Infrastructure\Messaging\Transport\Serialization;

use Assert\AssertionFailedException;
use Carbon\CarbonImmutable;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Stamp\DispatchAfterCurrentBusStamp;
use Whalar\Shared\Domain\Messaging\AsyncApi\AsyncApiChannel;

/**
 * @phpstan-type EncodedDomainEvent array{
 *      message_id: string,
 *      message_name: string,
 *      payload: array<string, array<mixed>|bool|int|object|string>,
 *      occurred_on_in_ms: int,
 * }
 */
final class DomainEventJsonSerializer
{
    private DomainEventMapping $mapping;

    public function __construct(DomainEventMapping $mapping)
    {
        $this->mapping = $mapping;
    }

    /**
     * @param array<mixed> $encodedDomainEvent
     * @throws AssertionFailedException
     */
    public function decode(array $encodedDomainEvent): Envelope
    {
        $messageName = $encodedDomainEvent['message_name'];

        $asyncApiFormatter = AsyncApiChannel::fromString($messageName);

        $domainEventClass = $this->mapping->for($asyncApiFormatter->action());

        $domainEvent = $domainEventClass::create(
            $encodedDomainEvent['message_id'],
            $asyncApiFormatter->messageVersion(),
            CarbonImmutable::createFromTimestampMs($encodedDomainEvent['occurred_on_in_ms']),
            $encodedDomainEvent['payload'],
        );

        return (new Envelope($domainEvent))->with(new DispatchAfterCurrentBusStamp());
    }
}
