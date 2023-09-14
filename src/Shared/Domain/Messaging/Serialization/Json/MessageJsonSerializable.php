<?php

declare(strict_types=1);

namespace Whalar\Shared\Domain\Messaging\Serialization\Json;

use Assert\AssertionFailedException;
use Whalar\Shared\Domain\Messaging\Message;
use Whalar\Shared\Domain\Messaging\Serialization\MessageSerializable;
use Whalar\Shared\Domain\Messaging\Serialization\SerializationException;
use Whalar\Shared\Domain\Utils;
use JetBrains\PhpStorm\ArrayShape;

final class MessageJsonSerializable implements MessageSerializable
{
    public function serialize(Message $message): string
    {
        return $this->encode($this->normalize($message));
    }

    /**
     * @return array<mixed>
     *
     * @throws SerializationException
     */
    #[ArrayShape([
        'message_id' => 'string',
        'message_name' => 'string',
        'payload' => 'array|mixed',
        'occurred_on_in_ms' => 'false|int',
        'occurred_on_in_atom' => 'string',
    ])]
    private function normalize(Message $message): array
    {
        try {
            return $message->toArray();
        } catch (AssertionFailedException $exception) {
            throw SerializationException::from(
                sprintf('Could not normalize Message: %s', $exception->getMessage()),
                $exception,
            );
        }
    }

    /** @param array<mixed> $data */
    private function encode(array $data): string
    {
        return Utils::jsonEncode($data);
    }
}
