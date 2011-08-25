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
            ->setHelp(<<<EOT
The <info>%command.name%</info> command install application:

    <info>%command.full_name%</info>
EOT
        );

    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Comming soon');
    }
}