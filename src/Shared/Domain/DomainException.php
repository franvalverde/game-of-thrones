<?php

declare(strict_types=1);

namespace Whalar\Shared\Domain;

use Exception;
use JetBrains\PhpStorm\Pure;
use Throwable;

abstract class DomainException extends Exception
{
    /** @noinspection PhpAttributeCanBeAddedToOverriddenMemberInspection */
    #[Pure]
    protected function __construct(string $message, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    abstract public function title(): string;

    #[Pure]
    public function detail(): string
    {
        return $this->getMessage();
    }

    public function code(): string
    {
        return str_replace('_exception', '', Utils::toSnakeCase(Utils::extractClassName($this)));
    }
}
