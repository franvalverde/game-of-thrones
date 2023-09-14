<?php

declare(strict_types=1);

namespace Whalar\Shared\Infrastructure\Persistence\Doctrine\Type;

use Whalar\Shared\Domain\Event\ValueObject\MessageId;

final class MessageIdType extends DoctrineEntityIdType
{
    private const FIELD_ID = 'message_id';

    public function getName(): string
    {
        return self::FIELD_ID;
    }

    public function getClassName(): string
    {
        return MessageId::class;
    }
}
