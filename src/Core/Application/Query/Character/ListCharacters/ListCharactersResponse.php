<?php

declare(strict_types=1);

namespace Whalar\Core\Application\Query\Character\ListCharacters;

use Whalar\Core\Domain\Character\Aggregate\Character;
use Whalar\Shared\Application\Query\PaginatorResponse;

final class ListCharactersResponse extends PaginatorResponse implements \JsonSerializable
{
    public function jsonSerialize(): array
    {
        return [
            'characters' => $this->transform($this->results()),
            'meta' => $this->meta(),
        ];
    }

    /** @param array<int, Character> $result */
    private function transform(array $result): array
    {
        $items = [];

        foreach ($result as $character) {
            $item = $character->jsonSerialize();

            if (1 === count($item['actors'])) {
                $item = array_merge($item, $item['actors'][0]->jsonSerialize());
                unset($item['actors']);
            }

            $items[] = array_filter($item);
        }

        return $items;
    }
}
