<?php

use Behat\Behat\Context\Context;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class FeatureContext implements Context
{

    /**
     * @var Process
     */
    private $process;

    /**
     * @var string
     */
    private $output;

    /**
     * @var int
     */
    private $exitCode;

    /**
     * @var string
     */
    private $errorOutput;

    /**
     * @When I run the PromptableCommand and input :input
     * @When I run the PromptableCommand with the option(s) :option
     * @When I run the PromptableCommand with the option(s) :option and input :input
     * @When I run the PromptableCommand in :mode mode
     *
     * @param string $input
     * @param string $option
     * @param string $mode
     */
    public function iRunThePromptableCommandWithTheOption($input = '', $option = '', $mode = '')
    {
        $this->process = new Process(sprintf('php console.php promptable:test %s', $option));

        $interactiveMode = true;

        if($mode === 'non-interactive') {
            $interactiveMode = false;
        }

        $env = ['SHELL_INTERACTIVE' => $interactiveMode];
        $this->process->setEnv($env);

        $this->process->setInput($input);

        $this->runProcess();
    }

    /**
     * @Then I should see :text
     *
     * @param $text
     *
     * @throws Exception
     */
    public function iShouldSee($text)
    {
        if (false === strpos($this->output . $this->errorOutput, $text)) {
            throw new \Exception(sprintf('Could not find %s in the console output', $text));
        }
    }

    /**
     * @Then I should not see :text
     *
     * @param $text
     *
     * @throws Exception
     */
    public function iShouldNotSee($text)
    {
        if (false !== strpos($this->output . $this->errorOutput, $text)) {
            throw new \Exception(sprintf('The text %s was in the console output and was not expected to be', $text));
        }
    }

    protected function runProcess()
    {
        try {
            $this->process->mustRun();
            $this->output = $this->process->getOutput();
            $this->exitCode = $this->process->getExitCode();
            $this->errorOutput = $this->process->getErrorOutput();
        } catch (ProcessFailedException $e) {
            $process = $e->getProcess();
            $this->exitCode = $process->getExitCode();
            $this->output = $process->getOutput();
            $this->errorOutput = $process->getErrorOutput();
        }
    }

    /**
     * @Then the command should not have completed successfully
     */
    public function theCommandShouldNotHaveCompletedSuccessfully()
    {
        if ($this->process->getExitCode() === 0) {
            throw new \Exception('Process exited with a zero status code');
        }
    }
    /**
     * @Then the command should have completed successfully
     */
    public function theCommandShouldHaveCompletedSuccessfully()
    {
        if ($this->process->getExitCode() !== 0) {
            throw new \Exception('Process exited with a non-zero status code');
        }
    }
}
