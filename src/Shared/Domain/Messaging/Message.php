<?php

declare(strict_types=1);

namespace Whalar\Shared\Domain\Messaging;

use Assert\AssertionFailedException;
use Carbon\CarbonImmutable;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;
use Whalar\Shared\Domain\Messaging\AsyncApi\AsyncApiChannel;

/**
 * @phpstan-type RawMessage array{
 *      message_id: string,
 *      message_name: string,
 *      payload: RawPayload,
 *      occurred_on_in_ms: int,
 *      occurred_on_in_atom: string,
 * }
 *
 * @phpstan-type RawPayload array<string, mixed>
 */
abstract class Message implements \JsonSerializable
{
    private const BUSINESS = 'whalar';

    private const MESSAGE_SERVICE_MAP = [
        'Core' => 'got',
    ];

    private const ISO8601_ZULU_MICROSECOND = 'Y-m-d\TH:i:s.u\Z';

    private MessageId $messageId;

    /** @var array<mixed> */
    private array $messagePayload;

    private \DateTimeInterface $messageOccurredOn;

    /** @param array<string, mixed> $payload */
    final private function __construct(MessageId $messageId, array $payload, \DateTimeInterface $occurredOn)
    {
        $this->setMessageId($messageId);
        $this->setMessagePayload($payload);
        $this->setMessageOccurredOn($occurredOn);
    }

    /**
     * @param array<string, mixed> $payload
     *
     * @return static
     */
    final public static function fromPayload(
        MessageId $messageId,
        array $payload,
        ?\DateTimeInterface $occurredOn = null,
    ): self {
        $message = new static($messageId, $payload, $occurredOn ?? CarbonImmutable::now('utc'));
        $message->assertPayload();

        return $message;
    }

    public static function messageVersion(): int
    {
        throw new \RuntimeException(
            'This method acts as an abstract one, should be override and should not be called directly.',
        );
    }

    public static function messageType(): string
    {
        throw new \RuntimeException(
            'This method acts as an abstract one, should be override and should not be called directly.',
        );
    }

    public static function messageAggregate(): string
    {
        throw new \RuntimeException(
            'This method acts as an abstract one, should be override and should not be called directly.',
        );
    }

    public static function messageAction(): string
    {
        throw new \RuntimeException(
            'This method acts as an abstract one, should be override and should not be called directly.',
        );
    }

    final public static function messageService(): string
    {
        $boundedContextNamespace = Str::of(static::class)->explode('\\')->slice(1)->first();

        $messageService = self::MESSAGE_SERVICE_MAP[$boundedContextNamespace] ?? null;

        if (null === $messageService) {
            throw new \LogicException('Message service mapping not found');
        }

        return $messageService;
    }

    /** @throws AssertionFailedException */
    final public static function messageChannel(): string
    {
        return AsyncApiChannel::from(
            self::BUSINESS,
            static::messageService(),
            static::messageVersion(),
            static::messageType(),
            static::messageAggregate(),
            static::messageAction(),
        )->format();
    }

    final public function messageId(): MessageId
    {
        return $this->messageId;
    }

    /** @return array<mixed> */
    final public function messagePayload(): array
    {
        return $this->messagePayload;
    }

    final public function messageOccurredOn(): \DateTimeInterface
    {
        return $this->messageOccurredOn;
    }

    /**
     * @return array<mixed>
     *
     * @throws AssertionFailedException
     */
    #[ArrayShape([
        'message_id' => 'string',
        'message_name' => 'string',
        'payload' => 'array|mixed',
        'occurred_on_in_ms' => 'false|int',
        'occurred_on_in_atom' => 'string',
    ])]
    final public function toArray(): array
    {
        return [
            'message_id' => $this->messageId()->value(),
            'message_name' => $this::messageChannel(),
            'payload' => $this->messagePayload(),
            'occurred_on_in_ms' => $this->messageOccurredOn()->getTimestamp(),
            'occurred_on_in_atom' => $this->messageOccurredOn()->format(self::ISO8601_ZULU_MICROSECOND),
        ];
    }

    /**
     * @return array<mixed>
     *
     * @throws AssertionFailedException
     */
    #[ArrayShape([
        'message_id' => 'string',
        'message_name' => 'string',
        'payload' => 'array|mixed',
        'occurred_on_in_ms' => 'false|int',
        'occurred_on_in_atom' => 'string',
    ])]
    final public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    abstract protected function assertPayload(): void;

    /** @param class-string $class */
    final protected static function messageAggregateFromClass(string $class): string
    {
        return (string) Str::of(class_basename($class))->snake();
    }

    private function setMessageId(MessageId $id): void
    {
        $this->messageId = $id;
    }

    /** @param array<string, mixed> $payload */
    private function setMessagePayload(array $payload): void
    {
        $this->ensureThatPayloadHasOnlyPrimitives($payload);
        $this->messagePayload = $payload;
    }

    private function setMessageOccurredOn(\DateTimeInterface $occurredOn): void
    {
        $this->messageOccurredOn = $occurredOn;
    }

    /**
     * @param array<string, mixed> $payload
     *
     * @throws \InvalidArgumentException
     */
    private function ensureThatPayloadHasOnlyPrimitives(array $payload, string $index = 'payload'): void
    {
        array_walk(
            $payload,
            function ($item, $currentIndex) use ($index): void {
                $fieldName = "$index.$currentIndex";

                $this->ensureThatValueIsPrimitive($item, $fieldName);
            },
        );
    }

    /* @throws InvalidArgumentException */
    private function ensureThatValueIsPrimitive(mixed $value, string $fieldName): void
    {
        if (\is_object($value)) {
            $message = sprintf('Provided "%s" is an object. Payload parameters only can be primitive.', $fieldName);

            throw new \InvalidArgumentException($message);
        }

        if (true !== \is_array($value)) {
            return;
        }

        $this->ensureThatPayloadHasOnlyPrimitives($value, $fieldName);
    }
}
