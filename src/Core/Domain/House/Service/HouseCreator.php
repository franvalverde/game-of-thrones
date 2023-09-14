<?php

declare(strict_types=1);

namespace Whalar\Core\Domain\House\Service;

use Whalar\Core\Domain\House\Aggregate\House;
use Whalar\Core\Domain\House\Exception\HouseAlreadyExistsException;
use Whalar\Core\Domain\House\Repository\HouseRepository;
use Whalar\Shared\Domain\ValueObject\AggregateId;
use Whalar\Shared\Domain\ValueObject\Name;

final class HouseCreator
{
    public function __construct(private readonly HouseRepository $houses)
    {}

    /** @throws HouseAlreadyExistsException */
    public function execute(AggregateId $houseId, Name $name): void
    {
        $this->ensureHouseNotExists($houseId, $name);

        $this->houses->save(House::create($houseId, $name));
    }

    /** @throws HouseAlreadyExistsException */
    private function ensureHouseNotExists(AggregateId $houseId, Name $name): void
    {
        if (null !== $this->houses->ofId($houseId) || null !== $this->houses->ofName($name)) {
            throw HouseAlreadyExistsException::from($name->name());
        }
    }
}
