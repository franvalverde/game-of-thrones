<?php

declare(strict_types=1);

namespace Whalar\Core\Infrastructure\Delivery\Rest\V1\Character;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Whalar\Core\Application\Query\Character\RetrieveCharacter\RetrieveCharacterQuery;
use Whalar\Shared\Domain\Data\DataMapping;
use Whalar\Shared\Infrastructure\Delivery\Rest\ApiQueryPage;

final class RetrieveCharacterPage extends ApiQueryPage
{
    use DataMapping;

    /** @throws \Throwable */
    public function __invoke(Request $request): JsonResponse
    {
        $response = $this->ask(
            new RetrieveCharacterQuery(
                characterId: self::getString($request->attributes->all(), 'id'),
            ),
        );

        return new JsonResponse($response, Response::HTTP_OK);
    }
}
