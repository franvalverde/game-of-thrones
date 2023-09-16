<?php

declare(strict_types=1);

namespace Whalar\Tests\Shared\Core\Application\Command\Character\CreateCharacter;

use Assert\AssertionFailedException;
use Whalar\Core\Application\Command\Character\CreateCharacter\CreateCharacterCommand;
use Whalar\Tests\Shared\Core\Domain\Actor\ValueObject\ActorIdMother;
use Whalar\Tests\Shared\Core\Domain\Character\ValueObject\CharacterIdMother;
use Whalar\Tests\Shared\Core\Domain\Character\ValueObject\CharacterKingsGuardMother;
use Whalar\Tests\Shared\Core\Domain\Character\ValueObject\CharacterRoyalMother;
use Whalar\Tests\Shared\Shared\Domain\ValueObject\AggregateIdMother;
use Whalar\Tests\Shared\Shared\Domain\ValueObject\NameMother;

final class CreateCharacterCommandMother
{
    /** @throws AssertionFailedException */
    public static function create(
        ?string $id = null,
        ?string $internalId = null,
        ?string $name = null,
        ?bool $royal = null,
        ?bool $kingGuard = null,
        ?array $actors = null,
        ?string $nickname = null,
        ?string $imageThumb = null,
        ?string $imageFull = null,
    ): CreateCharacterCommand {
        return new CreateCharacterCommand(
            id: $id ?? AggregateIdMother::random()->id(),
            characterId: $internalId ?? CharacterIdMother::create(),
            name: $name ?? NameMother::random()->value(),
            royal: $royal ?? CharacterRoyalMother::random()->value(),
            kingsGuard: $kingGuard ?? CharacterKingsGuardMother::random()->value(),
            actors: $actors ?? [ActorIdMother::random()->value()],
            nickname: $nickname,
            imageThumb: $imageThumb,
            imageFull: $imageFull,
        );
    }
}
