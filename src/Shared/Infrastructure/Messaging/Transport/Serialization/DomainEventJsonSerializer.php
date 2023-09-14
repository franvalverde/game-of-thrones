<?php

declare(strict_types=1);

namespace Whalar\Shared\Infrastructure\Messaging\Transport\Serialization;

use Assert\AssertionFailedException;
use Carbon\CarbonImmutable;
use Whalar\Shared\Domain\Messaging\AsyncApi\AsyncApiChannel;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Stamp\DispatchAfterCurrentBusStamp;

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

        // @phpstan-ignore-next-line
        $asyncApiFormatter = AsyncApiChannel::fromString($messageName);

        $domainEventClass = $this->mapping->for($asyncApiFormatter->action());

        $domainEvent = $domainEventClass::create(
            // @phpstan-ignore-next-line
            $encodedDomainEvent['message_id'],
            $asyncApiFormatter->messageVersion(),
            // @phpstan-ignore-next-line
            CarbonImmutable::createFromTimestampMs($encodedDomainEvent['occurred_on_in_ms']),
            // @phpstan-ignore-next-line
            $encodedDomainEvent['payload'],
        );

        return (new Envelope($domainEvent))->with(new DispatchAfterCurrentBusStamp());
    }
}
