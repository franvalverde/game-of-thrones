<?php

declare(strict_types=1);

namespace Whalar\Shared\Application\Event;

use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

interface EventHandler extends MessageSubscriberInterface
{
}
