<?php

declare(strict_types=1);

namespace Whalar\Tests\Shared\Core\Infrastructure\Behat\Context;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Whalar\Core\Domain\House\Repository\HouseRepository;
use Whalar\Shared\Domain\ValueObject\AggregateId;
use Whalar\Shared\Domain\ValueObject\Name;
use Whalar\Tests\Shared\Core\Domain\House\Aggregate\HouseMother;

final class HouseContext implements Context
{
    public function __construct(private readonly HouseRepository $houses)
    {
    }

    /** @Given /^the following house exist:$/ */
    public function theFollowingHouseExist(TableNode $table): void
    {
        foreach ($table->getHash() as $row) {
            $house = HouseMother::create(
                id: AggregateId::from($row['id']),
                name: Name::from($row['name']),
            );

            $this->houses->save($house);
        }
    }
}
