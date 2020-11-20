<?php

namespace Jakmall\Recruitment\Calculator\History;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class HistoryListCommand extends Command
{
    protected static $defaultName = 'history:list';
    public function __construct()
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Show calculator history')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        echo "Executing: " . HistoryListCommand::$defaultName . PHP_EOL;
    }
}
