<?php

declare(strict_types=1);

namespace Whalar\Tests\Unit\Core\Application\Command\Character\CreateCharacter;

use Whalar\Core\Application\Command\Character\CreateCharacter\CreateCharacterCommand;
use Whalar\Core\Application\Command\Character\CreateCharacter\CreateCharacterCommandHandler;
use Whalar\Core\Domain\Actor\Exception\ActorAlreadyHasCharacterException;
use Whalar\Core\Domain\Actor\Exception\ActorNotFoundException;
use Whalar\Core\Domain\Actor\Repository\ActorRepository;
use Whalar\Core\Domain\Actor\Service\ActorFinder;
use Whalar\Core\Domain\Character\Event\CharacterWasCreated;
use Whalar\Core\Domain\Character\Exception\CharacterAlreadyExistsException;
use Whalar\Core\Domain\Character\Exception\CharacterMustHaveActorException;
use Whalar\Core\Domain\Character\Repository\CharacterRepository;
use Whalar\Core\Domain\Character\Service\CharacterCreator;
use Whalar\Core\Domain\Character\ValueObject\CharacterKingsGuard;
use Whalar\Core\Domain\Character\ValueObject\CharacterRoyal;
use Whalar\Core\Domain\House\Exception\HouseNotFoundException;
use Whalar\Core\Domain\House\Repository\HouseRepository;
use Whalar\Core\Domain\House\Service\HouseFinder;
use Whalar\Shared\Infrastructure\Messaging\DomainEventPublisher;
use Whalar\Tests\Shared\Core\Application\Command\Character\CreateCharacter\CreateCharacterCommandMother;
use Whalar\Tests\Shared\Core\Domain\Actor\Aggregate\ActorMother;
use Whalar\Tests\Shared\Core\Domain\Character\Aggregate\CharacterMother;
use Whalar\Tests\Shared\Core\Domain\Character\ValueObject\CharacterIdMother;
use Whalar\Tests\Shared\Core\Domain\House\Aggregate\HouseMother;
use Whalar\Tests\Shared\Core\Infrastructure\Persistence\InMemory\InMemoryActorRepository;
use Whalar\Tests\Shared\Core\Infrastructure\Persistence\InMemory\InMemoryCharacterRepository;
use Whalar\Tests\Shared\Core\Infrastructure\Persistence\InMemory\InMemoryHouseRepository;
use Whalar\Tests\Shared\Shared\Domain\ValueObject\AggregateIdMother;
use Whalar\Tests\Shared\Shared\Domain\ValueObject\NameMother;
use Whalar\Tests\Shared\Shared\Infrastructure\PHPUnit\UnitTestCase;

final class CreateCharacterCommandHandlerTest extends UnitTestCase
{
    private CharacterRepository $characters;
    private HouseRepository $houses;
    private ActorRepository $actors;

    public function testCharacterIsCreatedSuccessfully(): void
    {
        $character = CharacterMother::create(royal: CharacterRoyal::from(true));
        $actor = ActorMother::create();
        $this->actors->save($actor);

        $this->createCharacter(
            CreateCharacterCommandMother::create(
                id: $character->id()->id(),
                internalId: $character->internalId()->value(),
                name: $character->name()->value(),
                royal: $character->royal()->value(),
                kingGuard: $character->kingsGuard()->value(),
                actors: [$actor->internalId()->value()],
                nickname: $character->nickname()?->value(),
                imageThumb: $character->imageThumb()?->value(),
                imageFull: $character->imageFull()?->value(),
            ),
        );

        $characterCreated = $this->characters->ofInternalId($character->internalId());

        self::assertNotNull($characterCreated);
        self::assertTrue($character->id()->equalsTo($characterCreated->id()));
        self::assertEquals($character->name()->value(), $characterCreated->name()->value());
        self::assertEquals('true', $characterCreated->royal()->__toString());
        self::assertEquals('true', $characterCreated->royal()->jsonSerialize());
        self::assertNotEmpty($characterCreated->actors());
        self::assertNull($characterCreated->house());
        self::assertEmpty($characterCreated->relates());

        $events = DomainEventPublisher::instance()->events();

        self::assertCount(1, $events);
        self::assertEquals($events[0]->messageAggregateId(), $character->id()->id());
        self::assertInstanceOf(CharacterWasCreated::class, $events[0]);
        self::assertEquals('character', $events[0]->messageAggregateName());
        self::assertEquals($events[0]->name(), $character->name()->value());
        self::assertEquals($events[0]->internalId(), $character->internalId()->value());
    }

