<?php

declare(strict_types=1);

namespace Whalar\Core\Domain\House\Aggregate;

use Whalar\Shared\Domain\ValueObject\AggregateId;
use Whalar\Shared\Domain\ValueObject\Name;

class House
{
    private function __construct(private AggregateId $id, private readonly Name $name)
    {
    }

    public static function create(AggregateId $id, Name $name): self
    {
        return new self($id, $name);
    }

    public function id(): AggregateId
    {
        return $this->id;
    }

    public function name(): Name
    {
        return $this->name;
    }
}
