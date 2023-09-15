<?php

declare(strict_types=1);

namespace Whalar\Tests\Shared\Shared\Infrastructure\Behat\Mink;

use Behat\Gherkin\Node\PyStringNode;

final class MinkRequestHelper
{
    public function __construct(private readonly MinkHelper $mink)
    {
    }

    // @phpstan-ignore-next-line
    public function sendRequest($method, $url, array $optionalParams = []): void
    {
        $this->mink->sendRequest($method, $url, $optionalParams);
    }

    // @phpstan-ignore-next-line
    public function sendRequestWithPyStringNode($method, $url, PyStringNode $body): void
    {
        $this->mink->sendRequest($method, $url, ['content' => $body->getRaw()]);
    }
}
