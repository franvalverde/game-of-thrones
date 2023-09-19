<?php

declare(strict_types=1);

namespace Whalar\Core\Infrastructure\Delivery\Rest\V1\House;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Whalar\Core\Application\Command\House\UpdateHouse\UpdateHouseCommand;
use Whalar\Shared\Domain\Data\DataMapping;
use Whalar\Shared\Infrastructure\Delivery\Rest\ApiCommandPage;

final class UpdateHousePage extends ApiCommandPage
{
    use DataMapping;

    /** @throws \Throwable */
    public function __invoke(Request $request): JsonResponse
    {
        $this->dispatch(
            new UpdateHouseCommand(
                houseId: self::getString($request->attributes->all(), 'id'),
                name: self::getString($request->request->all(), 'name'),
            ),
        );

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
