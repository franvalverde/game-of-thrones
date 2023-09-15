<?php

declare(strict_types=1);

namespace Whalar\Tests\Shared\Core\Domain\Actor\Aggregate;

use Whalar\Core\Domain\Actor\Aggregate\Actor;
use Whalar\Core\Domain\Actor\ValueObject\ActorId;
use Whalar\Core\Domain\Actor\ValueObject\SeasonsActive;
use Whalar\Shared\Domain\ValueObject\AggregateId;
use Whalar\Shared\Domain\ValueObject\Name;
use Whalar\Shared\Infrastructure\Messaging\DomainEventPublisher;
use Whalar\Tests\Shared\Core\Domain\Actor\ValueObject\ActorIdMother;
use Whalar\Tests\Shared\Shared\Domain\ValueObject\AggregateIdMother;
use Whalar\Tests\Shared\Shared\Domain\ValueObject\NameMother;

final class ActorMother
{
    public static function create(
        ?AggregateId $id = null,
        ?ActorId $internalId = null,
        ?Name $name = null,
        ?SeasonsActive $seasonsActive = null,
    ): Actor {
        $actor = Actor::create(
            id: $id ?? AggregateIdMother::random(),
            internalId: $internalId ?? ActorIdMother::random(),
            name: $name ?? NameMother::random(),
            seasonsActive: $seasonsActive ?? null,
        );

        DomainEventPublisher::instance()->resetEvents();

        return $actor;
    }
}
