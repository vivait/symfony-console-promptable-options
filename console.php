#!/usr/bin/env php
<?php

require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Application;
use Vivait\PromptableOptions\Tests\Stub\Command\PromptableCommand;

$application = new Application();

$application->add(new PromptableCommand);

$application->run();
