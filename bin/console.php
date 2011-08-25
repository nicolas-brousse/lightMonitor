#!/usr/bin/env php
<?php
# http://beryllium.ca/?p=481
require_once __DIR__ . '/../vendor/silex.phar';

$loader->register();

$app = new Silex\Application();
$app['autoloader']->registerNamespace('Symfony', __DIR__ . '/../vendor');

# require command
require_once __DIR__ . '/Command/ScriptCommand.php';

//Include the namespaces of the components we plan to use
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;;
use Symfony\Component\Console\Output\OutputInterface;

//Instantiate our Console application
$cli = new Application('LightMonitor', '0.1');


$cli->setCatchExceptions(true);
$cli->addCommands(array(

//     new \Console\Command\InstallCommand(),
//     new \Console\Command\UpgradeCommand(),
     new \Console\Command\ScriptCommand(),
));


$input = file_exists('migrations-input.php')
       ? include('migrations-input.php')
       : null;

$output = file_exists('migrations-output.php')
        ? include('migrations-output.php')
        : null;

$cli->run($input, $output);