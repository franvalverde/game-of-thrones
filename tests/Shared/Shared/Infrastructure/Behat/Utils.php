<?php

declare(strict_types=1);

namespace Whalar\Tests\Shared\Shared\Infrastructure\Behat;

use Behat\Gherkin\Node\PyStringNode;

final class Utils
{
    /**
     * Convert a PyStringNode into an array.
     *
     * @return array<mixed>
     */
    public static function normalizePyStringNode(PyStringNode $node): array
    {
        return json_decode($node->getRaw(), true, 512, \JSON_THROW_ON_ERROR);
    }

    /**
     * Make a string version of a value.
     */
    public static function stringify(mixed $value): string
    {
        $result = \gettype($value);

        if (\is_bool($value)) {
            $result = $value
                ? '<TRUE>'
                : '<FALSE>';
        } elseif (\is_scalar($value)) {
            $val = (string) $value;

            if (mb_strlen($val) > 100) {
                $val = mb_substr($val, 0, 97).'...';
            }

            $result = $val;
        } elseif (\is_array($value)) {
            $result = '<ARRAY>';
        } elseif (\is_object($value)) {
            $result = $value::class;
        } elseif (\is_resource($value)) {
            $result = get_resource_type($value);
        } elseif (null === $value) {
            $result = '<NULL>';
        }

        return $result;
    }
}
