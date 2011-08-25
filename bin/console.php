#!/usr/bin/env php
<?php
# http://beryllium.ca/?p=481
require_once __DIR__ . '/../vendor/silex.phar';
require_once 'phar://'.__DIR__ .'/../vendor/doctrine-migrations.phar/Doctrine/Common/ClassLoader.php';

$loader->register();

$app = new Silex\Application();
$app['autoloader']->registerNamespace('Symfony', __DIR__ . '/../vendor');

$classLoader = new \Doctrine\Common\ClassLoader('Doctrine\Common', 'phar://'.__FILE__);
$classLoader->register();

$classLoader = new \Doctrine\Common\ClassLoader('Doctrine\DBAL', 'phar://'.__FILE__);
$classLoader->register();

$helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
  'dialog' => new \Symfony\Component\Console\Helper\DialogHelper(),
));

//Include the namespaces of the components we plan to use
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

//Instantiate our Console application
$cli = new Application('LightMonitor', '0.1');


$cli->setCatchExceptions(true);
$cli->setHelperSet($helperSet);
$cli->addCommands(array(
    // Migrations Commands
//     new \Doctrine\DBAL\Migrations\Tools\Console\Command\DiffCommand(),
//     new \Doctrine\DBAL\Migrations\Tools\Console\Command\ExecuteCommand(),
//     new \Doctrine\DBAL\Migrations\Tools\Console\Command\GenerateCommand(),
//     new \Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand(),
//     new \Doctrine\DBAL\Migrations\Tools\Console\Command\StatusCommand(),
//     new \Doctrine\DBAL\Migrations\Tools\Console\Command\VersionCommand(),

//     new \Console\Command\InstallCommand(),
//     new \Console\Command\UpgradeCommand(),
));

$cli->register('install')
  ->setDefinition( array(
     //Create a "--test" optional parameter
     new InputOption('test', '', InputOption::VALUE_NONE, 'Test mode'),
    ) )
  ->setDescription('Install application')
  ->setHelp('Usage: <info>./console.php sync [--test]</info>')
  ->setCode(
    function(InputInterface $input, OutputInterface $output) use ($app)
    {
      if ($input->getOption('test'))
      {
        $output->write("\n\tTest Mode Enabled\n\n");
      }

      $output->write( "Contacting external data source ...\n");
      //Do work here
      //Example:
      //  $app[ 'myExtension' ]->doStuff();
    }
  );
$cli->register('upgrade')->setDescription('Upgrade application');


$input = file_exists('migrations-input.php')
       ? include('migrations-input.php')
       : null;

$output = file_exists('migrations-output.php')
        ? include('migrations-output.php')
        : null;

$cli->run($input, $output);