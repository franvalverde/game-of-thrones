<?php

declare(strict_types=1);

namespace Whalar\Shared\Infrastructure\ErrorHandler;

use Assert\Assert;
use Assert\AssertionFailedException;
use Assert\InvalidArgumentException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Whalar\Shared\Domain\DomainException;
use Whalar\Shared\Domain\Exception\Http\BadRequestException;
use Whalar\Shared\Domain\Exception\Http\ConflictException;
use Whalar\Shared\Domain\Exception\Http\NotFoundException;
use Whalar\Shared\Domain\Utils;

final class ErrorHandler
{
    private const DEFAULT_STATUS = Response::HTTP_INTERNAL_SERVER_ERROR;
    private const DEFAULT_TITLE = 'BAD_REQUEST';
    private const BASE_PROBLEM_TYPE_URI = 'https://developer.mozilla.org/es/docs/Web/HTTP/Status#';

    /** @var array<string, int> */
    private array $exceptions = [
        // domain exceptions
        BadRequestException::class => Response::HTTP_BAD_REQUEST,
        ConflictException::class => Response::HTTP_CONFLICT,
        NotFoundException::class => Response::HTTP_NOT_FOUND,

        // beberlei/assert
        Assert::class => Response::HTTP_BAD_REQUEST,
        AssertionFailedException::class => Response::HTTP_BAD_REQUEST,
        InvalidArgumentException::class => Response::HTTP_BAD_REQUEST,

        // doctrine
        UniqueConstraintViolationException::class => Response::HTTP_CONFLICT,
        OptimisticLockException::class => Response::HTTP_CONFLICT,

        // symfony
        ResourceNotFoundException::class => Response::HTTP_NOT_FOUND,
    ];

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $httpStatusCode = $this->httpStatusCodeFor($exception);

        $event->setResponse(new JsonResponse(
            [
                'status' => $httpStatusCode,
                'title' => $this->titleFor($exception),
                'detail' => $this->detailFor($exception),
                'type' => $this->typeFor($exception),
                'code' => $this->codeFor($exception),
            ],
            $httpStatusCode,
        ));
    }

    private function httpStatusCodeFor(\Throwable $exception): int
    {
        if ($exception instanceof HttpExceptionInterface) {
            return $exception->getStatusCode();
        }

        foreach ($this->exceptions as $key => $value) {
            if (!($exception instanceof $key)) {
                continue;
            }

            return $value;
        }

        return self::DEFAULT_STATUS;
    }

    private function titleFor(\Throwable $exception): string
    {
        if ($exception instanceof DomainException) {
            return $exception->title();
        }

        return self::DEFAULT_TITLE;
    }

    private function detailFor(\Throwable $exception): string
    {
        if ($exception instanceof DomainException) {
            return $exception->detail();
        }

        return $exception->getMessage();
    }

    private function typeFor(\Throwable $exception): string
    {
        return self::BASE_PROBLEM_TYPE_URI.$this->codeFor($exception);
    }

    private function codeFor(\Throwable $exception): string
    {
        if ($exception instanceof DomainException) {
            return $exception->code();
        }

        return str_replace('_exception', '', Utils::toSnakeCase(Utils::extractClassName($exception)));
    }
}
