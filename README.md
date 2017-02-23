# Promptable Options for Symfony Console

[![Build Status](https://travis-ci.org/vivait/symfony-console-promptable-options.svg?branch=master)](https://travis-ci.org/vivait/symfony-console-promptable-options)

## Compatibility / Requirements

* PHP 5.4 and above
* symfony/console ^2.8|^3.0

## Installation

`composer require vivait/symfony-console-promptable-options`

## Usage

Configure the promptable options in the `configure()` method of your command.

You can call the `$this->addPrompt(string $optionName)` fluently with the other options to add a prompt for a single option.

Alternatively, call `$this->addPrompts(array $optionNames)` fluently with the other options to add prompts for multiple options at the same time. This does not over-write any previously added prompts, it adds them on to the options.

Once configured, access options using  `$this->getConsoleOptionInput(string $optionName)`.

The table below shows how it acts in various situations:

|   | **Option marked as promptable** | **Option not promptable** |
|---|---|---|
| **Option supplied via** `--optionName=value` | The value of `--optionName=value` will be returned  | The value of `--optionName=value` will be returned |
| **Option not supplied via**  `--optionName=value` | The option will be asked for via an interactive question | `null` will be returned |

#### Non-interactive commands:

If you run a command that has promptable options **that are not supplied via** `--optionName=value` then an `\Exception` will be thrown with the message: `"Cannot prompt for optionName, command is running in non-interactive mode"`

#### Example command:

```php
<?php

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Vivait\PromptableOptions\Command\PromptableOptionsTrait;

class PromptableCommand extends Command
{

    use PromptableOptionsTrait;

    protected function configure()
    {
        $this
            ->setName('promptable:demo')
            ->addOption('name', null, InputOption::VALUE_REQUIRED, 'Your name')
            ->addPrompt('name') // add a single thing to be prompted
            ->addOption('age', null, InputOption::VALUE_REQUIRED, 'Your age')
            ->addOption('occupation', null, InputOption::VALUE_REQUIRED, 'Your occupation')
            ->addPrompt('occupation') // add a single thing to be prompted
            ->setDescription('To demonstrate the use of promptable input options');
        
        // alternatively:
        
        $this
            ->setName('promptable:demo')
            ->addOption('name', null, InputOption::VALUE_REQUIRED, 'Your name')
            ->addOption('age', null, InputOption::VALUE_REQUIRED, 'Your age')
            ->addOption('occupation', null, InputOption::VALUE_REQUIRED, 'Your occupation')
            ->addPrompts(['name', 'occupation']) // add multiple things to be prompted
            ->setDescription('To demonstrate the use of promptable input options');
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
        
        $occupation = $this->getConsoleOptionInput('occupation');
        $output->writeln(sprintf('You are a %s!', $occupation));
    }
}
```

## Limitations

This has only been tested with string options set to `InputOption::VALUE_REQUIRED` - any other options are (currently) unsupported.

## Contributing

This started as a project internally that we used on some of our projects, if there's new features / ideas you think could be useful please feel free to suggest them, or submit a PR!

Although this project is small, openness and inclusivity are taken seriously. To that end the following code of conduct has been adopted.

[Contributor Code of Conduct](CONTRIBUTING.md)
