<?php

namespace Vivait\PromptableOptions\Tests\Stub\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Vivait\PromptableOptions\Command\PromptableOptionsTrait;

class PromptableCommand extends Command
{

    use PromptableOptionsTrait;

    protected function configure()
    {
        $this
            ->setName('promptable:test')
            ->addPrompt('name', ['required' => true, 'description' => 'Your name'])
            ->addPrompt('age', ['type' => 'int', 'required' => true, 'description' => 'Your age'])
            ->addPrompt('occupation', ['required' => true, 'description' => 'Your occupation'])
            ->setDescription('To test the use of promptable input options');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->setUpPrompt($input, $output);

        $name = $this->getConsoleOptionInput('name');

        $output->writeln(sprintf('Hello %s!', $name));

        $age = $this->getConsoleOptionInput('age');

        if ($age) {
            $output->writeln(sprintf('You are %s years old', $age));
        }
    }
}
