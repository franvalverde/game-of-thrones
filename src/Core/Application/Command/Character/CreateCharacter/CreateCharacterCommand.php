<?php

declare(strict_types=1);

namespace Whalar\Core\Application\Command\Character\CreateCharacter;

use Whalar\Shared\Application\Command\Command;

final class CreateCharacterCommand implements Command
{
    public function __construct(
        public readonly string $id,
        public readonly string $characterId,
        public readonly string $name,
        public readonly bool $royal,
        public readonly bool $kingsGuard,
        public readonly ?string $nickname,
        public readonly ?string $imageThumb,
        public readonly ?string $imageFull,
    ) {
    }
}
