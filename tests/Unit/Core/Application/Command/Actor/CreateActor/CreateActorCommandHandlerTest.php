<?php

declare(strict_types=1);

namespace Whalar\Tests\Unit\Core\Application\Command\Actor\CreateActor;

use Whalar\Core\Application\Command\Actor\CreateActor\CreateActorCommand;
use Whalar\Core\Application\Command\Actor\CreateActor\CreateActorCommandHandler;
use Whalar\Core\Domain\Actor\Event\ActorWasCreated;
use Whalar\Core\Domain\Actor\Exception\ActorAlreadyExistsException;
use Whalar\Core\Domain\Actor\Repository\ActorRepository;
use Whalar\Core\Domain\Actor\Service\ActorCreator;
use Whalar\Shared\Infrastructure\Messaging\DomainEventPublisher;
use Whalar\Tests\Shared\Core\Application\Command\Actor\CreateActor\CreateActorCommandMother;
use Whalar\Tests\Shared\Core\Domain\Actor\ValueObject\ActorIdMother;
use Whalar\Tests\Shared\Core\Infrastructure\Persistence\InMemory\InMemoryActorRepository;
use Whalar\Tests\Shared\Shared\Domain\ValueObject\AggregateIdMother;
use Whalar\Tests\Shared\Shared\Domain\ValueObject\NameMother;
use Whalar\Tests\Shared\Shared\Infrastructure\PHPUnit\UnitTestCase;

final class CreateActorCommandHandlerTest extends UnitTestCase
{
    private ActorRepository $actors;

    public function testActorIsCreatedSuccessfully(): void
    {
        $actorId = AggregateIdMother::random();
        $internalId = ActorIdMother::random();
        $name = NameMother::random();
        $sessionsActive = [2, 3];

        DomainEventPublisher::instance()->resetEvents();

        $this->createActor(
            CreateActorCommandMother::create(
                id: $actorId->id(),
                internalId: $internalId->id(),
                name: $name->value(),
                seasonsActive: $sessionsActive,
            ),
        );

        $actor = $this->actors->ofId($actorId);

        self::assertNotNull($actor);
        self::assertTrue($actorId->equalsTo($actor->id()));
        self::assertEquals($actor->name()->value(), $name->value());
        self::assertEquals($actor->seasonsActive()->value(), json_encode($sessionsActive));
        self::assertEquals($actor->seasonsActive()->toArray(), $sessionsActive);
        self::assertEquals(
            [
                'actorName' => $name->value(),
                'actorLink' => sprintf('/name/%s/', $actor->internalId()->jsonSerialize()),
                'seasonsActive' => $sessionsActive,
            ],
            $actor->jsonSerialize(),
        );

        $events = DomainEventPublisher::instance()->events();

        self::assertCount(1, $events);
        self::assertEquals($events[0]->messageAggregateId(), $actorId->id());
        self::assertInstanceOf(ActorWasCreated::class, $events[0]);
        self::assertEquals('actor', $events[0]->messageAggregateName());
        self::assertEquals($events[0]->name(), $name->value());
    }

    public function testTryCreateWithSameIdShouldThrowActorAlreadyExistsException(): void
    {
        $actorId = AggregateIdMother::random();

        $this->createActor(CreateActorCommandMother::create(id: $actorId->id()));

        $this->expectException(ActorAlreadyExistsException::class);
        $this->createActor(CreateActorCommandMother::create(id: $actorId->id()));
    }

    public function testTryCreateWithSameNameShouldThrowActorAlreadyExistsException(): void
    {
        $name = NameMother::random();

        $this->createActor(CreateActorCommandMother::create(name: $name->value()));

        $this->expectException(ActorAlreadyExistsException::class);
        $this->createActor(CreateActorCommandMother::create(name: $name->value()));
    }

    public function testTryCreateWithSameInternalIdShouldThrowActorAlreadyExistsException(): void
    {
        $internalId = ActorIdMother::random('nm2231505');

        $this->createActor(CreateActorCommandMother::create(internalId: $internalId->id()));

        $this->expectException(ActorAlreadyExistsException::class);
        $this->createActor(CreateActorCommandMother::create(internalId: $internalId->id()));
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->actors = new InMemoryActorRepository();
    }

    private function createActor(CreateActorCommand $command): void
    {
        (new CreateActorCommandHandler(new ActorCreator($this->actors)))($command);
    }
}
