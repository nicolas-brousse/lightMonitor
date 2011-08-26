#!/usr/bin/env php
<?php
# http://beryllium.ca/?p=481
require_once __DIR__ . '/../vendor/App.php';

$app = App::getInstance();
$app['autoloader']->registerNamespace('Symfony', __DIR__ . '/../vendor');
$app['autoloader']->registerNamespace('Console', __DIR__);


//Include the namespaces of the components we plan to use
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;

//Instantiate our Console application
$cli = new Application(App::NAME, App::VERSION);

$helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
    'dialog' => new \Symfony\Component\Console\Helper\DialogHelper(),
    'formatter' => new \Symfony\Component\Console\Helper\FormatterHelper(),
));

$cli->setCatchExceptions(true);
$cli->setHelperSet($helperSet);
$cli->addCommands(array(
   new \Console\Command\InstallCommand(),
   new \Console\Command\UpgradeCommand(),
   new \Console\Command\ScriptCommand(),
));

$input = null; #new InputInterface();
$output = new ConsoleOutput();
$logo = <<<EOT
<question> +-------------------------------------------------------+ </question>
<question> |     _ _      _   _   __  __          _ _              | </question>
<question> |    | (_)__ _| |_| |_|  \/  |___ _ _ (_) |_ ___ _ _    | </question>
<question> |    | | / _` | ' \  _| |\/| / _ \ ' \| |  _/ _ \ '_|   | </question>
<question> |    |_|_\__, |_||_\__|_|  |_\___/_||_|_|\__\___/_|     | </question>
<question> |        |___/  _______________________________________ | </question>
<question> |                                                       | </question>
<question> +-------------------------------------------------------+ </question>

EOT
;
//var_dump(explode('magic_character',$logo)); exit(0);

/*foreach (str_split($logo) as $char) {
  //usleep(30);
  $output->write($char);
}*/
//sleep(1);

$output->writeln($logo);

$cli->run($input, $output);
