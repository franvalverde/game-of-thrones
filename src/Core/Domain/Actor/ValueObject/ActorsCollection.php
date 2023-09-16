<?php

declare(strict_types=1);

namespace Whalar\Core\Domain\Actor\ValueObject;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Whalar\Core\Domain\Actor\Aggregate\Actor;

use function Lambdish\Phunctional\reduce;

final class ActorsCollection
{
    /** @var Collection<int, Actor> */
    private Collection $collection;

    /** @param array<int, Actor> $elements */
    private function __construct(array $elements)
    {
        $this->setCollection($elements);
    }

    /** @param array<int, Actor> $elements */
    public static function from(array $elements): self
    {
        return new self($elements);
    }

    /** @return Collection<int, Actor> */
    public function collection(): Collection
    {
        return $this->collection;
    }

    /** @param array<int, Actor> $elements */
    private function setCollection(array $elements): void
    {
        $this->collection = reduce(
            $this->uniqueElementsExtractor(),
            $elements,
            new ArrayCollection(),
        );
    }

    private function uniqueElementsExtractor(): callable
    {
        return function (Collection $collection, Actor $newValue): Collection {
            $exists = $collection->exists($this->elementPredicate($newValue));

            if (!$exists) {
                $collection->add($newValue);
            }

            return $collection;
        };
    }

    private function elementPredicate(Actor $newValue): \Closure
    {
        return static fn ($key, Actor $value) => $value->id()->equalsTo($newValue->id());
    }
}
