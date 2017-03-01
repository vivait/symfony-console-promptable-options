<?php

namespace Vivait\PromptableOptions\Tests\Stub\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Vivait\PromptableOptions\Command\PromptableOptionsTrait;

class NotRequiredCommand extends Command
{

    use PromptableOptionsTrait;

    protected function configure()
    {
        $this
            ->setName('promptable:test:not-required')
            ->addPrompt('name', ['type' => 'bool', 'required' => false, 'description' => 'Your name'])
            ->setDescription('To test if options are required');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->setUpPrompt($input, $output);

        $name = $this->getConsoleOptionInput('name');
        $output->writeln(sprintf("Type name: %s", gettype($name)));
        $output->writeln(sprintf("Length name: %s", strlen($name)));
    }
}
