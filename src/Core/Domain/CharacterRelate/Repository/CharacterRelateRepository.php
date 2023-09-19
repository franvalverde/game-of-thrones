<?php

declare(strict_types=1);

namespace Whalar\Core\Domain\CharacterRelate\Repository;

use Whalar\Core\Domain\CharacterRelate\Aggregate\CharacterRelate;

interface CharacterRelateRepository
{
    public function save(CharacterRelate $characterRelate): void;
}
