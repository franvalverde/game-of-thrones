<?php

declare(strict_types=1);

namespace Whalar\Core\Application\Query\House\ListHouses;

use Whalar\Core\Domain\House\Repository\HouseRepository;
use Whalar\Shared\Application\Query\QueryHandler;
use Whalar\Shared\Domain\ValueObject\PaginatorOrder;
use Whalar\Shared\Domain\ValueObject\PaginatorPage;
use Whalar\Shared\Domain\ValueObject\PaginatorSize;

final class ListHousesQueryHandler implements QueryHandler
{
    public function __construct(private readonly HouseRepository $houses)
    {
    }

    /** @throws \Throwable */
    public function __invoke(ListHousesQuery $query): ListHousesResponse
    {
        return ListHousesResponse::write(
            $this->houses->paginate(
                PaginatorPage::from($query->page),
                PaginatorSize::from($query->size),
                PaginatorOrder::from($query->order),
            ),
        );
    }
}
