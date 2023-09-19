<?php

declare(strict_types=1);

namespace Whalar\Tests\Unit\Core\Application\Command\House\UpdateHouse;

use Whalar\Core\Application\Command\House\UpdateHouse\UpdateHouseCommand;
use Whalar\Core\Application\Command\House\UpdateHouse\UpdateHouseCommandHandler;
use Whalar\Core\Domain\House\Aggregate\House;
use Whalar\Core\Domain\House\Event\HouseWasUpdated;
use Whalar\Core\Domain\House\Exception\HouseNotFoundException;
use Whalar\Core\Domain\House\Repository\HouseRepository;
use Whalar\Core\Domain\House\Service\HouseUpdater;
use Whalar\Shared\Infrastructure\Messaging\DomainEventPublisher;
use Whalar\Tests\Shared\Core\Domain\House\Aggregate\HouseMother;
use Whalar\Tests\Shared\Core\Infrastructure\Persistence\InMemory\InMemoryHouseRepository;
use Whalar\Tests\Shared\Shared\Domain\ValueObject\AggregateIdMother;
use Whalar\Tests\Shared\Shared\Domain\ValueObject\NameMother;
use Whalar\Tests\Shared\Shared\Infrastructure\PHPUnit\UnitTestCase;

final class UpdateHouseCommandHandlerTest extends UnitTestCase
{
    private HouseRepository $houses;
    private House $house;

    public function testHouseIsUpdatedSuccessfully(): void
    {
        $name = NameMother::random();

        $this->updateHouse(new UpdateHouseCommand(houseId: $this->house->id()->id(), name: $name->value()));

        $houseUpdated = $this->houses->ofId($this->house->id());

        self::assertNotNull($houseUpdated);
        self::assertEquals($houseUpdated->name()->value(), $name->value());

        $events = DomainEventPublisher::instance()->events();

        self::assertCount(1, $events);
        self::assertEquals($events[0]->messageAggregateId(), $this->house->id()->id());
        self::assertInstanceOf(HouseWasUpdated::class, $events[0]);
        self::assertEquals('house', $events[0]->messageAggregateName());
        self::assertEquals($events[0]->name(), $name->value());
    }

    public function testTryUpdateWithHouseIdNotFoundShouldThrowException(): void
    {
        $this->expectException(HouseNotFoundException::class);
        $this->updateHouse(new UpdateHouseCommand(houseId: AggregateIdMother::create(), name: NameMother::create()));
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->houses = new InMemoryHouseRepository();
        $this->house = HouseMother::create();
        $this->houses->save($this->house);
    }

    private function updateHouse(UpdateHouseCommand $command): void
    {
        (new UpdateHouseCommandHandler(new HouseUpdater($this->houses)))($command);
    }
}
