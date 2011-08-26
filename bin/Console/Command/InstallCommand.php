<?php

namespace Console\Command;

use Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface,
    Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console\Command\Command;

class InstallCommand extends Command
{
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('app:install')
            ->setDescription('Install application')
            ->setHelp(
<<<EOT
The <info>%command.name%</info> command install application:

    <info>%command.full_name%</info>
EOT
);

    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        # Check if application is installed or not
        $app_installed = true;
        if ($app_installed !== false) {
          throw new \Exception("Operation cancelled\nThis application is already installed!");
        }

        # Check configurations
        foreach ($this->_checkConf() as $k=>$v) {
          $output->writeln("\n <info>==</info> ".$k."");
          foreach ($v as $name => $value) {
            $output->writeln('    <comment>>></comment> ' . $name . ': ' . str_repeat(' ', 50 - strlen($name)) . $value);
          }
        }

        if ($this->_checkConf(true) == false) {
          throw new \Exception("Operation cancelled\nYou can't install this application, because you haven't softwares necessaries");
        }

        $confirmation = $this->getHelper('dialog')->askConfirmation($output, "\n<question>Continue? (y/n)</question>\n", 'y');
        if ($confirmation === true) {
          $tmp = false;
          $user = array();

          # Set username
          while ($tmp!==true) {
            $user['username'] = $this->getHelper('dialog')->ask($output, "<question>What is your <info>username</info> ?</question>\n");
            if (!empty($user['username'])) {
              $tmp = $this->getHelper('dialog')->askConfirmation($output, "<question>You have set <info>".$user['username']."</info></question><question>. Confirm ? (y/n)</question>\n", false);
            }
          }
          $tmp=false;

          # Set password
          while ($tmp!==true) {
            $user['password'] = $this->getHelper('dialog')->ask($output, "<question>Set your <info>password</info></question>\n");
            $pass_confirm = $this->getHelper('dialog')->ask($output, "<question>Confirm your <info>password</info></question>\n");
            if ($user['password'] !== $pass_confirm) {
              $output->writeln("<error>Password confirmation is not ok, retry</error>\n");
            }
            else {
              $tmp=true;
            }
          }
          $tmp=false;

          # Set email ?

          # 

          $output->writeln(var_export($user, true));

          $output->writeln("Comming soon\n");
        } else {
          $output->writeln("<error>Operation cancelled</error>\n");
        }
    }

    private function _checkConf($bool=false)
    {
      if (!defined('PHP_VERSION_ID')) {
         $version = explode('.',PHP_VERSION);

         define('PHP_VERSION_ID', ($version[0] * 10000 + $version[1] * 100 + $version[2]));
      }

      if ($bool==false) {
        return array(
          'Check Softwares' => array(
            'Version' => (PHP_VERSION_ID < 50300 ? '[<error>NONE</error>] ' : '[OK] ') . phpversion(),
            'Sqlite3' => '[OK]',
          ),
          'Check Extentions' => array(
            'SSH'                   => function_exists('ssh2_connect') ? '[OK]' : '[<error>NONE</error>]',
            'SNMP'                  => class_exists('SNMP') ? '[OK]' : '[<error>NONE</error>]',
            'Curl'                  => function_exists('curl_init') ? '[OK]' : '[<error>NONE</error>]',
            'RRDTOOL'               => function_exists('rrd_graph') && function_exists('rrd_create') && function_exists('rrd_update') ? '[OK]' : '[<error>NONE</error>]',
          ),
          'Check Environment' => array(
            'Database directory'    => 'todo',
            'graphs directory'      => 'todo',
            'rrdtool directory'     => 'todo',
            'Logs directory'        => 'todo',
          ),
        );
      }
      else {
        return true;
      }
    }
}