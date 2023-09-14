<?php

declare(strict_types=1);

namespace Whalar\Tests\Shared\Shared\Domain\Messaging;

use Assert\AssertionFailedException;
use Whalar\Shared\Domain\Messaging\MessageId;
use Whalar\Tests\Shared\Shared\Domain\ValueObject\AggregateIdMother;

final class MessageIdMother
{
    /** @throws AssertionFailedException */
    public static function create(?string $value = null): MessageId
    {
        return MessageId::from($value ?? AggregateIdMother::create());
    }
}