    public function testCharacterThatBelongsToAHouseIsCreatedSuccessfully(): void
    {
        $character = CharacterMother::create(kingsGuard: CharacterKingsGuard::from(true));

        $actor = ActorMother::create();
        $this->actors->save($actor);

        $house = HouseMother::create();
        $this->houses->save($house);

        $this->createCharacter(
            CreateCharacterCommandMother::create(
                internalId: $character->internalId()->value(),
                kingGuard: $character->kingsGuard()->value(),
                actors: [$actor->internalId()->value()],
                houseId: $house->id()->id(),
            ),
        );

        $characterCreated = $this->characters->ofInternalId($character->internalId());

        self::assertNotNull($characterCreated);
        self::assertEquals($character->internalId()->value(), $characterCreated->internalId()->jsonSerialize());
        self::assertEquals($character->internalId()->value(), $characterCreated->internalId()->__toString());
        self::assertEquals('true', $characterCreated->kingsGuard()->jsonSerialize());
        self::assertEquals('true', $characterCreated->kingsGuard()->__toString());
        self::assertNotNull($characterCreated->house());
        self::assertTrue($house->id()->equalsTo($characterCreated->house()->id()));
    }

    public function testTryCreateWithSameNameShouldThrowCharacterAlreadyExistsException(): void
    {
        $name = NameMother::random();

        $this->createCharacter($this->getCommand(name: $name->value()));

        $this->expectException(CharacterAlreadyExistsException::class);
        $this->createCharacter($this->getCommand(name: $name->value()));
    }

    public function testTryCreateWithActorNotFoundShouldThrowException(): void
    {
        $this->expectException(ActorNotFoundException::class);
        $this->createCharacter(CreateCharacterCommandMother::create());
    }

    public function testTryCreateWithHouseNotFoundShouldThrowException(): void
    {
        $this->expectException(HouseNotFoundException::class);
        $this->createCharacter($this->getCommand(houseId: AggregateIdMother::random()->id()));
    }

    public function testTryCreateWithoutActorsShouldThrowException(): void
    {
        $this->expectException(CharacterMustHaveActorException::class);
        $this->createCharacter(CreateCharacterCommandMother::create(actors: []));
    }

    public function testTryCreateWithSameInternalIdShouldThrowCharacterAlreadyExistsException(): void
    {
        $internalId = CharacterIdMother::random('ch2231505');

        $this->createCharacter($this->getCommand(internalId: $internalId->value()));

        $this->expectException(CharacterAlreadyExistsException::class);
        $this->createCharacter($this->getCommand(internalId: $internalId->value()));
    }

    public function testTryCreateWithActorAlreadyAssignedShouldThrowException(): void
    {
        $actor = ActorMother::create();
        $this->actors->save($actor);

        $this->createCharacter(
            CreateCharacterCommandMother::create(
                actors: [$actor->internalId()->value()],
            ),
        );

        $this->expectException(ActorAlreadyHasCharacterException::class);
        $this->createCharacter(CreateCharacterCommandMother::create(actors: [$actor->internalId()->value()]));
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->characters = new InMemoryCharacterRepository();
        $this->houses = new InMemoryHouseRepository();
        $this->actors = new InMemoryActorRepository();
    }

    private function createCharacter(CreateCharacterCommand $command): void
    {
        (new CreateCharacterCommandHandler(
            new CharacterCreator($this->characters, $this->actors),
            new HouseFinder($this->houses),
            new ActorFinder($this->actors),
        ))($command);
    }

    private function getCommand(
        ?string $name = null,
        ?string $internalId = null,
        ?string $houseId = null,
    ): CreateCharacterCommand {
        $actor = ActorMother::create();
        $this->actors->save($actor);

        return CreateCharacterCommandMother::create(
            internalId: $internalId ?? CharacterIdMother::create(),
            name: $name ?? NameMother::create(),
            actors: [$actor->internalId()->value()],
            houseId: $houseId,
        );
    }
}
