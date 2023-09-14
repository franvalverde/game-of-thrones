<?php

declare(strict_types=1);

namespace Whalar\Tests\Unit\Shared\Domain\Data;

use Whalar\Shared\Domain\Data\DataMapping;
use Whalar\Shared\Domain\Exception\InvalidDataMappingException;
use Whalar\Tests\Shared\Shared\Domain\ValueObject\AggregateIdMother;
use Whalar\Tests\Shared\Shared\Infrastructure\PHPUnit\UnitTestCase;

final class DataMappingTest extends UnitTestCase
{
    use DataMapping;

    /** @dataProvider getBoolDataProvider */
    public function testShouldGetBool(mixed $input, bool $output): void
    {
        $foo = [
            'bar' => $input,
        ];

        $this->assertSame($output, self::getBool($foo, 'bar'));
    }

    /** @return array<array<mixed>> */
    public function getBoolDataProvider(): array
    {
        return [
            // "True" values
            [true, true],
            [1, true],
            ['1', true],
            ['true', true],
            ['on', true],
            ['yes', true],

            // "False" values
            [false, false],
            [0, false],
            ['0', false],
            ['false', false],
            ['off', false],
            ['no', false],

            // Invalid values
            [null, false],
            [123, false],
            ['a string', false],
            [[], false],
        ];
    }

    /** @dataProvider getBoolOrNullDataProvider */
    public function testShouldGetBoolOrNull(mixed $input, ?bool $output): void
    {
        $foo = [
            'bar' => $input,
        ];

        $this->assertSame($output, self::getBoolOrNull($foo, 'bar'));
    }

    /** @return array<array<mixed>> */
    public function getBoolOrNullDataProvider(): array
    {
        return [
            // "True" values
            [true, true],
            [1, true],
            ['1', true],
            ['true', true],
            ['on', true],
            ['yes', true],

            // "False" values
            [false, false],
            [0, false],
            ['0', false],
            ['false', false],
            ['off', false],
            ['no', false],

            // Invalid values
            [null, null],
            [123, null],
            ['a string', null],
            [[], null],
        ];
    }

    /** @dataProvider getIntDataProvider */
    public function testShouldGetInt(mixed $input, int $output): void
    {
        $foo = [
            'bar' => $input,
        ];

        $this->assertSame($output, self::getInt($foo, 'bar'));
    }

    /** @return array<array<mixed>> */
    public function getIntDataProvider(): array
    {
        return [
            // Valid values
            [true, 1],
            [false, 0],
            [0, 0],
            [1, 1],
            [123, 123],
            ['123', 123],

            // Invalid values
            [null, 0],
            [1.23, 0],
            ['a string', 0],
            [[], 0],
        ];
    }

    /** @dataProvider getIntOrNullDataProvider */
    public function testShouldGetIntOrNull(mixed $input, ?int $output): void
    {
        $foo = [
            'bar' => $input,
        ];

        $this->assertSame($output, self::getIntOrNull($foo, 'bar'));
    }

    /** @dataProvider getFloatDataProvider */
    public function testShouldGetFloat(mixed $input, float $output): void
    {
        $foo = [
            'bar' => $input,
        ];

        $this->assertSame($output, self::getFloat($foo, 'bar'));
    }

    /** @return array<array<mixed>> */
    public function getFloatDataProvider(): array
    {
        return [
            // Valid values
            [true, 1.0],
            [false, 0.0],
            [0, 0.0],
            [1, 1.0],
            [123, 123.0],
            ['123', 123.0],

            // Invalid values
            [null, 0.0],
            ['a string', 0.0],
            [[], 0.0],
        ];
    }

    /** @return array<array<mixed>> */
    public function getIntOrNullDataProvider(): array
    {
        return [
            // Valid values
            [true, 1],
            [false, 0],
            [0, 0],
            [1, 1],
            [123, 123],
            ['123', 123],

            // Invalid values
            [null, null],
            [1.23, null],
            ['a string', null],
            [[], null],
        ];
    }

