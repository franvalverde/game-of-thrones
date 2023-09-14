<?php

declare(strict_types=1);

namespace Whalar\Shared\Domain\Messaging\Serialization;

use Whalar\Shared\Domain\Messaging\Message;

interface MessageSerializable
{
    /** @throws SerializationException */
    public function serialize(Message $message): string;
}
