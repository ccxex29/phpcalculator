<?php

namespace Jakmall\Recruitment\Calculator\History;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class HistoryCommand extends Command
{
    /**
     * Command definition (signature)
     *
     * @var string
     */
    protected $signature;

    /**
     * Command description
     *
     * @var string
     */
    protected $description;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute history command block
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->handle();
    }

    /**
     * Return array of string of calculation function names to be used for filtering the history
     *
     * @return string[]
     */
    protected function calculateList(): array
    {
        return ['add', 'subtract', 'multiply', 'divide', 'pow'];
    }

    /**
     * Handle children specific functionalities
     *
     * @return void
     */
    abstract protected function handle(): void;
}
