<?php

declare(strict_types=1);

namespace Whalar\Tests\Shared\Shared\Infrastructure\Behat\Json;

use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

final class JsonInspector
{
    public function __construct(
        private readonly PropertyAccessorInterface $accessor,
        private readonly string $evaluationMode = 'javascript',
    ) {
    }

    // @phpstan-ignore-next-line
    public function evaluate(Json $json, $expression): mixed
    {
        if ('javascript' === $this->evaluationMode) {
            $expression = str_replace('->', '.', (string) $expression);
        }

        try {
            return $json->read($expression, $this->accessor);
        } catch (\Throwable) {
            throw new \RuntimeException("Failed to evaluate expression '$expression'");
        }
    }
}
