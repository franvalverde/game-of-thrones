<?php

declare(strict_types=1);

namespace Whalar\Core\Domain\House\Service;

use Whalar\Core\Domain\House\Exception\HouseAlreadyExistsException;
use Whalar\Core\Domain\House\Repository\HouseRepository;
use Whalar\Shared\Domain\ValueObject\AggregateId;
use Whalar\Shared\Domain\ValueObject\Name;

final class HouseUpdater
{
    private HouseFinder $finder;

    public function __construct(private readonly HouseRepository $houses)
    {
        $this->finder = new HouseFinder($this->houses);
    }

    /** @throws HouseAlreadyExistsException|\Throwable */
    public function execute(AggregateId $houseId, Name $name): void
    {
        $house = $this->finder->ofIdOrFail($houseId);

        $house->update($name);

        $this->houses->save($house);
    }
}
