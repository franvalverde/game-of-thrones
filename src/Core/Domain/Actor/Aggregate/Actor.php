<?php

declare(strict_types=1);

namespace Whalar\Core\Domain\Actor\Aggregate;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use Whalar\Core\Domain\Actor\Event\ActorWasCreated;
use Whalar\Core\Domain\Actor\ValueObject\ActorId;
use Whalar\Core\Domain\Actor\ValueObject\SeasonsActive;
use Whalar\Core\Infrastructure\Delivery\Rest\V1\Actor\CreateActorPage;
use Whalar\Shared\Domain\ValueObject\AggregateId;
use Whalar\Shared\Domain\ValueObject\Name;
use Whalar\Shared\Infrastructure\Messaging\DomainEventPublisher;

#[ApiResource(operations: [
    new Post(
        uriTemplate: '/actors',
        controller: CreateActorPage::class,
        name: 'Create an actor',
    ),
])]
class Actor implements \JsonSerializable
{
    private function __construct(
        private AggregateId $id,
        private readonly ActorId $internalId,
        private readonly Name $name,
        private readonly ?SeasonsActive $seasonsActive,
    ) {
    }

    /** @throws \Throwable */
    public static function create(AggregateId $id, ActorId $internalId, Name $name, ?SeasonsActive $seasonsActive): self
    {
        $actor = new self($id, $internalId, $name, $seasonsActive);

        DomainEventPublisher::instance()->publish(
            ActorWasCreated::from(
                actorId: $id->id(),
                internalId: $internalId->value(),
                name: $name->value(),
            ),
        );

        return $actor;
    }

    public function id(): AggregateId
    {
        return $this->id;
    }

    public function internalId(): ActorId
    {
        return $this->internalId;
    }

    public function name(): Name
    {
        return $this->name;
    }

    public function seasonsActive(): ?SeasonsActive
    {
        return $this->seasonsActive;
    }

    public function jsonSerialize(): array
    {
        $result = [
            'actorName' => $this->name()->value(),
            'actorLink' => sprintf('/name/%s/', $this->internalId()),
        ];

        if (null !== $this->seasonsActive()) {
            $result['seasonsActive'] = $this->seasonsActive()->toArray();
        }

        return $result;
    }
}
