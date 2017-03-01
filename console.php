#!/usr/bin/env php
<?php

require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Application;
use Vivait\PromptableOptions\Tests\Stub\Command\DefaultSetCommand;
use Vivait\PromptableOptions\Tests\Stub\Command\IntegerCommand;
use Vivait\PromptableOptions\Tests\Stub\Command\NotRequiredCommand;
use Vivait\PromptableOptions\Tests\Stub\Command\PromptableCommand;

$application = new Application();

$application->add(new PromptableCommand);
$application->add(new IntegerCommand);
$application->add(new NotRequiredCommand);
$application->add(new DefaultSetCommand);

$application->run();
