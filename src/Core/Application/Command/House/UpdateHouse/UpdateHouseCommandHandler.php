<?php

declare(strict_types=1);

namespace Whalar\Core\Application\Command\House\UpdateHouse;

use Whalar\Core\Domain\House\Service\HouseUpdater;
use Whalar\Shared\Application\Command\CommandHandler;
use Whalar\Shared\Domain\ValueObject\AggregateId;
use Whalar\Shared\Domain\ValueObject\Name;

final class UpdateHouseCommandHandler implements CommandHandler
{
    public function __construct(private readonly HouseUpdater $updater)
    {
    }

    /** @throws \Throwable */
    public function __invoke(UpdateHouseCommand $command): void
    {
        $this->updater->execute(AggregateId::from($command->houseId), Name::from($command->name));
    }
}
