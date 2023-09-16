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
                self::generateId(),
                $characterId,
                self::getString($data, 'name'),
                self::getBool($data, 'royal'),
                self::getBool($data, 'kingsguard'),
                self::getNonEmptyStringOrNull($data, 'nickname'),
                self::getNonEmptyStringOrNull($data, 'imageThumb'),
                self::getNonEmptyStringOrNull($data, 'imageFull'),
            ),
        );

        return new JsonResponse(['characterId' => $characterId], Response::HTTP_CREATED);
    }
}
