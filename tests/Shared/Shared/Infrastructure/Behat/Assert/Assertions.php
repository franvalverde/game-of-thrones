<?php

declare(strict_types=1);

namespace Whalar\Tests\Shared\Shared\Infrastructure\Behat\Assert;

use Whalar\Tests\Shared\Shared\Infrastructure\Behat\Assert\Assertion\FalseAssertion;
use Whalar\Tests\Shared\Shared\Infrastructure\Behat\Assert\Assertion\NullAssertion;
use Whalar\Tests\Shared\Shared\Infrastructure\Behat\Assert\Assertion\TrueAssertion;
use Whalar\Tests\Shared\Shared\Infrastructure\Behat\Json\Json;
use Whalar\Tests\Shared\Shared\Infrastructure\Behat\Json\JsonInspector;
use Whalar\Tests\Shared\Shared\Infrastructure\Behat\Utils;
use Illuminate\Support\Arr;
use PHPUnit\Framework\Assert;

trait Assertions
{
    /**
     * @param array<mixed> $expected
     * @param array<mixed> $actual
     */
    public function assertArrayEqualsArray(array $expected, array $actual): void
    {
        foreach ($expected as $key => $expectedValue) {
            $actualValue = Arr::get($actual, $key);

            $this->assertEquals(
                $expectedValue,
                $actualValue,
                sprintf(
                    "The array key %s and value %s doesn't match the expected.",
                    Utils::stringify($key),
                    Utils::stringify($actualValue),
                ),
            );
        }
    }

    /** @param array<mixed> $expected */
    public function assertArrayEqualsJson(array $expected, Json $actual): void
    {
        foreach ($expected as $key => $expectedValue) {
            $actualValue = $this->jsonInspector()->evaluate($actual, $key);

            $this->assertEquals(
                $expectedValue,
                $actualValue,
                sprintf(
                    'The JSON key "%s" and value "%s" doesn\'t match the expected.',
                    Utils::stringify($key),
                    Utils::stringify($actualValue),
                ),
            );
        }
    }

    public function assertEquals(mixed $expected, mixed $actual, string $message = ''): void
    {
        \assert(\is_scalar($expected), sprintf('Expected value %s must be an scalar.', Utils::stringify($expected)));

        try {
            // If expected value starts with "=", then we treat it as a custom checker.
            if (preg_match('/^=(\w+)(\(?\w[,\w]*\))?$/', (string) $expected, $matches)) {
                $checkerId = $matches[1];
                $checkerArgs = \array_key_exists(2, $matches)
                    ? explode(',', substr($matches[2], 1, -1))
                    : [];

                (Assertion::get($checkerId, $checkerArgs))($actual);

                return;
            }

            if ($expected === 'null') {
                (new NullAssertion())($actual);

                return;
            }

            if ($expected === 'true') {
                (new TrueAssertion())($actual);

                return;
            }

            if ($expected === 'false') {
                (new FalseAssertion())($actual);

                return;
            }

            Assert::assertEquals($expected, $actual);
        } catch (\Throwable $exception) {
            throw new \RuntimeException(trim(sprintf('%s %s', $message, $exception->getMessage())));
        }
    }

    abstract protected function jsonInspector(): JsonInspector;
}
