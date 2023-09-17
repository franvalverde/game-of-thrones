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
        $pageNumber = PaginatorPage::from($query->page);
        $pageSize = PaginatorSize::from($query->size);

        $list = $this->houses->paginate(
            $pageNumber,
            $pageSize,
            PaginatorOrder::from($query->order),
        );

        return new ListHousesResponse($list['houses'], $list['total'], $pageNumber, $pageSize);
    }
}
