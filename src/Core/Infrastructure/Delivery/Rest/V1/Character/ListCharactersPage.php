<?php

declare(strict_types=1);

namespace Whalar\Core\Infrastructure\Delivery\Rest\V1\Character;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Whalar\Core\Application\Query\Character\ListCharacters\ListCharactersQuery;
use Whalar\Shared\Domain\Data\DataMapping;
use Whalar\Shared\Infrastructure\Delivery\Rest\ApiQueryPage;

final class ListCharactersPage extends ApiQueryPage
{
    use DataMapping;

    /** @throws \Throwable */
    public function __invoke(Request $request): JsonResponse
    {
        $data = $request->query->all();

        $response = $this->ask(
            new ListCharactersQuery(
                page: self::getInt($data, 'page', 1),
                size: self::getInt($data, 'size', 5),
                order: self::getString($data, 'sort', 'desc'),
            ),
        );

        return new JsonResponse($response, Response::HTTP_OK);
    }
}
