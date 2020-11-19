<?php

namespace Jakmall\Recruitment\Calculator\History;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class HistoryClearCommand extends Command
{
    protected static $defaultName = 'history:clear';
    public function __construct()
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Clear saved history')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        echo "Executing: " . HistoryClearCommand::$defaultName . PHP_EOL;
    }
}
