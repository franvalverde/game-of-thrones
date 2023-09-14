<?php

declare(strict_types=1);

namespace Whalar\Shared\Infrastructure\Delivery\Rest;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;

abstract class ApiCommandPage
{
    protected MessageBusInterface $commandBus;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @param object $message (Envelope)
     *
     * @throws \Throwable
     */
    protected function dispatch(object $message): Envelope
    {
        try {
            return $this->commandBus->dispatch($message);
        } catch (HandlerFailedException $e) {
            while ($e instanceof HandlerFailedException) {
                $e = $e->getPrevious();
                \assert($e instanceof \Throwable);
            }

            throw $e;
        }
    }
}
