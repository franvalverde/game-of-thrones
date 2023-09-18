<?php

declare(strict_types=1);

namespace Whalar\Core\Application\Query\Character\RetrieveCharacter;

use Whalar\Core\Domain\Character\Service\CharacterFinder;
use Whalar\Core\Domain\Character\ValueObject\CharacterId;
use Whalar\Shared\Application\Query\QueryHandler;

final class RetrieveCharacterQueryHandler implements QueryHandler
{
    public function __construct(private readonly CharacterFinder $finder)
    {
    }

    /** @throws \Throwable */
    public function __invoke(RetrieveCharacterQuery $query): RetrieveCharacterResponse
    {
        return RetrieveCharacterResponse::write(
            $this->finder->ofCharacterIdOrFail(CharacterId::from($query->characterId)),
        );
    }
}
