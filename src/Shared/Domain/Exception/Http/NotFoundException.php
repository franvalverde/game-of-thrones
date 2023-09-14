<?php

declare(strict_types=1);

namespace Whalar\Shared\Domain\Exception\Http;

use Whalar\Shared\Domain\DomainException;

abstract class NotFoundException extends DomainException
{
    final public function title(): string
    {
        return 'HTTP_NOT_FOUND';
    }
}
