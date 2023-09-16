<?php

declare(strict_types=1);

namespace Whalar\Tests\Shared\Core\Domain\Character\Aggregate;

use Whalar\Core\Domain\Actor\ValueObject\ActorsCollection;
use Whalar\Core\Domain\Character\Aggregate\Character;
use Whalar\Core\Domain\Character\ValueObject\CharacterId;
use Whalar\Core\Domain\Character\ValueObject\CharacterKingsGuard;
use Whalar\Core\Domain\Character\ValueObject\CharacterRoyal;
use Whalar\Shared\Domain\ValueObject\AggregateId;
use Whalar\Shared\Domain\ValueObject\ImageUrl;
use Whalar\Shared\Domain\ValueObject\Name;
use Whalar\Shared\Infrastructure\Messaging\DomainEventPublisher;
use Whalar\Tests\Shared\Core\Domain\Actor\Aggregate\ActorMother;
use Whalar\Tests\Shared\Core\Domain\Character\ValueObject\CharacterIdMother;
use Whalar\Tests\Shared\Core\Domain\Character\ValueObject\CharacterKingsGuardMother;
use Whalar\Tests\Shared\Core\Domain\Character\ValueObject\CharacterRoyalMother;
use Whalar\Tests\Shared\Shared\Domain\ValueObject\AggregateIdMother;
use Whalar\Tests\Shared\Shared\Domain\ValueObject\ImageUrlMother;
use Whalar\Tests\Shared\Shared\Domain\ValueObject\NameMother;

final class CharacterMother
{
    public static function create(
        ?AggregateId $id = null,
        ?CharacterId $internalId = null,
        ?Name $name = null,
        ?CharacterRoyal $royal = null,
        ?CharacterKingsGuard $kingsGuard = null,
        ?ActorsCollection $actors = null,
        ?Name $nickname = null,
        ?ImageUrl $imageThumb = null,
        ?ImageUrl $imageFull = null,
    ): Character {
        $character = Character::create(
            id: $id ?? AggregateIdMother::random(),
            internalId: $internalId ?? CharacterIdMother::random(),
            name: $name ?? NameMother::random(),
            royal: $royal ?? CharacterRoyalMother::random(),
            kingsGuard: $kingsGuard ?? CharacterKingsGuardMother::random(),
            actors: $actors ?? ActorsCollection::from([ActorMother::create()]),
            nickname: $nickname ?? NameMother::random(),
            imageThumb: $imageThumb ?? ImageUrlMother::random(),
            imageFull: $imageFull ?? ImageUrlMother::random(),
        );

        DomainEventPublisher::instance()->resetEvents();

        return $character;
    }
}
