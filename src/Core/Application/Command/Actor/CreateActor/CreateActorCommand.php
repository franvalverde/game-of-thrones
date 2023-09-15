<?php

declare(strict_types=1);

namespace Whalar\Core\Application\Command\Actor\CreateActor;

use Whalar\Shared\Application\Command\Command;

final class CreateActorCommand implements Command
{
    public function __construct(
        public readonly string $id,
        public readonly string $actorId,
        public readonly string $name,
        public readonly ?array $seasonsActive,
    ) {
    }
}
