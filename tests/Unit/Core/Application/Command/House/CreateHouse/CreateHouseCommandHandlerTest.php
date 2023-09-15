<?php

declare(strict_types=1);

namespace Whalar\Tests\Unit\Core\Application\Command\House\CreateHouse;

use Whalar\Core\Application\Command\House\CreateHouse\CreateHouseCommand;
use Whalar\Core\Application\Command\House\CreateHouse\CreateHouseCommandHandler;
use Whalar\Core\Domain\House\Event\HouseWasCreated;
use Whalar\Core\Domain\House\Exception\HouseAlreadyExistsException;
use Whalar\Core\Domain\House\Repository\HouseRepository;
use Whalar\Core\Domain\House\Service\HouseCreator;
use Whalar\Shared\Infrastructure\Messaging\DomainEventPublisher;
use Whalar\Tests\Shared\Core\Application\Command\House\CreateHouse\CreateHouseCommandMother;
use Whalar\Tests\Shared\Core\Infrastructure\Persistence\InMemory\InMemoryHouseRepository;
use Whalar\Tests\Shared\Shared\Domain\ValueObject\AggregateIdMother;
use Whalar\Tests\Shared\Shared\Domain\ValueObject\NameMother;
use Whalar\Tests\Shared\Shared\Infrastructure\PHPUnit\UnitTestCase;

final class CreateHouseCommandHandlerTest extends UnitTestCase
{
    private HouseRepository $houses;

    public function testHouseIsCreatedSuccessfully(): void
    {
        $houseId = AggregateIdMother::random();
        $name = NameMother::random();

        DomainEventPublisher::instance()->resetEvents();

        $this->createHouse(CreateHouseCommandMother::create(houseId: $houseId->id(), name: $name->value()));

        $house = $this->houses->ofId($houseId);

        self::assertNotNull($house);
        self::assertTrue($houseId->equalsTo($house->id()));
        self::assertEquals($name->value(), $name->value());

        $events = DomainEventPublisher::instance()->events();

        self::assertCount(1, $events);
        self::assertEquals($events[0]->messageAggregateId(), $houseId->id());
        self::assertInstanceOf(HouseWasCreated::class, $events[0]);
        self::assertEquals('house', $events[0]->messageAggregateName());
        self::assertEquals($events[0]->name(), $name->value());
    }

    public function testTryCreateWithSameIdShouldThrowHouseAlreadyExistsException(): void
    {
        $houseId = AggregateIdMother::random();

        $this->createHouse(CreateHouseCommandMother::create(houseId: $houseId->id()));

        $this->expectException(HouseAlreadyExistsException::class);
        $this->createHouse(CreateHouseCommandMother::create(houseId: $houseId->id()));
    }

    public function testTryCreateWithSameNameShouldThrowHouseAlreadyExistsException(): void
    {
        $name = NameMother::random();

        $this->createHouse(CreateHouseCommandMother::create(name: $name->value()));

        $this->expectException(HouseAlreadyExistsException::class);
        $this->createHouse(CreateHouseCommandMother::create(name: $name->value()));
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->houses = new InMemoryHouseRepository();
    }

    private function createHouse(CreateHouseCommand $command): void
    {
        (new CreateHouseCommandHandler(new HouseCreator($this->houses)))($command);
    }
}
