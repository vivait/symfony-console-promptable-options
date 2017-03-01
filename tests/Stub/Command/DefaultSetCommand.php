<?php

namespace Vivait\PromptableOptions\Tests\Stub\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Vivait\PromptableOptions\Command\PromptableOptionsTrait;

class DefaultSetCommand extends Command
{

    use PromptableOptionsTrait;

    protected function configure()
    {
        $this
            ->setName('promptable:test:default')
            ->addPrompt(
                'input',
                [
                    'type'        => 'string',
                    'required'    => false,
                    'description' => 'Your input',
                    'default'     => 'My default value'
                ]
            )
            ->setDescription('To test promptable option default values');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->setUpPrompt($input, $output);

        $inputOption = $this->getConsoleOptionInput('input');
        $output->writeln(sprintf("Type input: %s", gettype($inputOption)));
        $output->writeln(sprintf("Input: %s", $inputOption));
    }
}
