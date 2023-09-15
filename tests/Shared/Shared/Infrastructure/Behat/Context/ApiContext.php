<?php

declare(strict_types=1);

namespace Whalar\Tests\Shared\Shared\Infrastructure\Behat\Context;

use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\RawMinkContext;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Whalar\Tests\Shared\Shared\Infrastructure\Behat\Assert\Assertions;
use Whalar\Tests\Shared\Shared\Infrastructure\Behat\Json\Json;
use Whalar\Tests\Shared\Shared\Infrastructure\Behat\Json\JsonInspector;
use Whalar\Tests\Shared\Shared\Infrastructure\Behat\Mink\MinkHelper;
use Whalar\Tests\Shared\Shared\Infrastructure\Behat\Mink\MinkRequestHelper;
use Whalar\Tests\Shared\Shared\Infrastructure\Behat\Utils;

final class ApiContext extends RawMinkContext
{
    use Assertions;

    private readonly MinkRequestHelper $minkRequest;

    public function __construct(private readonly MinkHelper $mink, private readonly JsonInspector $jsonInspector)
    {
        $this->minkRequest = new MinkRequestHelper($mink);
    }

    /**
     * Add a header element in a request.
     *
     * @When /^I add a(?:n)? "([^"]*)" header equal to "([^"]*)"$/
     */
    public function iAddAHeaderEqualTo(string $name, string|int $value): void
    {
        $this->mink->setHttpHeader($name, $value);
    }

    /** @When I send a :method request to :url */
    public function iSendARequestTo(string $method, string $url): void
    {
        $this->minkRequest->sendRequest($method, $this->locatePath($url));
    }

    /** @When I send a :method request to :url with body: */
    public function iSendARequestToWithBody(string $method, string $url, PyStringNode $body): void
    {
        $this->minkRequest->sendRequestWithPyStringNode($method, $this->locatePath($url), $body);
    }

    /** @When I send a :method request to :url with files: */
    public function iSendARequestToWithFiles(string $method, string $url, TableNode $data): void
    {
        $files = [];
        $parameters = [];

        foreach ($data->getHash() as $row) {
            $key = $row['key'];
            $value = $row['value'];

            if (!isset($key) || !isset($value)) {
                throw new \RuntimeException("You must provide a 'key' and 'value' column in your table node.");
            }

            if (\is_string($value) && str_starts_with($value, '@')) {
                $filename = substr($value, 1);
                \assert(
                    \is_string($this->getMinkParameter('files_path')),
                    'Mink parameter "files_path" is not a string.',
                );
                $path = rtrim(
                        $this->getMinkParameter('files_path'),
                        \DIRECTORY_SEPARATOR,
                    ).\DIRECTORY_SEPARATOR.$filename;
                Arr::set($files, $key, new UploadedFile($path, $filename));
            } else {
                Arr::set($parameters, $key, $value);
            }
        }

        $this->minkRequest->sendRequest($method, $this->locatePath($url), [
            'parameters' => $parameters,
            'files' => $files,
            'server' => [],
        ]);
    }

    /** @Then /^the response status code should be (\d+)$/ */
    public function theResponseStatusCodeShouldBe(int $expected): void
    {
        $actual = $this->mink->getResponseStatusCode();

        if ($actual !== $expected) {
            $this->printLastResponse();

            throw new \RuntimeException(
                sprintf(
                    'The status code %s does not match the expected %s.',
                    Utils::stringify($actual),
                    Utils::stringify($expected),
                ),
            );
        }
    }

    /** @Then /^the response should be:$/ */
    public function theResponseShouldBe(TableNode $nodes): void
    {
        $expected = $nodes->getRowsHash();
        $actual = $this->getJsonResponse();

        $this->assertArrayEqualsJson($expected, $actual);
    }

    /** @Then /^the response should be empty$/ */
    public function theResponseShouldBeEmpty(): void
    {
        $response = trim($this->mink->getResponse());

        if ($response !== '' && $response !== '{}') {
            throw new \RuntimeException("The response is not empty.\n\n-- Actual:\n$response");
        }
    }

    /** @Then /^the response node (.*) should be (.*)$/ */
    public function theResponseNodeShouldBe(string $node, mixed $expected): void
    {
        $actual = $this->jsonInspector->evaluate($this->getJsonResponse(), $node);

        $this->assertEquals(
            $expected,
            $actual,
            sprintf(
                "The response node %s with value %s doesn't match the expected.",
                Utils::stringify($node),
                Utils::stringify($actual),
            ),
        );
    }

    /** @Then /^the response node (.*) should not be null$/ */
    public function theResponseNodeShouldNotBeNull(string $node): void
    {
        $response = $this->getJsonResponse();

        $actual = $this->jsonInspector->evaluate($response, $node);

        if ($actual === null) {
            throw new \RuntimeException("The response node <$node> is null.");
        }
    }

    /** @Then /^the response node (.*) should have (\d+) element(?:s)?$/ */
    public function theResponseNodeShouldHaveElements(string $node, int $expected): void
    {
        $response = $this->getJsonResponse();

        $actual = \count((array) $this->jsonInspector->evaluate($response, $node));

        if ($expected !== $actual) {
            throw new \RuntimeException("The response node <$node> has <$actual> elements.");
        }
    }

    /** @Then /^the response headers should be:$/ */
    public function theResponseHeadersShouldBe(TableNode $nodes): void
    {
        $expected = $nodes->getRowsHash();
        $actual = $this->mink->getResponseHeaders();

        $this->assertArrayEqualsArray($expected, $actual);
    }

    /** @Then /^the response header node (.*) should be (.*)$/ */
    public function theResponseHeaderNodeShouldBe(string $header, $expected): void
    {
        $actual = $this->mink->getResponseHeaders();

        $this->assertEquals(
            $expected,
            $actual,
            sprintf(
                "The response header %s and value %s doesn't match the expected.",
                Utils::stringify($header),
                Utils::stringify($actual),
            ),
        );
    }

    /** @Then /^print last response$/ */
    public function printLastResponse(): void
    {
        if ($this->mink->getResponse() === '') {
            print_r('The response is empty.');

            return;
        }

        print_r(json_decode($this->mink->getResponse(), null, 512, \JSON_THROW_ON_ERROR));
    }

    /** @Then /^print last response headers$/ */
    public function printLastResponseHeaders(): void
    {
        print_r($this->mink->getResponseHeaders());
    }

    /** @Given /^I wait (\d+) second(?:s)?$/ */
    public function iWaitSeconds(int $seconds): void
    {
        sleep($seconds);
    }

    protected function jsonInspector(): JsonInspector
    {
        return $this->jsonInspector;
    }

    private function getJsonResponse(): Json
    {
        return Json::fromString($this->mink->getResponse());
    }
}
