<?php

declare(strict_types=1);

namespace Whalar\Shared\Infrastructure\Delivery\Rest;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Throwable;

use function assert;

abstract class ApiQueryPage
{
    protected MessageBusInterface $queryBus;

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    /** @throws Throwable */
    protected function ask(object $message): mixed
    {
        try {
            $envelop = $this->queryBus->dispatch($message);

            $handledStamp = $envelop->last(HandledStamp::class);
            assert($handledStamp instanceof HandledStamp);

            return $handledStamp->getResult();
        } catch (HandlerFailedException $e) {
            throw $this->raiseException($e);
        }
    }

    protected function raiseException(Throwable $e): Throwable
    {
        while ($e instanceof HandlerFailedException) {
            $e = $e->getPrevious();
            assert($e instanceof Throwable);
        }

        return $e;
    }


    protected function getStatus(string $statusResponse): int
    {
        $status = Response::HTTP_OK;
        if ($statusResponse === 'HTTP_NOT_FOUND') {
            $status = Response::HTTP_NOT_FOUND;
        } else if ($statusResponse === 'HTTP_NO_CONTENT') {
            $status = Response::HTTP_NO_CONTENT;
        }
        return $status;
    }
}
