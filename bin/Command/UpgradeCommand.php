<?php

namespace Console\Command;

use Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface,
    Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputOption;

class UpgradeCommand extends GenerateCommand
{
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('app:install')
            ->setDescription('Generate a migration by comparing your current database to your mapping information.')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command generates a migration by comparing your current database to your mapping information:

    <info>%command.full_name%</info>

You can optionally specify a <comment>--editor-cmd</comment> option to open the generated file in your favorite editor:

    <info>%command.full_name% --editor-cmd=mate</info>
EOT
        );

    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $version = date('YmdHis');

        $output->writeln(sprintf('Generated new migration class to "<info>%s</info>" from schema differences.', $version));
    }
}