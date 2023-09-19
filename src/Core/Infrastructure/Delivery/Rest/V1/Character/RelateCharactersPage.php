<?php

declare(strict_types=1);

namespace Whalar\Core\Infrastructure\Delivery\Rest\V1\Character;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Whalar\Core\Application\Command\Character\RelateCharacters\RelateCharactersCommand;
use Whalar\Shared\Domain\Data\DataMapping;
use Whalar\Shared\Infrastructure\Delivery\Rest\ApiCommandPage;

final class RelateCharactersPage extends ApiCommandPage
{
    use DataMapping;

    /** @throws \Throwable */
    public function __invoke(Request $request): JsonResponse
    {
        $data = $request->request->all();

        $relationId = self::generateId();

        $this->dispatch(
            new RelateCharactersCommand(
                relationId: $relationId,
                characterId: self::getString($request->attributes->all(), 'id'),
                relationType: self::getString($data, 'type'),
                relatedTo: self::getString($data, 'relatedTo'),
            ),
        );

        return new JsonResponse(['relationId' => $relationId], Response::HTTP_CREATED);
    }
}
