<?php

declare(strict_types=1);

namespace Whalar\Shared\Domain\Messaging\Serialization;

use Whalar\Shared\Domain\Messaging\AsyncApi\AsyncApiChannel;
use Whalar\Shared\Domain\Messaging\Message;

interface MessageMapping
{
    /** @return class-string<Message> */
    public function for(AsyncApiChannel $channel): string;
}
