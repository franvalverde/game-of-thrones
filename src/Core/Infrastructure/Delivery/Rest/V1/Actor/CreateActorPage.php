<?php

declare(strict_types=1);

namespace Whalar\Core\Infrastructure\Delivery\Rest\V1\Actor;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Whalar\Core\Application\Command\Actor\CreateActor\CreateActorCommand;
use Whalar\Shared\Domain\Data\DataMapping;
use Whalar\Shared\Infrastructure\Delivery\Rest\ApiCommandPage;

final class CreateActorPage extends ApiCommandPage
{
    use DataMapping;

    /** @throws \Throwable */
    public function __invoke(Request $request): JsonResponse
    {
        $data = $request->request->all();

        $id = self::getId($data);

        $this->dispatch(
            new CreateActorCommand(
                $id,
                self::getString($data, 'internalId'),
                self::getString($data, 'name'),
                self::getArray($data, 'seasonsActive'),
            ),
        );

        return new JsonResponse(['id' => $id], Response::HTTP_CREATED);
    }
}
