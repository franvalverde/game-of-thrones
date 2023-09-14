<?php

declare(strict_types=1);

namespace Whalar\Shared\Domain\Event;

use Assert\Assertion;
use DateTimeInterface;
use Whalar\Shared\Domain\Messaging\AsyncApi\AsyncApiChannel;
use http\Exception\RuntimeException;
use InvalidArgumentException;
use JetBrains\PhpStorm\ArrayShape;
use JsonSerializable;
use ReflectionClass;
use Throwable;

use function is_object;

abstract class DomainEvent implements JsonSerializable
{
    private const BUSINESS_NAME = 'whalar';
    private const DEPARTMENT_NAME = 'got';
    private const EVENT_TYPE = 'domain_event';

    private string $messageId;
    private int $messageVersion;
    private DateTimeInterface $occurredOn;

    /** @var array<mixed> */
    private array $messagePayload;

    /**
     * @param array<mixed> $messagePayload
     *
     * @throws InvalidArgumentException
     */
    final private function __construct(
        string $messageId,
        int $messageVersion,
        DateTimeInterface $occurredOn,
        array $messagePayload,
    ) {
        $this->messageId = $messageId;
        $this->messageVersion = $messageVersion;
        $this->occurredOn = $occurredOn;
        $this->setMessagePayload($messagePayload);
    }

    /**
     * @param array<mixed> $messagePayload
     *
     * @throws InvalidArgumentException
     */
    final public static function create(
        string $messageId,
        int $messageVersion,
        DateTimeInterface $occurredOn,
        array $messagePayload,
    ): self {
        $message = new static(
            $messageId,
            $messageVersion,
            $occurredOn,
            $messagePayload,
        );

        $message->setupPayload();

        return $message;
    }

    public function messageAggregateId(): string
    {
        throw new RuntimeException(
            'This method acts as an abstract one, should be override and should not be called directly.',
        );
    }

    public function messageAggregateName(): string
    {
        throw new RuntimeException(
            'This method acts as an abstract one, should be override and should not be called directly.',
        );
    }

    public function messageId(): string
    {
        return $this->messageId;
    }

    /* @throws Throwable */
    public static function messageName(): string
    {
        $value = (new ReflectionClass(static::class))->getShortName();

        if (!ctype_lower($value)) {
            $value = preg_replace('/\s+/u', '', $value) ?? '';
            $value = preg_replace('/(.)(?=[A-Z])/u', '$1'.'_', $value) ?? '';
            $value = strtolower($value);
        }

        return $value;
    }

    /* @throws Throwable */
    public function messageDefinition(): string
    {
        return AsyncApiChannel::from(
            self::BUSINESS_NAME,
            self::DEPARTMENT_NAME,
            $this->messageVersion,
            self::EVENT_TYPE,
            $this->messageAggregateName(),
            $this::messageName(),
        )->format();
    }

    /** @return array<mixed> */
    public function messagePayload(): array
    {
        return $this->messagePayload;
    }

    public function occurredOn(): DateTimeInterface
    {
        return $this->occurredOn;
    }

    /**
     * @return array<mixed>
     *
     * @throws Throwable
     */
    #[ArrayShape([
        'message_id' => 'string',
        'message_name' => 'string',
        'payload' => 'mixed',
        'occurred_on_in_ms' => 'false|int',
        'occurred_on_in_atom' => "\DateTimeInterface",
    ])]
    public function jsonSerialize(): array
    {
        return [
            'message_id' => $this->messageId(),
            'message_name' => $this->messageDefinition(),
            'payload' => $this->messagePayload(),
            'occurred_on_in_ms' => $this->occurredOn()->getTimestamp(),
            'occurred_on_in_atom' => $this->occurredOn(),
        ];
    }

    abstract protected function setupPayload(): void;

    /**
     * @param array<mixed> $messagePayload
     *
     * @throws InvalidArgumentException
     */
    private function setMessagePayload(array $messagePayload): void
    {
        $this->ensureThatPayloadHasOnlyPrimitives($messagePayload);
        $this->messagePayload = $messagePayload;
    }

    /**
     * @param array<mixed> $payload
     * @noinspection PhpSameParameterValueInspection
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
        if (is_object($value)) {
            $message = sprintf('Provided "%s" is a invalid primitive.', $fieldName);

            throw new InvalidArgumentException($message, Assertion::INVALID_OBJECT);
        }
    }

    /** @param class-string $fullClassName */
    final protected function aggregateNameFromClassname(string $fullClassName): string
    {
        $className = explode('\\', $fullClassName);

        $nameSanitized = preg_replace('/[A-Z]([A-Z](?![a-z]))*/', '_$0', end($className));

        return ltrim(strtolower("$nameSanitized"), '_');
    }
}
