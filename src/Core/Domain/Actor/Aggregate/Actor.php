<?php

declare(strict_types=1);

namespace Whalar\Core\Domain\Actor\Aggregate;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use Whalar\Core\Domain\Actor\Event\ActorWasCreated;
use Whalar\Core\Domain\Actor\ValueObject\ActorId;
use Whalar\Core\Domain\Actor\ValueObject\SeasonsActive;
use Whalar\Core\Infrastructure\Delivery\Rest\V1\Actor\CreateActorPage;
use Whalar\Shared\Domain\ValueObject\Link;
use Whalar\Shared\Domain\ValueObject\Name;
use Whalar\Shared\Infrastructure\Messaging\DomainEventPublisher;

#[ApiResource(operations: [
    new Post(
        uriTemplate: '/actors',
        controller: CreateActorPage::class,
        name: 'Create an actor',
    ),
])]
class Actor
{
    private function __construct(
        private ActorId $id,
        private readonly Name $name,
        private readonly Link $link,
        private readonly ?SeasonsActive $seasonsActive,
    )
    {
    }

    /** @throws \Throwable */
    public static function create(ActorId $id, Name $name, Link $link, ?SeasonsActive  $seasonsActive): self
    {
        $actor = new self($id, $name, $link, $seasonsActive);

        DomainEventPublisher::instance()->publish(
            ActorWasCreated::from(
                actorId: $id->id(),
                name: $name->value(),
            ),
        );

        return $actor;
    }

    public function id(): ActorId
    {
        return $this->id;
    }

    public function name(): Name
    {
        return $this->name;
    }

    public function link(): Link
    {
        return $this->link;
    }

    public function seasonsActive(): ?SeasonsActive
    {
        return $this->seasonsActive;
    }
}
