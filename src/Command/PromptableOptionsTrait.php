<?php

namespace Vivait\PromptableOptions\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * This trait should only be used on a Symfony command class
 *
 * @mixin Command
 */
trait PromptableOptionsTrait
{

    /**
     * @var SymfonyStyle
     */
    private $consoleIo;

    /**
     * @var InputInterface
     */
    private $consoleInput;

    /**
     * @var OutputInterface
     */
    private $consoleOutput;

    /**
     * @var array
     */
    private $consolePrompts = [];

    /**
     * @param string $optionName
     *
     * @return string
     * @throws \Exception
     */
    protected function getConsoleOptionInput($optionName)
    {
        $value = $this->consoleInput->getOption($optionName);

        if ($value) {
            return $value;
        }

        $optionIsPromptable = in_array($optionName, $this->consolePrompts);

        if ( ! $optionIsPromptable) {
            return null;
        }

        $description = $this->getQuestionText($optionName);

        if ( ! $this->consoleInput->isInteractive()) {
            throw new \Exception(
                sprintf('Cannot prompt for %s, command is running in non-interactive mode.', $optionName)
            );
        }

        /** @var QuestionHelper $question */
        $value = $this->consoleIo->ask($description);

        return $value;
    }

    /**
     * @param string $optionName
     *
     * @return self
     */
    protected function addPrompt($optionName)
    {
        $this->consolePrompts[] = $optionName;

        return $this;
    }

    /**
     * @param array $optionNames
     *
     * @return self
     */
    protected function addPrompts(array $optionNames = [])
    {
        $this->consolePrompts = array_unique(array_merge($this->consolePrompts, $optionNames));

        return $this;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function setUpPrompt(InputInterface $input, OutputInterface $output)
    {
        $this->consoleIo = new SymfonyStyle($input, $output);
        $this->consoleInput = $input;
        $this->consoleOutput = $output;
    }

    /**
     * @param $optionName
     *
     * @return string
     */
    protected function getQuestionText($optionName)
    {
        $description = $this->getDefinition()->getOption($optionName)->getDescription();

        if ($description === null || trim($description) === '') {
            $description = ucfirst($optionName);
        }

        return $description;
    }
}
