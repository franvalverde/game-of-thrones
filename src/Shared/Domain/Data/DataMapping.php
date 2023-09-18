<?php

declare(strict_types=1);

namespace Whalar\Shared\Domain\Data;

use Assert\AssertionFailedException;
use Illuminate\Support\Arr;
use Whalar\Shared\Domain\ValueObject\AggregateId;

trait DataMapping
{
    /**
     * @param array $data
     * @param string $key
     * @return array|null
     */
    private static function getArray(array $data, string $key): ?array
    {
        if (!array_key_exists($key, $data)) {
            return null;
        }

        $value = $data[$key];

        return !\is_array($value)
            ? null
            : $value;
    }

    /* @param array<mixed> $data */
    private static function getBool(array $data, string $key, bool $default = false): bool
    {
        $value = self::getBoolOrNull($data, $key);

        if (null === $value) {
            return $default;
        }

        return $value;
    }

    /* @param array<mixed> $data */
    private static function getBoolOrNull(array $data, string $key): ?bool
    {
        $value = Arr::get($data, $key);

        if (null === $value) {
            return null;
        }

        return filter_var($value, \FILTER_VALIDATE_BOOLEAN, \FILTER_NULL_ON_FAILURE);
    }

    /* @param array<mixed> $data */
    private static function getInt(array $data, string $key, int $default = 0): int
    {
        $value = self::getIntOrNull($data, $key);

        if (null === $value) {
            return $default;
        }

        return $value;
    }

    /* @param array<mixed> $data */
    private static function getIntOrNull(array $data, string $key): ?int
    {
        $value = Arr::get($data, $key);

        if (null === $value) {
            return null;
        }

        if (\is_bool($value)) {
            return (int) $value;
        }

        return filter_var($value, \FILTER_VALIDATE_INT, \FILTER_NULL_ON_FAILURE);
    }

    /** @throws AssertionFailedException */
    private static function generateId(): string
    {
        return AggregateId::random()->id();
    }

    /* @param array<mixed> $data */
    private static function getString(array $data, string $key, string $default = ''): string
    {
        $value = Arr::get($data, $key);

        if (!is_scalar($value)) {
            return $default;
        }

        if (true === $value || false === $value) {
            return true === $value
                ? 'true'
                : 'false';
        }

        return (string) $value;
    }

    /* @param array<mixed> $data */
    private static function getNonEmptyStringOrNull(array $data, string $key): ?string
    {
        $value = self::getString($data, $key);

        if ('' === $value) {
            return null;
        }

        return $value;
    }
}
