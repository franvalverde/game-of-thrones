<?php

declare(strict_types=1);

namespace Whalar\Tests\Shared\Core\Application\Command\Character\RelateCharacters;

use Assert\AssertionFailedException;
use Whalar\Core\Application\Command\Character\RelateCharacters\RelateCharactersCommand;
use Whalar\Tests\Shared\Core\Domain\Character\ValueObject\CharacterIdMother;
use Whalar\Tests\Shared\Core\Domain\CharacterRelate\ValueObject\CharacterRelationMother;
use Whalar\Tests\Shared\Shared\Domain\ValueObject\AggregateIdMother;

final class RelateCharactersCommandMother
{
    /** @throws AssertionFailedException */
    public static function create(
        ?string $characterId = null,
        ?string $relatedTo = null,
        ?string $relation = null,
    ): RelateCharactersCommand {
        return new RelateCharactersCommand(
            relationId: AggregateIdMother::random()->id(),
            characterId: $characterId ?? CharacterIdMother::create(),
            relationType: $relation ?? CharacterRelationMother::create(),
            relatedTo: $relatedTo ?? CharacterIdMother::create(),
        );
    }
}
