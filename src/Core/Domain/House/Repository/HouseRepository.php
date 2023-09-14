<?php

namespace Whalar\Core\Domain\House\Repository;

use Whalar\Core\Domain\House\Aggregate\House;
use Whalar\Shared\Domain\ValueObject\AggregateId;
use Whalar\Shared\Domain\ValueObject\Name;

interface HouseRepository
{
    public function ofId(AggregateId $id): ?House;

    public function ofName(Name $name): ?House;

    public function save(House $house): void;
}