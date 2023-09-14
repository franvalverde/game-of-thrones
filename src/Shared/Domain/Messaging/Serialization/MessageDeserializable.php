<?php

declare(strict_types=1);

namespace Whalar\Shared\Domain\Messaging\Serialization;

use Whalar\Shared\Domain\Messaging\Message;

interface MessageDeserializable
{
    /** @throws SerializationException */
    public function deserialize(string $data): Message;
}
