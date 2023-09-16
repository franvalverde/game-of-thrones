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
use Whalar\Core\Domain\Character\ValueObject\CharacterRoyal;
use Whalar\Shared\Infrastructure\Messaging\DomainEventPublisher;
use Whalar\Tests\Shared\Core\Application\Command\Character\CreateCharacter\CreateCharacterCommandMother;
use Whalar\Tests\Shared\Core\Domain\Actor\Aggregate\ActorMother;
use Whalar\Tests\Shared\Core\Domain\Character\Aggregate\CharacterMother;
use Whalar\Tests\Shared\Core\Domain\Character\ValueObject\CharacterIdMother;
use Whalar\Tests\Shared\Core\Infrastructure\Persistence\InMemory\InMemoryActorRepository;
use Whalar\Tests\Shared\Core\Infrastructure\Persistence\InMemory\InMemoryCharacterRepository;
use Whalar\Tests\Shared\Shared\Domain\ValueObject\NameMother;
use Whalar\Tests\Shared\Shared\Infrastructure\PHPUnit\UnitTestCase;

final class CreateCharacterCommandHandlerTest extends UnitTestCase
{
    private CharacterRepository $characters;
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
        self::assertNotEmpty($characterCreated->actors());

        self::assertEquals(
            [
                'characterName' => $character->name()->value(),
                'characterLink' => sprintf('/name/%s/', $character->internalId()->jsonSerialize()),
                'royal' => $character->royal()->jsonSerialize(),
                'kingsguard' => $character->kingsGuard()->jsonSerialize(),
            ],
            $characterCreated->jsonSerialize(),
        );

        $events = DomainEventPublisher::instance()->events();

        self::assertCount(1, $events);
        self::assertEquals($events[0]->messageAggregateId(), $character->id()->id());
        self::assertInstanceOf(CharacterWasCreated::class, $events[0]);
        self::assertEquals('character', $events[0]->messageAggregateName());
        self::assertEquals($events[0]->name(), $character->name()->value());
        self::assertEquals($events[0]->internalId(), $character->internalId()->value());
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
        $this->actors = new InMemoryActorRepository();
    }

    private function createCharacter(CreateCharacterCommand $command): void
    {
        (new CreateCharacterCommandHandler(
            new CharacterCreator($this->characters, $this->actors),
            new ActorFinder($this->actors),
        ))($command);
    }

    private function getCommand(?string $name = null, ?string $internalId = null): CreateCharacterCommand
    {
        $actor = ActorMother::create();
        $this->actors->save($actor);

        return CreateCharacterCommandMother::create(
            internalId: $internalId ?? CharacterIdMother::create(),
            name: $name ?? NameMother::create(),
            actors: [$actor->internalId()->value()],
        );
    }
}
