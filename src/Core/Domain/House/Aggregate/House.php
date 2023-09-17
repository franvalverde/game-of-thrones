<?php

declare(strict_types=1);

namespace Whalar\Core\Domain\House\Aggregate;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Whalar\Core\Domain\Character\Aggregate\Character;
use Whalar\Core\Domain\House\Event\HouseWasCreated;
use Whalar\Core\Infrastructure\Delivery\Rest\V1\House\CreateHousePage;
use Whalar\Shared\Domain\ValueObject\AggregateId;
use Whalar\Shared\Domain\ValueObject\Name;
use Whalar\Shared\Infrastructure\Messaging\DomainEventPublisher;

#[ApiResource(operations: [
    new Post(
        uriTemplate: '/houses',
        controller: CreateHousePage::class,
        name: 'Create a house',
    ),
])]
class House
{
    /** @var Collection<int, Character> */
    private Collection $characters;

    private function __construct(private AggregateId $id, private readonly Name $name)
    {
        $this->characters = new ArrayCollection();
    }

    /** @throws \Throwable */
    public static function create(AggregateId $id, Name $name): self
    {
        $house = new self($id, $name);

        DomainEventPublisher::instance()->publish(HouseWasCreated::from($id->id(), $name->value()));

        return $house;
    }

    public function id(): AggregateId
    {
        return $this->id;
    }

    public function name(): Name
    {
        return $this->name;
    }

    public function characters(): Collection
    {
        return $this->characters;
    }
}
