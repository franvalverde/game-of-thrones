<?php

declare(strict_types=1);

namespace Whalar\Core\Domain\CharacterRelate\Service;

use Whalar\Core\Domain\Character\Aggregate\Character;
use Whalar\Core\Domain\CharacterRelate\Aggregate\CharacterRelate;
use Whalar\Core\Domain\CharacterRelate\Repository\CharacterRelateRepository;
use Whalar\Core\Domain\CharacterRelate\ValueObject\CharacterRelation;
use Whalar\Shared\Domain\ValueObject\AggregateId;

final class CharacterLinker
{
    public function __construct(private readonly CharacterRelateRepository $relates)
    {
    }

    /** @throws \Throwable */
    public function execute(
        AggregateId $id,
        Character $character,
        Character $relatedTo,
        CharacterRelation $relation,
    ): void {
        $this->relates->save(
            CharacterRelate::create(
                id: $id,
                character: $character,
                relatedTo: $relatedTo,
                relation: $relation,
            ),
        );
    }
}
