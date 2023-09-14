<?php

declare(strict_types=1);

namespace Whalar\Shared\Application\Event;

use Symfony\Component\Messenger\MessageBusInterface;
use Whalar\Shared\Infrastructure\Generator\UuidGenerator;

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
