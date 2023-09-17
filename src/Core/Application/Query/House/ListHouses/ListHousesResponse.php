<?php

declare(strict_types=1);

namespace Whalar\Core\Application\Query\House\ListHouses;

final class ListHousesResponse implements \JsonSerializable
{
    private function __construct(public readonly array $data)
    {
    }

    public static function write(array $data): self
    {
        return new self($data);
    }

    public function jsonSerialize(): array
    {
        return $this->data;
    }
}
