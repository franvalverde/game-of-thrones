<?php

declare(strict_types=1);

namespace Whalar\Core\Domain\Character\Aggregate;

use ApiPlatform\Metadata\ApiResource;
use Whalar\Core\Domain\Character\Event\CharacterWasCreated;
use Whalar\Core\Domain\Character\ValueObject\CharacterId;
use Whalar\Core\Domain\Character\ValueObject\CharacterKingsGuard;
use Whalar\Core\Domain\Character\ValueObject\CharacterRoyal;
use Whalar\Shared\Domain\ValueObject\AggregateId;
use Whalar\Shared\Domain\ValueObject\ImageUrl;
use Whalar\Shared\Domain\ValueObject\Name;
use Whalar\Shared\Infrastructure\Messaging\DomainEventPublisher;

#[ApiResource]
class Character implements \JsonSerializable
{
    private function __construct(
        private AggregateId $id,
        private readonly CharacterId $internalId,
        private readonly Name $name,
        private readonly CharacterRoyal $royal,
        private readonly CharacterKingsGuard $kingsGuard,
        private readonly ?Name $nickname,
        private readonly ?ImageUrl $imageThumb,
        private readonly ?ImageUrl $imageFull,
    ) {
    }

    /** @throws \Throwable */
    public static function create(
        AggregateId $id,
        CharacterId $internalId,
        Name $name,
        CharacterRoyal $royal,
        CharacterKingsGuard $kingsGuard,
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
            nickname: $nickname,
            imageThumb: $imageThumb,
            imageFull: $imageFull,
        );

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

    public function jsonSerialize(): array
    {
        return [
            'characterName' => $this->name()->value(),
            'characterLink' => sprintf('/name/%s/', $this->internalId()),
            'royal' => $this->royal(),
            'kingsguard' => $this->kingsGuard(),
        ];
    }
}
