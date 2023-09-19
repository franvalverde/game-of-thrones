<?php

declare(strict_types=1);

namespace Whalar\Core\Domain\CharacterRelate\Event;

use Assert\Assert;
use Carbon\CarbonImmutable;
use Whalar\Core\Domain\CharacterRelate\Aggregate\CharacterRelate;
use Whalar\Shared\Domain\Event\DomainEvent;
use Whalar\Shared\Domain\ValueObject\AggregateId;

final class CharacterWasRelated extends DomainEvent
{
    private const MESSAGE_VERSION = 1;

    private string $relationId;
    private string $characterId;
    private string $relatedTo;
    private string $relation;

    public static function from(
        string $relationId,
        string $characterId,
        string $relatedTo,
        string $relation,
    ): DomainEvent {
        $id = AggregateId::random()->id();

        return self::create(
            $id,
            self::MESSAGE_VERSION,
            CarbonImmutable::now(),
            [
                'relationId' => $relationId,
                'characterId' => $characterId,
                'relatedTo' => $relatedTo,
                'relation' => $relation,
            ],
        );
    }

    public function messageAggregateId(): string
    {
        return $this->relationId();
    }

    public function messageAggregateName(): string
    {
        return $this->aggregateNameFromClassname(CharacterRelate::class);
    }

    public function relationId(): string
    {
        return $this->relationId;
    }

    public function relatedTo(): string
    {
        return $this->relatedTo;
    }

    public function relation(): string
    {
        return $this->relation;
    }

    public function characterId(): string
    {
        return $this->characterId;
    }

    protected function setupPayload(): void
    {
        $payload = $this->messagePayload();

        Assert::that($payload)->keyExists('relationId');
        Assert::that($payload['relationId'])->string();
        $this->relationId = $payload['relationId'];

        Assert::that($payload)->keyExists('characterId');
        Assert::that($payload['characterId'])->string();
        $this->characterId = $payload['characterId'];

        Assert::that($payload)->keyExists('relatedTo');
        Assert::that($payload['relatedTo'])->string();
        $this->relatedTo = $payload['relatedTo'];

        Assert::that($payload)->keyExists('relation');
        Assert::that($payload['relation'])->string();
        $this->relation = $payload['relation'];
    }
}
