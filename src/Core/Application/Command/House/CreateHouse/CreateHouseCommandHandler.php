<?php

declare(strict_types=1);

namespace Whalar\Core\Application\Command\House\CreateHouse;

use Whalar\Core\Domain\House\Service\HouseCreator;
use Whalar\Shared\Application\Command\CommandHandler;
use Whalar\Shared\Domain\ValueObject\AggregateId;
use Whalar\Shared\Domain\ValueObject\Name;

final class CreateHouseCommandHandler implements CommandHandler
{
    public function __construct(private readonly HouseCreator $creator)
    {
    }

    /** @throws \Throwable */
    public function __invoke(CreateHouseCommand $command): void
    {
        $this->creator->execute(AggregateId::from($command->houseId), Name::from($command->name));
    }
}
