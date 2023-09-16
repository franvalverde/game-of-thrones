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

        $actorId = self::getString($data, 'actorId');

        $this->dispatch(
            new CreateActorCommand(
                self::generateId(),
                self::getString($data, 'internalId'),
                self::getString($data, 'name'),
                self::getArray($data, 'seasonsActive'),
            ),
        );

        return new JsonResponse(['actorId' => $actorId], Response::HTTP_CREATED);
    }
}
