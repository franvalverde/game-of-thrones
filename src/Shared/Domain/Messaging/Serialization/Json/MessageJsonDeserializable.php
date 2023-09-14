<?php

declare(strict_types=1);

namespace Whalar\Shared\Domain\Messaging\Serialization\Json;

use Assert\Assert;
use Carbon\CarbonImmutable;
use Whalar\Shared\Domain\Messaging\AsyncApi\AsyncApiChannel;
use Whalar\Shared\Domain\Messaging\Message;
use Whalar\Shared\Domain\Messaging\MessageId;
use Whalar\Shared\Domain\Messaging\Serialization\MessageDeserializable;
use Whalar\Shared\Domain\Messaging\Serialization\MessageMapping;
use Whalar\Shared\Domain\Messaging\Serialization\SerializationException;
use Whalar\Shared\Domain\Utils;
use Throwable;

final class MessageJsonDeserializable implements MessageDeserializable
{
    private MessageMapping $mapping;

    public function __construct(MessageMapping $mapping)
    {
        $this->mapping = $mapping;
    }

    public function deserialize(string $data): Message
    {
        return $this->denormalize($this->decode($data));
    }

    /** @return array<mixed> */
    private function decode(string $data): array
    {
        return Utils::jsonDecode($data);
    }

    /**
     * @param array<mixed> $data
     *
     * @throws SerializationException
     */
    private function denormalize(array $data): Message
    {
        try {
            Assert::that($data['message_id'])->string();
            Assert::that($data['message_name'])->string();
            Assert::that($data['payload'])->isArray();
            Assert::that($data['occurred_on_in_ms'])->integerish();

            $asyncApiChannel = AsyncApiChannel::fromString($data['message_name']);

            $messageClass = $this->mapping->for($asyncApiChannel);

            return $messageClass::fromPayload(
                MessageId::from($data['message_id']),
                $data['payload'],
                CarbonImmutable::createFromTimestampMs($data['occurred_on_in_ms']),
            );
        } catch (Throwable $exception) {
            throw SerializationException::from(
                sprintf('Could not denormalize Message: %s', $exception->getMessage()),
                $exception,
            );
        }
    }
}
