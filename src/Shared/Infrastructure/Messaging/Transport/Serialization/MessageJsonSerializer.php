<?php

declare(strict_types=1);

namespace Whalar\Shared\Infrastructure\Messaging\Transport\Serialization;

use Whalar\Shared\Domain\Messaging\Message;
use Whalar\Shared\Domain\Messaging\Serialization\Json\MessageJsonDeserializable;
use Whalar\Shared\Domain\Messaging\Serialization\Json\MessageJsonSerializable;
use Whalar\Shared\Domain\Messaging\Serialization\SerializationException;
use JetBrains\PhpStorm\ArrayShape;
use LogicException;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\MessageDecodingFailedException;
use Symfony\Component\Messenger\Stamp\BusNameStamp;
use Symfony\Component\Messenger\Stamp\DispatchAfterCurrentBusStamp;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

use function array_key_exists;

final class MessageJsonSerializer implements SerializerInterface
{
    private MessageJsonSerializable $serializer;
    private MessageJsonDeserializable $deserializer;

    public function __construct(MessageJsonSerializable $serializer, MessageJsonDeserializable $deserializer)
    {
        $this->serializer = $serializer;
        $this->deserializer = $deserializer;
    }

    /** @param array<mixed> $encodedEnvelope */
    public function decode(array $encodedEnvelope): Envelope
    {
        if (!array_key_exists('body', $encodedEnvelope)) {
            throw new MessageDecodingFailedException('Encoded envelope should have at least a "body"');
        }

        try {
            $message = $this->deserializer->deserialize($encodedEnvelope['body']);
        } catch (SerializationException $exception) {
            throw new MessageDecodingFailedException($exception->getMessage(), $exception->getCode(), $exception);
        }

        return (new Envelope($message))
            ->with(new BusNameStamp(sprintf('%s.bus', $message::messageType())))
            ->with(new DispatchAfterCurrentBusStamp());
    }

    /** @return array<string> */
    #[ArrayShape([
        'body' => 'string',
    ])]
    public function encode(Envelope $envelope): array
    {
        $message = $envelope->getMessage();

        if (!($message instanceof Message)) {
            throw new LogicException(
                sprintf(
                    '%s could not encode object of type %s',
                    class_basename(self::class),
                    $message::class,
                ),
            );
        }

        try {
            $encodedMessage = $this->serializer->serialize($message);
        } catch (SerializationException $exception) {
            throw new LogicException(
                sprintf(
                    '%s could not encode object of type %s: %s',
                    class_basename(self::class),
                    class_basename(Message::class),
                    $exception->getMessage(),
                ),
            );
        }

        return [
            'body' => $encodedMessage,
        ];
    }
}
