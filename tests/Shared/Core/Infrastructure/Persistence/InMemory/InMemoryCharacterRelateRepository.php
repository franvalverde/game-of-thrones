<?php

declare(strict_types=1);

namespace Whalar\Tests\Shared\Core\Infrastructure\Persistence\InMemory;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JetBrains\PhpStorm\Pure;
use Whalar\Core\Domain\Character\ValueObject\CharacterId;
use Whalar\Core\Domain\CharacterRelate\Aggregate\CharacterRelate;
use Whalar\Core\Domain\CharacterRelate\Repository\CharacterRelateRepository;

final class InMemoryCharacterRelateRepository implements CharacterRelateRepository
{
    /** @var Collection<string, CharacterRelate> */
    private Collection $relates;

    #[Pure]
    public function __construct()
    {
        $this->relates = new ArrayCollection();
    }

    public function save(CharacterRelate $characterRelate): void
    {
        $this->relates->set($characterRelate->id()->id(), $characterRelate);
    }

    public function ofPrincipalId(CharacterId $characterId): ?CharacterRelate
    {
        foreach ($this->relates as $relate) {
            if ($relate->character()->internalId()->equalsTo($characterId)) {
                return $relate;
            }
        }

        return null;
    }
}
