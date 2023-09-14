<?php

declare(strict_types=1);

namespace Whalar\Tests\Shared\Core\Infrastructure\Persistence\InMemory;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JetBrains\PhpStorm\Pure;
use Whalar\Core\Domain\House\Aggregate\House;
use Whalar\Core\Domain\House\Repository\HouseRepository;
use Whalar\Shared\Domain\ValueObject\AggregateId;
use Whalar\Shared\Domain\ValueObject\Name;

final class InMemoryHouseRepository implements HouseRepository
{
    /** @var Collection<string, House> */
    private Collection $houses;

    #[Pure]
    public function __construct()
    {
        $this->houses = new ArrayCollection();
    }

    public function ofId(AggregateId $id): ?House
    {
        return $this->houses->get($id->id());
    }

    public function ofName(Name $name): ?House
    {
        foreach ($this->houses as $house) {
            if ($house->name()->name() === $name->name()) {
                return $house;
            }
        }

        return null;
    }

    public function save(House $house): void
    {
        $this->houses->set($house->id()->id(), $house);
    }
}