    /** @dataProvider getFloatOrNullDataProvider */
    public function testShouldGetFloatOrNull(mixed $input, ?float $output): void
    {
        $foo = [
            'bar' => $input,
        ];

        $this->assertSame($output, self::getFloatOrNull($foo, 'bar'));
    }

    /** @return array<array<mixed>> */
    public function getFloatOrNullDataProvider(): array
    {
        return [
            // Valid values
            [true, 1.0],
            [false, 0.0],
            [0, 0.0],
            [1, 1.0],
            [123, 123.0],
            ['123', 123.0],

            // Invalid values
            [null, null],
            [1.23, 1.23],
            ['a string', null],
            [[], null],
        ];
    }

    public function testShouldGetIntegerOfArray(): void
    {
        $foo = [
            'bar' => 1,
        ];

        $this->assertSame(1, self::integerOfArray('bar', $foo));
        $this->assertSame(0, self::integerOfArray('undefined', $foo));
    }

    /** @dataProvider getStringDataProvider */
    public function testShouldGetString(mixed $input, string $output): void
    {
        $foo = [
            'bar' => $input,
        ];

        $this->assertSame($output, self::getString($foo, 'bar', 'default'));
    }

    /** @return array<array<mixed>> */
    public function getStringDataProvider(): array
    {
        return [
            // Valid values
            [true, 'true'],
            [false, 'false'],
            [123, '123'],
            [1.23, '1.23'],
            ['', ''],
            ['a string', 'a string'],

            // Invalid values
            [null, 'default'],
            [[], 'default'],
        ];
    }

    /** @dataProvider getNonEmptyStringOrNullDataProvider */
    public function testShouldGetNonEmptyStringOrNull(mixed $input, ?string $output): void
    {
        $foo = [
            'bar' => $input,
        ];

        $this->assertSame($output, self::getNonEmptyStringOrNull($foo, 'bar'));
    }

    public function testShouldGetId(): void
    {
        $id = AggregateIdMother::random()->id();
        $foo = [
            'id' => $id,
        ];

        $this->assertSame($id, self::getId($foo));
        $this->assertSame(36, strlen(self::getId([])));
    }

    /** @return array<array<mixed>> */
    public function getNonEmptyStringOrNullDataProvider(): array
    {
        return [
            // Valid values
            [true, 'true'],
            [false, 'false'],
            [123, '123'],
            [1.23, '1.23'],
            ['a string', 'a string'],

            // Invalid values
            [null, null],
            ['', null],
            [[], null],
        ];
    }

    /** @dataProvider getStringOrFailDataProvider */
    public function testShouldGetStringOrFail(mixed $input, ?string $output, bool $throwsException = false): void
    {
        $foo = [
            'bar' => $input,
        ];

        if ($throwsException) {
            $this->expectException(InvalidDataMappingException::class);
            $this->expectExceptionMessage('Expected array key "$foo[\'bar\']" to have a scalar value.');
        }

        $actual = self::getStringOrFail($foo, 'bar', '$foo');

        $this->assertSame($output, $actual);
    }

    /** @return array<array<mixed>> */
    public function getStringOrFailDataProvider(): array
    {
        return [
            // Valid values
            [true, 'true'],
            [false, 'false'],
            [123, '123'],
            [1.23, '1.23'],
            ['', ''],
            ['a string', 'a string'],

            // Invalid values
            [null, null, true],
            [[], null, true],
        ];
    }

    public function testShouldThrowAnExceptionWhenMissingKeyOnGetStringOrFail(): void
    {
        $foo = [];

        $this->expectException(InvalidDataMappingException::class);
        $this->expectExceptionMessage('Expected array "$foo" to have a key "bar".');

        self::getStringOrFail($foo, 'bar', '$foo');
    }
}
