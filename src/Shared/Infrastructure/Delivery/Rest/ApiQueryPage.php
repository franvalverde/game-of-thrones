<?php

declare(strict_types=1);

namespace Whalar\Shared\Infrastructure\Delivery\Rest;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

abstract class ApiQueryPage
{
    protected MessageBusInterface $queryBus;

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    /** @throws \Throwable */
    protected function ask(object $message): mixed
    {
        try {
            $envelop = $this->queryBus->dispatch($message);

            $handledStamp = $envelop->last(HandledStamp::class);
            \assert($handledStamp instanceof HandledStamp);

            return $handledStamp->getResult();
        } catch (HandlerFailedException $e) {
            throw $this->raiseException($e);
        }
    }

    protected function raiseException(\Throwable $e): \Throwable
    {
        while ($e instanceof HandlerFailedException) {
            $e = $e->getPrevious();
            \assert($e instanceof \Throwable);
        }

        return $e;
    }

    protected function getStatus(string $statusResponse): int
    {
        $status = Response::HTTP_OK;

        if ('HTTP_NOT_FOUND' === $statusResponse) {
            $status = Response::HTTP_NOT_FOUND;
        } elseif ('HTTP_NO_CONTENT' === $statusResponse) {
            $status = Response::HTTP_NO_CONTENT;
        }

        return $status;
    }
}
