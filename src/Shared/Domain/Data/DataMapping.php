<?php

declare(strict_types=1);

namespace Whalar\Shared\Domain\Data;

use Assert\Assertion;
use Illuminate\Support\Arr;
use Whalar\Shared\Domain\Exception\InvalidDataMappingException;
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

    /* @param array<mixed> $data */
    private static function getFloat(array $data, string $key, float $default = 0.0): float
    {
        $value = self::getFloatOrNull($data, $key);

        return $value ?? $default;
    }

    /* @param array<mixed> $data */
    private static function getFloatOrNull(array $data, string $key): ?float
    {
        $value = Arr::get($data, $key);

        if (null === $value) {
            return null;
        }

        if (\is_bool($value)) {
            return (float) $value;
        }

        return filter_var($value, \FILTER_VALIDATE_FLOAT, \FILTER_NULL_ON_FAILURE);
    }

    /* @param array<mixed> $data */
    private static function getId(array $data): string
    {
        $value = Arr::get($data, 'id');

        if (!is_scalar($value)) {
            $value = AggregateId::random()->id();
        }

        Assertion::string($value);

        return $value;
    }

    /* @param array<mixed> $data */
    private static function getString(array $data, string $key, string $default = ''): string
    {
        $value = Arr::get($data, $key);

        if (!is_scalar($value)) {
            return $default;
        }

        if (true === $value) {
            return 'true';
        }

        if (false === $value) {
            return 'false';
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

    /**
     * @param array<mixed> $data
     *
     * @throws InvalidDataMappingException
     */
    private static function getStringOrFail(array $data, string $key, ?string $arrayName = null): string
    {
        if (!Arr::has($data, $key)) {
            throw InvalidDataMappingException::fromMissingKey($key, $arrayName);
        }

        $value = Arr::get($data, $key);

        if (!is_scalar($value)) {
            throw InvalidDataMappingException::fromNonScalar($key, $arrayName);
        }

        if (true === $value) {
            return 'true';
        }

        if (false === $value) {
            return 'false';
        }

        return (string) $value;
    }

    // @phpstan-ignore-next-line
    private static function integerOfArray(string $key, array $data): int
    {
        return array_key_exists($key, $data)
            ? $data[$key]
            : 0;
    }
}
