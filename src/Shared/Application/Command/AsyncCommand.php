<?php

declare(strict_types=1);

namespace Whalar\Shared\Application\Command;

use Whalar\Shared\Domain\Messaging\Message;
use Illuminate\Support\Str;

abstract class AsyncCommand extends Message
{
    final public static function messageType(): string
    {
        return 'command';
    }

    final public static function messageAction(): string
    {
        return (string) Str::of(class_basename(static::class))->snake()->replaceLast('_command', '');
    }
}
