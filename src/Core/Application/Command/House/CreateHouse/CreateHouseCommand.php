<?php

declare(strict_types=1);

namespace Whalar\Core\Application\Command\House\CreateHouse;

use Whalar\Shared\Application\Command\Command;

final class CreateHouseCommand implements Command
{
    public function __construct(public readonly string $houseId, public readonly string $name)
    {
    }
}
