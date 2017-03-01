<?php

namespace Vivait\PromptableOptions\Tests\Stub\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Vivait\PromptableOptions\Command\PromptableOptionsTrait;

class IntegerCommand extends Command
{

    use PromptableOptionsTrait;

    protected function configure()
    {
        $this
            ->setName('promptable:test:integer')
            ->addPrompt('age', ['type' => 'int', 'required' => true, 'description' => 'Your age'])
            ->setDescription('To test promptable option types');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->setUpPrompt($input, $output);
        
        $age = $this->getConsoleOptionInput('age');
        $output->writeln(sprintf("Type age: %s", gettype($age)));
    }
}
