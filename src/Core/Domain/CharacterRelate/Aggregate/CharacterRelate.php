<?php

declare(strict_types=1);

namespace Whalar\Core\Domain\CharacterRelate\Aggregate;

use Whalar\Core\Domain\Character\Aggregate\Character;
use Whalar\Core\Domain\CharacterRelate\Event\CharacterWasRelated;
use Whalar\Core\Domain\CharacterRelate\ValueObject\CharacterRelation;
use Whalar\Shared\Domain\ValueObject\AggregateId;
use Whalar\Shared\Infrastructure\Messaging\DomainEventPublisher;

class CharacterRelate
{
    private function __construct(
        private readonly AggregateId $id,
        private readonly ?Character $character,
        private readonly ?Character $relatedTo,
        private readonly CharacterRelation $relation,
    ) {
    }

    /** @throws \Throwable */
    public static function create(
        AggregateId $id,
        Character $character,
        Character $relatedTo,
        CharacterRelation $relation,
    ): self {
        $relate = new self($id, $character, $relatedTo, $relation);

        DomainEventPublisher::instance()->publish(
            CharacterWasRelated::from(
                relationId: $id->id(),
                characterId: $character->internalId()->value(),
                relatedTo: $relatedTo->internalId()->value(),
                relation: $relation->value(),
            ),
        );

        return $relate;
    }

    public function id(): AggregateId
    {
        return $this->id;
    }

    public function character(): ?Character
    {
        return $this->character;
    }

    public function relatedTo(): ?Character
    {
        return $this->relatedTo;
    }

    public function relation(): CharacterRelation
    {
        return $this->relation;
    }
}
