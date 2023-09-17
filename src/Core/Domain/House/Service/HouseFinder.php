<?php

declare(strict_types=1);

namespace Whalar\Core\Domain\House\Service;

use Whalar\Core\Domain\House\Aggregate\House;
use Whalar\Core\Domain\House\Exception\HouseNotFoundException;
use Whalar\Core\Domain\House\Repository\HouseRepository;
use Whalar\Shared\Domain\ValueObject\AggregateId;

final class HouseFinder
{
    public function __construct(private readonly HouseRepository $houses)
    {
    }

    /** @throws HouseNotFoundException|\Throwable */
    public function ofIdOrFail(AggregateId $id): House
    {
        $house = $this->houses->ofId($id);

        if (null === $house) {
            throw HouseNotFoundException::from($id->id());
        }

        return $house;
    }
}
