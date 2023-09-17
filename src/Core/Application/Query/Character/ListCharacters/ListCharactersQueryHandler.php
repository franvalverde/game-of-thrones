<?php

declare(strict_types=1);

namespace Whalar\Core\Application\Query\Character\ListCharacters;

use Whalar\Core\Domain\Character\Repository\CharacterRepository;
use Whalar\Shared\Application\Query\QueryHandler;
use Whalar\Shared\Domain\ValueObject\PaginatorOrder;
use Whalar\Shared\Domain\ValueObject\PaginatorPage;
use Whalar\Shared\Domain\ValueObject\PaginatorSize;

final class ListCharactersQueryHandler implements QueryHandler
{
    public function __construct(private readonly CharacterRepository $characters)
    {
    }

    /** @throws \Throwable */
    public function __invoke(ListCharactersQuery $query): ListCharactersResponse
    {
        $pageNumber = PaginatorPage::from($query->page);
        $pageSize = PaginatorSize::from($query->size);

        $list = $this->characters->paginate(
            $pageNumber,
            $pageSize,
            PaginatorOrder::from($query->order),
        );

        return new ListCharactersResponse($list['characters'], $list['total'], $pageNumber, $pageSize);
    }
}
