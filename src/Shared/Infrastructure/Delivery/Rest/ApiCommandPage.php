<?php

declare(strict_types=1);

namespace Whalar\Shared\Infrastructure\Delivery\Rest;

use Whalar\Shared\Domain\Exception\InvalidContentTypeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

use function assert;

abstract class ApiCommandPage
{
    private const JSON_TYPE = 'application/json';

    protected MessageBusInterface $commandBus;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @param object $message (Envelope)
     *
     * @throws Throwable
     */
    protected function dispatch(object $message): Envelope
    {
        try {
            return $this->commandBus->dispatch($message);
        } catch (HandlerFailedException $e) {
            while ($e instanceof HandlerFailedException) {
                $e = $e->getPrevious();
                assert($e instanceof Throwable);
            }

            throw $e;
        }
    }

    protected function checkApplicationJsonContentType(Request $request): void
    {
        if (self::JSON_TYPE !== $_SERVER["CONTENT_TYPE"]) {
            throw InvalidContentTypeException::from(self::JSON_TYPE);
        }
    }
}
