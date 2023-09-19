<?php

declare(strict_types=1);

namespace Whalar\Core\Application\Command\House\UpdateHouse;

use Whalar\Shared\Application\Command\Command;

final class UpdateHouseCommand implements Command
{
    public function __construct(public readonly string $houseId, public readonly string $name)
    {
    }
}
