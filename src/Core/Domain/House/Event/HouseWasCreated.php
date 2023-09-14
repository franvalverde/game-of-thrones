<?php

declare(strict_types=1);

namespace Whalar\Core\Domain\House\Event;

use Assert\Assert;
use Carbon\CarbonImmutable;
use Whalar\Core\Domain\House\Aggregate\House;
use Whalar\Shared\Domain\Event\DomainEvent;
use Whalar\Shared\Domain\ValueObject\AggregateId;

final class HouseWasCreated extends DomainEvent
{
    private const MESSAGE_VERSION = 1;

    private string $houseId;
    private string $name;

    public static function from(string $houseId, string $name): DomainEvent
    {
        $id = AggregateId::random()->id();

        return self::create(
            $id,
            self::MESSAGE_VERSION,
            CarbonImmutable::now(),
            [
                'houseId' => $houseId,
                'name' => $name,
            ],
        );
    }

    public function messageAggregateId(): string
    {
        return $this->houseId();
    }

    public function messageAggregateName(): string
    {
        return $this->aggregateNameFromClassname(House::class);
    }

    public function houseId(): string
    {
        return $this->houseId;
    }


    public function name(): string
    {
        return $this->name;
    }

    protected function setupPayload(): void
    {
        $payload = $this->messagePayload();

        Assert::that($payload)->keyExists('houseId');
        Assert::that($payload['houseId'])->string();
        $this->houseId = $payload['houseId'];

        Assert::that($payload)->keyExists('name');
        Assert::that($payload['name'])->string();
        $this->name = $payload['name'];
    }
}
