<?php

declare(strict_types=1);

namespace Whalar\Tests\Shared\Core\Infrastructure\Persistence\InMemory;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JetBrains\PhpStorm\Pure;
use Whalar\Core\Domain\Character\Aggregate\Character;
use Whalar\Core\Domain\Character\Repository\CharacterRepository;
use Whalar\Core\Domain\Character\ValueObject\CharacterId;
use Whalar\Shared\Domain\ValueObject\FilterCollection;
use Whalar\Shared\Domain\ValueObject\Name;
use Whalar\Shared\Domain\ValueObject\PaginatorOrder;
use Whalar\Shared\Domain\ValueObject\PaginatorPage;
use Whalar\Shared\Domain\ValueObject\PaginatorSize;

final class InMemoryCharacterRepository implements CharacterRepository
{
    /** @var Collection<string, Character> */
    private Collection $characters;

    #[Pure]
    public function __construct()
    {
        $this->characters = new ArrayCollection();
    }

    public function ofName(Name $name): ?Character
    {
        foreach ($this->characters as $character) {
            if ($character->name()->value() === $name->value()) {
                return $character;
            }
        }

        return null;
    }

    public function save(Character $character): void
    {
        $this->characters->set($character->id()->id(), $character);
    }

    public function ofInternalId(CharacterId $id): ?Character
    {
        foreach ($this->characters as $character) {
            if ($character->internalId()->equalsTo($id)) {
                return $character;
            }
        }

        return null;
    }

    public function paginate(
        PaginatorPage $page,
        PaginatorSize $size,
        PaginatorOrder $order,
        ?FilterCollection $filter,
    ): array {
        $result = [];
        $total = 0;

        foreach ($this->characters->getValues() as $character) {
            if (null !== $filter && !str_starts_with($character->name()->value(), $filter->value)) {
                continue;
            }

            ++$total;
            $result[] = $character;
        }

        return [
            'total' => $total,
            'characters' => $result,
        ];
    }
}
