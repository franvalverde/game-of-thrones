<?php

declare(strict_types=1);

namespace Whalar\Shared\Application\Event;

use Whalar\Shared\Infrastructure\Generator\UuidGenerator;
use Symfony\Component\Messenger\MessageBusInterface;

abstract class MessageEventHandler
{
    protected MessageBusInterface $commandBus;
    protected UuidGenerator $uuidGenerator;

    public function __construct(MessageBusInterface $commandBus, UuidGenerator $uuidGenerator)
    {
        $this->commandBus = $commandBus;
        $this->uuidGenerator = $uuidGenerator;
    }
}
