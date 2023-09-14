<?php

declare(strict_types=1);

namespace Whalar\Tests\Shared\Shared\Domain\ValueObject;

use Faker\Factory;
use Faker\Generator;
use Faker\Provider\es_ES\Person;

final class MotherCreator
{
    private static $faker;

    public static function random(): Generator
    {
        if (null === self::$faker) {
            self::$faker = Factory::create();
            self::$faker->addProvider(new Person(self::$faker));
        }

        return self::$faker;
    }
}
