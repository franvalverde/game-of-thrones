<?php

declare(strict_types=1);

namespace Whalar\Tests\Shared\Shared\Infrastructure\Symfony;

use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class MockHttpClientFactory
{
    private static ?MockHttpClient $instance = null;

    public static function create(): HttpClientInterface
    {
        if (self::$instance === null) {
            self::$instance = new MockHttpClient();
        }

        return self::$instance;
    }
}
