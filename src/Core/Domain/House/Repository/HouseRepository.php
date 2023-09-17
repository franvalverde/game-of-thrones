<?php

declare(strict_types=1);

namespace Whalar\Core\Domain\House\Repository;

use Whalar\Core\Domain\House\Aggregate\House;
use Whalar\Shared\Domain\ValueObject\AggregateId;
use Whalar\Shared\Domain\ValueObject\Name;
use Whalar\Shared\Domain\ValueObject\PaginatorOrder;
use Whalar\Shared\Domain\ValueObject\PaginatorPage;
use Whalar\Shared\Domain\ValueObject\PaginatorSize;

interface HouseRepository
{
    public function ofId(AggregateId $id): ?House;

    public function ofName(Name $name): ?House;

    public function save(House $house): void;

    public function paginate(PaginatorPage $page, PaginatorSize $size, PaginatorOrder $order): array;
}
