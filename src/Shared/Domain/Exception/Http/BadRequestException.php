<?php

declare(strict_types=1);

namespace Whalar\Shared\Domain\Exception\Http;

use Whalar\Shared\Domain\DomainException;

abstract class BadRequestException extends DomainException
{
    final public function title(): string
    {
        return 'HTTP_BAD_REQUEST';
    }
}
