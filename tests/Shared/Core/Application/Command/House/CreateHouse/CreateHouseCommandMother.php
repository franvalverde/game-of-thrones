<?php

declare(strict_types=1);

namespace Whalar\Tests\Shared\Core\Application\Command\House\CreateHouse;

use Assert\AssertionFailedException;
use Whalar\Core\Application\Command\House\CreateHouse\CreateHouseCommand;
use Whalar\Tests\Shared\Shared\Domain\ValueObject\AggregateIdMother;
use Whalar\Tests\Shared\Shared\Domain\ValueObject\NameMother;

final class CreateHouseCommandMother
{
    /** @throws AssertionFailedException */
    public static function create(?string $houseId = null, ?string $name = null): CreateHouseCommand
    {
        return new CreateHouseCommand(
            $houseId ?? AggregateIdMother::random()->id(),
            $name ?? NameMother::random()->name(),
        );
    }
}
