<?php

declare(strict_types=1);

namespace Whalar\Core\Infrastructure\Delivery\Rest\V1\Character;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Whalar\Core\Application\Command\Character\CreateCharacter\CreateCharacterCommand;
use Whalar\Shared\Domain\Data\DataMapping;
use Whalar\Shared\Infrastructure\Delivery\Rest\ApiCommandPage;

final class CreateCharacterPage extends ApiCommandPage
{
    use DataMapping;

    /** @throws \Throwable */
    public function __invoke(Request $request): JsonResponse
    {
        $data = $request->request->all();

        $characterId = self::getString($data, 'characterId');

        $this->dispatch(
            new CreateCharacterCommand(
                id: self::generateId(),
                characterId: $characterId,
                name: self::getString($data, 'name'),
                royal: self::getBool($data, 'royal'),
                kingsGuard: self::getBool($data, 'kingsguard'),
                actors: self::getArray($data, 'actors') ?? [],
                nickname: self::getNonEmptyStringOrNull($data, 'nickname'),
                imageThumb: self::getNonEmptyStringOrNull($data, 'imageThumb'),
                imageFull: self::getNonEmptyStringOrNull($data, 'imageFull'),
            ),
        );

        return new JsonResponse(['characterId' => $characterId], Response::HTTP_CREATED);
    }
}
