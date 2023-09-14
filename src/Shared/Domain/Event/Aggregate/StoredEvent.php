<?php

declare(strict_types=1);

namespace Whalar\Shared\Domain\Event\Aggregate;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Carbon\CarbonImmutable;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use Whalar\Shared\Domain\Event\ValueObject\MessageId;

final class StoredEvent implements \JsonSerializable
{
    private MessageId $messageId;

    private string $messageName;

    /** @var array<mixed> */
    private array $messageBody;

    private string $aggregateId;

    private CarbonImmutable $occurredAt;

    /**
     * @param array<mixed> $messageBody
     *
     * @throws AssertionFailedException
     */
    private function __construct(
        MessageId $messageId,
        string $messageName,
        array $messageBody,
        string $aggregateId,
        \DateTimeInterface $occurredAt,
    ) {
        $this->setMessageId($messageId);
        $this->setMessageName($messageName);
        $this->setMessageBody($messageBody);
        $this->setAggregateId($aggregateId);
        $this->setOccurredAt($occurredAt);
    }

    /**
     * @param array<mixed> $messageBody
     *
     * @throws AssertionFailedException
     */
    public static function create(
        MessageId $messageId,
        string $messageName,
        array $messageBody,
        string $aggregateId,
        \DateTimeInterface $occurredAt,
    ): self {
        return new self($messageId, $messageName, $messageBody, $aggregateId, $occurredAt);
    }

    public function messageId(): MessageId
    {
        return $this->messageId;
    }

    public function messageName(): string
    {
        return $this->messageName;
    }

    /** @return array<mixed> */
    public function messageBody(): array
    {
        return $this->messageBody;
    }

    public function aggregateId(): string
    {
        return $this->aggregateId;
    }

    public function occurredAt(): CarbonImmutable
    {
        return $this->occurredAt;
    }

    #[Pure]
    public function __toString(): string
    {
        return $this->messageName();
    }

    /** @return array<mixed> */
    #[ArrayShape([
        'id' => "\Whalar\Shared\Domain\Event\ValueObject\MessageId",
        'topic' => 'string',
        'payload' => 'mixed',
        'resourceId' => 'string',
        'createdAt' => 'string',
    ])]
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->messageId(),
            'topic' => $this->messageName(),
            'payload' => $this->messageBody(),
            'resourceId' => $this->aggregateId(),
            'createdAt' => $this->occurredAt()->toIso8601ZuluString(),
        ];
    }

    private function setMessageId(MessageId $messageId): void
    {
        $this->messageId = $messageId;
    }

    /** @throws AssertionFailedException */
    private function setMessageName(string $messageName): void
    {
        Assertion::notBlank($messageName);
        $this->messageName = trim($messageName);
    }

    /** @param array<mixed> $messageBody */
    private function setMessageBody(array $messageBody): void
    {
        $this->messageBody = $messageBody;
    }

    /** @throws AssertionFailedException */
    private function setAggregateId(string $aggregateId): void
    {
        Assertion::uuid($aggregateId);
        $this->aggregateId = $aggregateId;
    }

    private function setOccurredAt(\DateTimeInterface $occurredAt): void
    {
        $this->occurredAt = CarbonImmutable::instance($occurredAt);
    }
}
