<?php

declare(strict_types=1);

namespace Whalar\Core\Application\Query\Character\RetrieveCharacter;

use Whalar\Core\Domain\Character\Aggregate\Character;

final class RetrieveCharacterResponse implements \JsonSerializable
{
    private function __construct(private readonly Character $character)
    {
    }

    public static function write(Character $character): self
    {
        return new self($character);
    }

    public function jsonSerialize(): array
    {
        return array_filter($this->character->jsonSerialize());
    }
}
