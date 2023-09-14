<?php

declare(strict_types=1);

namespace Whalar\Shared\Domain;

use Assert\Assert;

final class Utils
{
    public static function extractClassName(mixed $objectOrClass): string
    {
        Assert::that($objectOrClass)->objectOrClass();

        try {
            $reflect = new \ReflectionClass($objectOrClass);

            return $reflect->getShortName();
        } catch (\ReflectionException) {
            return '';
        }
    }

    /** @return array<mixed> */
    public static function jsonDecode(string $json): array
    {
        $data = json_decode($json, true);

        if (\JSON_ERROR_NONE !== json_last_error()) {
            throw new \RuntimeException('Unable to decode JSON string: '.json_last_error_msg());
        }

        if (!\is_array($data)) {
            throw new \RuntimeException('Unable to convert decoded JSON to array.');
        }

        return $data;
    }

    /** @param array<mixed> $data */
    public static function jsonEncode(array $data): string
    {
        $json = json_encode($data);

        if (false === $json) {
            throw new \RuntimeException('Unable to encode JSON: '.json_last_error_msg());
        }

        return $json;
    }

    public static function toSnakeCase(string $text): string
    {
        return ctype_lower($text)
            ? $text
            : strtolower(preg_replace('/([^A-Z\s])([A-Z])/', '$1_$2', $text) ?? '');
    }
}
