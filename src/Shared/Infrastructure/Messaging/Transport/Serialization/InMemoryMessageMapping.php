<?php

declare(strict_types=1);

namespace Whalar\Shared\Infrastructure\Messaging\Transport\Serialization;

use Whalar\Shared\Domain\Messaging\AsyncApi\AsyncApiChannel;
use Whalar\Shared\Domain\Messaging\Message;
use Whalar\Shared\Domain\Messaging\Serialization\MessageMapping;

final class InMemoryMessageMapping implements MessageMapping
{
    /** @var array<string, class-string<Message>> */
    private array $mapping;

    /** @param array<string, class-string<Message>> $mapping */
    public function __construct(array $mapping)
    {
        $this->mapping = $mapping;
    }

    /** @return class-string<Message> */
    public function for(AsyncApiChannel $channel): string
    {
        if (!isset($this->mapping[$channel->format()])) {
            throw new \RuntimeException(sprintf('No channel found for "%s"', $channel->format()));
        }

        return $this->mapping[$channel->format()];
    }
}
