<?php

declare(strict_types=1);

namespace Whalar\Core\Domain\Actor\Event;

use Assert\Assert;
use Carbon\CarbonImmutable;
use Whalar\Core\Domain\Actor\Aggregate\Actor;
use Whalar\Shared\Domain\Event\DomainEvent;
use Whalar\Shared\Domain\ValueObject\AggregateId;

final class ActorWasCreated extends DomainEvent
{
    private const MESSAGE_VERSION = 1;

    private string $actorId;
    private string $name;

    public static function from(string $actorId, string $internalId, string $name): DomainEvent
    {
        $id = AggregateId::random()->id();

        return self::create(
            $id,
            self::MESSAGE_VERSION,
            CarbonImmutable::now(),
            [
                'actorId' => $actorId,
                'name' => $name,
            ],
        );
    }

    public function messageAggregateId(): string
    {
        return $this->actorId();
    }

    public function messageAggregateName(): string
    {
        return $this->aggregateNameFromClassname(Actor::class);
    }

    public function actorId(): string
    {
        return $this->actorId;
    }

    public function name(): string
    {
        return $this->name;
    }

    protected function setupPayload(): void
    {
        $payload = $this->messagePayload();

        Assert::that($payload)->keyExists('actorId');
        Assert::that($payload['actorId'])->string();
        $this->actorId = $payload['actorId'];

        Assert::that($payload)->keyExists('name');
        Assert::that($payload['name'])->string();
        $this->name = $payload['name'];
    }
}
