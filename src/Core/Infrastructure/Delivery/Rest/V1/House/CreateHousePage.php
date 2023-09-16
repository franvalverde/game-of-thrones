<?php

declare(strict_types=1);

namespace Whalar\Core\Infrastructure\Delivery\Rest\V1\House;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Whalar\Core\Application\Command\House\CreateHouse\CreateHouseCommand;
use Whalar\Shared\Domain\Data\DataMapping;
use Whalar\Shared\Infrastructure\Delivery\Rest\ApiCommandPage;

final class CreateHousePage extends ApiCommandPage
{
    use DataMapping;

    /** @throws \Throwable */
    public function __invoke(Request $request): JsonResponse
    {
        $id = self::generateId();

        $this->dispatch(
            new CreateHouseCommand(
                $id,
                self::getString($request->request->all(), 'name'),
            ),
        );

        return new JsonResponse(['houseId' => $id], Response::HTTP_CREATED);
    }
}
