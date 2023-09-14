<?php

namespace Whalar\Tests\Shared\Core\Infrastructure\Behat\Context;

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    private string $directory;

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

    /**
     * @Given I am in a directory :arg1
     */
    public function iAmInADirectory($directoryName)
    {
        $this->directory = __DIR__ . '/../../tmp/' . $directoryName;
        if (file_exists($this->directory)) {
            exec("rm -r {$this->directory}");
        }
        mkdir($this->directory, recursive: true);
        chdir($this->directory);
    }

    /**
     * @Given I have a file named :arg1
     */
    public function iHaveAFileNamed($filename)
    {
        touch($filename);
    }

    private string $output;

    /**
     * @When I run :arg1
     */
    public function iRun($commandName)
    {
        exec("$commandName", $output);
        // PHP store array lines in an array
        // we need to make it a string to compare
        $this->output = implode("\n", $output);
    }

    /**
     * @Then I should get:
     */
    public function iShouldGet(PyStringNode $expectedOutput)
    {
        assert($this->output === $expectedOutput->getRaw());
    }
}
