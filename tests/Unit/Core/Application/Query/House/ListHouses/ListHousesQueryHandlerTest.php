<?php

declare(strict_types=1);

namespace Whalar\Tests\Unit\Core\Application\Query\House\ListHouses;

use Whalar\Core\Application\Query\House\ListHouses\ListHousesQuery;
use Whalar\Core\Application\Query\House\ListHouses\ListHousesQueryHandler;
use Whalar\Core\Application\Query\House\ListHouses\ListHousesResponse;
use Whalar\Core\Domain\House\Repository\HouseRepository;
use Whalar\Shared\Domain\ValueObject\Name;
use Whalar\Tests\Shared\Core\Domain\House\Aggregate\HouseMother;
use Whalar\Tests\Shared\Core\Infrastructure\Persistence\InMemory\InMemoryHouseRepository;
use Whalar\Tests\Shared\Shared\Infrastructure\PHPUnit\UnitTestCase;

final class ListHousesQueryHandlerTest extends UnitTestCase
{
    private HouseRepository $houses;

    public function testListHousesSuccessfully(): void
    {
        $page = 1;
        $size = 10;
        $order = 'desc';

        $starkHouse = HouseMother::create(name: Name::from('Stark'));
        $lanisterHouse = HouseMother::create(name: Name::from('Lanister'));

        $this->houses->save($starkHouse);
        $this->houses->save($lanisterHouse);

        $response = $this->listHouses(new ListHousesQuery($page, $size, $order));

        self::assertEquals(
            [
                [
                    'id' => $starkHouse->id(),
                    'name' => $starkHouse->name(),
                ],
                [
                    'id' => $lanisterHouse->id(),
                    'name' => $lanisterHouse->name(),
                ],
            ],
            $response->jsonSerialize(),
        );

        self::assertEquals(
            [
                'currentPage' => $page,
                'lastPage' => 1,
                'size' => $size,
                'total' => 2,
            ],
            $response->jsonSerialize()['meta'],
        );
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->houses = new InMemoryHouseRepository();
    }

    private function listHouses(ListHousesQuery $query): ListHousesResponse
    {
        return (new ListHousesQueryHandler($this->houses))($query);
    }
}
