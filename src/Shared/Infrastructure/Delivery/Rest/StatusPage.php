<?php

declare(strict_types=1);

namespace Whalar\Shared\Infrastructure\Delivery\Rest;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class StatusPage
{
    public function __invoke(): JsonResponse
    {
        return new JsonResponse(['STATUS' => 'OK'], Response::HTTP_OK);
    }
}
