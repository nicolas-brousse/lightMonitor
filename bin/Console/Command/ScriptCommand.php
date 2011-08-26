<?php

namespace Console\Command;

use Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface,
    Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console\Command\Command;

class ScriptCommand extends Command
{
    private $envList = array('development', 'production');

    protected function configure()
    {
      parent::configure();

      $this->setName('app:script')
           ->setDescription('Execute a script')
           ->setDefinition( array(
               new InputArgument('filename', InputArgument::REQUIRED, 'Filename of script must be executed (scripts in \'script/\' folder)'),
               new InputOption('--env', '-e', InputOption::VALUE_REQUIRED, 'Environment ('.implode(' or ', $this->envList).')', 'development'),
              ) )
           ->setHelp(<<<EOT
The <info>%command.name%</info> command execute a script:

    <info>%command.full_name% filename</info>

You can optionally specify a <comment>-e|--env</comment> option to precise an environment:

    <info>%command.full_name% --env|-e=(production|development) filename</info>
EOT
);

    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $filename = $input->getArgument('filename');
        $env = $input->getOption('env');

        if (!file_exists(__DIR__ . '/../../scripts/'.$filename)) {
          throw new \InvalidArgumentException("File '$filename' doesn't exist");
        }
        else if (!in_array($env, $this->envList)) {
          throw new \RuntimeException("Environment '$env' is not valid (".implode(' or ', $this->envList).")");
        }
        else {
          $start = microtime(true);
          /**
           * Bootstraping
           */
          define('APPLICATION_ENV', $env);
          define('CLI_FILENAME', $filename);

          $output->writeln("--- Start execution of '$filename' ---");
          $tmp = include __DIR__ . '/../../scripts/'.$filename;
          $output->writeln($tmp);
          $output->writeln("--- End of execution - Execute in " . (microtime(true) - APPLICATION_MICROTIME_START) . " secondes ---");
        }
    }
}