#!/usr/bin/env php
<?php

use Illuminate\Console\Application;
use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;

try {
    require_once __DIR__.'/vendor/autoload.php';

    $appVersion = '0.5';
    $container = new Container();
    $dispatcher = new Dispatcher();
    $app = new Application($container, $dispatcher, $appVersion);
    $app->setName('Calculator');

    $commands = require_once __DIR__.'/commands.php';
    $commands = collect($commands)
        ->map(
            function ($command) use ($app) {
                return $app->getLaravel()->make($command);
            }
        )
        ->all()
    ;

    $app->addCommands($commands);

    $app->run(new ArgvInput(), new ConsoleOutput());
} catch (Throwable $e) {
	fwrite(STDERR, $e . PHP_EOL);
}
