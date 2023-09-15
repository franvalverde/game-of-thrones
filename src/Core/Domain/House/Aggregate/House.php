<?php

declare(strict_types=1);

namespace Whalar\Core\Domain\House\Aggregate;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use Whalar\Core\Domain\House\Event\HouseWasCreated;
use Whalar\Core\Infrastructure\Delivery\Rest\V1\House\CreateHousePage;
use Whalar\Shared\Domain\ValueObject\AggregateId;
use Whalar\Shared\Domain\ValueObject\Name;
use Whalar\Shared\Infrastructure\Messaging\DomainEventPublisher;

#[ApiResource(operations: [
    new Post(
        uriTemplate: '/houses',
        requirements: ['id' => '\d+'],
        controller: CreateHousePage::class,
        name: 'Create a house',
    ),
])]
class House
{
    private function __construct(private AggregateId $id, private readonly Name $name)
    {
    }

    /** @throws \Throwable */
    public static function create(AggregateId $id, Name $name): self
    {
        $house = new self($id, $name);

        DomainEventPublisher::instance()->publish(HouseWasCreated::from($id->id(), $name->name()));

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
}
