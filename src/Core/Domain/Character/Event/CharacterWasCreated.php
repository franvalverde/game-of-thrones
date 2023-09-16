<?php

declare(strict_types=1);

namespace Whalar\Core\Domain\Character\Event;

use Assert\Assert;
use Carbon\CarbonImmutable;
use Whalar\Core\Domain\Character\Aggregate\Character;
use Whalar\Shared\Domain\Event\DomainEvent;
use Whalar\Shared\Domain\ValueObject\AggregateId;

final class CharacterWasCreated extends DomainEvent
{
    private const MESSAGE_VERSION = 1;

    private string $characterId;
    private string $internalId;
    private string $name;

    public static function from(string $characterId, string $internalId, string $name): DomainEvent
    {
        $id = AggregateId::random()->id();

        return self::create(
            $id,
            self::MESSAGE_VERSION,
            CarbonImmutable::now(),
            [
                'characterId' => $characterId,
                'internalId' => $internalId,
                'name' => $name,
            ],
        );
    }

    public function messageAggregateId(): string
    {
        return $this->characterId();
    }

    public function messageAggregateName(): string
    {
        return $this->aggregateNameFromClassname(Character::class);
    }

    public function characterId(): string
    {
        return $this->characterId;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function internalId(): string
    {
        return $this->internalId;
    }

    protected function setupPayload(): void
    {
        $payload = $this->messagePayload();

        Assert::that($payload)->keyExists('characterId');
        Assert::that($payload['characterId'])->string();
        $this->characterId = $payload['characterId'];

        Assert::that($payload)->keyExists('name');
        Assert::that($payload['name'])->string();
        $this->name = $payload['name'];

        Assert::that($payload)->keyExists('internalId');
        Assert::that($payload['internalId'])->string();
        $this->internalId = $payload['internalId'];
    }
}
