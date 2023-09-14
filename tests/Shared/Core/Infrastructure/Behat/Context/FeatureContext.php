<?php

declare(strict_types=1);

namespace Whalar\Tests\Shared\Core\Infrastructure\Behat\Context;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    private string $directory;

    private string $output;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /** @Given I am in a directory :arg1 */
    public function iAmInADirectory($directoryName): void
    {
        $this->directory = __DIR__.'/../../tmp/'.$directoryName;

        if (file_exists($this->directory)) {
            exec("rm -r {$this->directory}");
        }

        mkdir($this->directory, recursive: true);
        chdir($this->directory);
    }

    /** @Given I have a file named :arg1 */
    public function iHaveAFileNamed($filename): void
    {
        touch($filename);
    }

    /** @When I run :arg1 */
    public function iRun($commandName): void
    {
        exec("$commandName", $output);
        // PHP store array lines in an array
        // we need to make it a string to compare
        $this->output = implode("\n", $output);
    }

    /** @Then I should get: */
    public function iShouldGet(PyStringNode $expectedOutput): void
    {
        assert($this->output === $expectedOutput->getRaw());
    }
}
