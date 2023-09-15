<?php

declare(strict_types=1);

namespace Whalar\Tests\Shared\Shared\Infrastructure\Behat\Mink;

use Behat\Mink\Driver\DriverInterface;
use Behat\Mink\Session;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\DomCrawler\Crawler;

final class MinkHelper
{
    private array $requestHeaders = [];

    public function __construct(private readonly Session $session)
    {
    }

    /**
     * Taken from Behat\Mink\Driver\BrowserKitDriver::setRequestHeader.
     */
    public function setHttpHeader(string $name, string|int $value): void
    {
        $contentHeaders = ['CONTENT_LENGTH' => true, 'CONTENT_MD5' => true, 'CONTENT_TYPE' => true];
        $name = str_replace('-', '_', strtoupper($name));

        // CONTENT_* are not prefixed with HTTP_ in PHP when building $_SERVER
        if (!isset($contentHeaders[$name])) {
            $name = 'HTTP_'.$name;
        }

        $this->requestHeaders[$name] = $value;
    }

    // @phpstan-ignore-next-line
    public function sendRequest($method, $url, array $optionalParams = []): Crawler
    {
        $defaultOptionalParams = [
            'parameters' => [],
            'files' => [],
            'server' => ['HTTP_ACCEPT' => 'application/json', 'CONTENT_TYPE' => 'application/json'],
            'content' => null,
            'changeHistory' => true,
        ];

        $optionalParams = array_merge($defaultOptionalParams, $optionalParams);
        $optionalParams['server'] = array_merge($optionalParams['server'], $this->requestHeaders);

        $crawler = $this->getClient()->request(
            $method,
            $url,
            $optionalParams['parameters'],
            $optionalParams['files'],
            $optionalParams['server'],
            $optionalParams['content'],
            $optionalParams['changeHistory'],
        );

        $this->resetServerParameters();

        return $crawler;
    }

    public function getRequest(): object
    {
        return $this->getClient()->getRequest();
    }

    public function getResponse(): string
    {
        return $this->getSession()->getPage()->getContent();
    }

    public function getResponseHeaders(): array
    {
        return $this->normalizeHeaders(
            array_change_key_case($this->getSession()->getResponseHeaders(), \CASE_LOWER),
        );
    }

    public function getResponseStatusCode(): int
    {
        return $this->getSession()->getStatusCode();
    }

    private function getSession(): Session
    {
        return $this->session;
    }

    private function getDriver(): DriverInterface
    {
        return $this->getSession()->getDriver();
    }

    private function getClient(): AbstractBrowser
    {
        return $this->getDriver()->getClient();
    }

    private function normalizeHeaders(array $headers): array
    {
        return array_map('implode', array_filter($headers));
    }

    private function resetServerParameters(): void
    {
        $this->requestHeaders = [];
        $this->getClient()->setServerParameters([]);
    }
}
