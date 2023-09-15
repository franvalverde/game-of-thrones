<?php

namespace Whalar\Tests\Shared\Core\Domain\House;

use Whalar\Core\Domain\House\Aggregate\House;
use Whalar\Shared\Domain\ValueObject\AggregateId;
use Whalar\Shared\Domain\ValueObject\Name;
use Whalar\Shared\Infrastructure\Messaging\DomainEventPublisher;
use Whalar\Tests\Shared\Shared\Domain\ValueObject\AggregateIdMother;
use Whalar\Tests\Shared\Shared\Domain\ValueObject\NameMother;

final class HouseMother
{
    public static function create(
        ?AggregateId $id = null,
        ?Name $name = null
    ): House
    {
        $house = House::create(
            id: $id ?? AggregateIdMother::create(),
            name: $name ?? NameMother::random(),
        );

        DomainEventPublisher::instance()->resetEvents();

        return $house;
    }
}
