<?php

declare(strict_types=1);

namespace Whalar\Core\Domain\Character\Aggregate;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Whalar\Core\Domain\Actor\Aggregate\Actor;
use Whalar\Core\Domain\Actor\ValueObject\ActorsCollection;
use Whalar\Core\Domain\Character\Event\CharacterWasCreated;
use Whalar\Core\Domain\Character\ValueObject\CharacterId;
use Whalar\Core\Domain\Character\ValueObject\CharacterKingsGuard;
use Whalar\Core\Domain\Character\ValueObject\CharacterRoyal;
use Whalar\Core\Domain\House\Aggregate\House;
use Whalar\Shared\Domain\ValueObject\AggregateId;
use Whalar\Shared\Domain\ValueObject\ImageUrl;
use Whalar\Shared\Domain\ValueObject\Name;
use Whalar\Shared\Infrastructure\Messaging\DomainEventPublisher;

#[ApiResource]
class Character implements \JsonSerializable
{
    /** @var Collection<int, Actor> */
    private Collection $actors;

    private function __construct(
        private AggregateId $id,
        private readonly CharacterId $internalId,
        private readonly Name $name,
        private readonly CharacterRoyal $royal,
        private readonly CharacterKingsGuard $kingsGuard,
        private readonly ?House $house,
        private readonly ?Name $nickname,
        private readonly ?ImageUrl $imageThumb,
        private readonly ?ImageUrl $imageFull,
    ) {
        $this->actors = new ArrayCollection();
    }

    /** @throws \Throwable */
    public static function create(
        AggregateId $id,
        CharacterId $internalId,
        Name $name,
        CharacterRoyal $royal,
        CharacterKingsGuard $kingsGuard,
        ActorsCollection $actors,
        ?House $house,
        ?Name $nickname,
        ?ImageUrl $imageThumb,
        ?ImageUrl $imageFull,
    ): self {
        $character = new self(
            id: $id,
            internalId: $internalId,
            name: $name,
            royal: $royal,
            kingsGuard: $kingsGuard,
            house: $house,
            nickname: $nickname,
            imageThumb: $imageThumb,
            imageFull: $imageFull,
        );

        foreach ($actors->collection() as $actor) {
            $character->actors()->add($actor);
        }

        DomainEventPublisher::instance()->publish(
            CharacterWasCreated::from(
                characterId: $id->id(),
                internalId: $internalId->value(),
                name: $name->value(),
            ),
        );

        return $character;
    }

    public function id(): AggregateId
    {
        return $this->id;
    }

    public function internalId(): CharacterId
    {
        return $this->internalId;
    }

    public function name(): Name
    {
        return $this->name;
    }

    public function royal(): CharacterRoyal
    {
        return $this->royal;
    }

    public function kingsGuard(): CharacterKingsGuard
    {
        return $this->kingsGuard;
    }

    public function house(): ?House
    {
        return $this->house;
    }

    public function nickname(): ?Name
    {
        return $this->nickname;
    }

    public function imageThumb(): ?ImageUrl
    {
        return $this->imageThumb;
    }

    public function imageFull(): ?ImageUrl
    {
        return $this->imageFull;
    }

    /** @return Collection<int, Actor> */
    public function actors(): Collection
    {
        return $this->actors;
    }

    public function jsonSerialize(): array
    {
        return [
            'characterName' => $this->name(),
            'houseName' => $this->house()?->name(),
            'characterImageThumb' => $this->imageThumb(),
            'characterImageFull' => $this->imageFull(),
            'characterLink' => sprintf('/character/%s/', $this->internalId()),
            'actors' => $this->actors()->toArray(),
            'nickname' => $this->nickname(),
            'royal' => $this->royal()->value() ?? null,
            'kingsguard' => $this->kingsGuard()->value() ?? null,
        ];
    }
}
