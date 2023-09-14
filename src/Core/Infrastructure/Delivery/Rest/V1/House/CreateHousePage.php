<?php

declare(strict_types=1);

namespace Whalar\Core\Infrastructure\Delivery\Rest\V1\House;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Whalar\Core\Application\Command\House\CreateHouse\CreateHouseCommand;
use Whalar\Shared\Domain\Data\DataMapping;
use Whalar\Shared\Infrastructure\Delivery\Rest\ApiCommandPage;
use Symfony\Component\HttpFoundation\Request;

final class CreateHousePage extends ApiCommandPage
{
    use DataMapping;

    /** @throws \Throwable */
    public function __invoke(Request $request): JsonResponse
    {
        $data = $request->request->all();

        $id = self::getId($data);

        $this->dispatch(
            new CreateHouseCommand(
                $id,
                self::getString($data, 'name'),
            ),
        );

        return new JsonResponse(['id' => $id], Response::HTTP_CREATED);
    }
}
