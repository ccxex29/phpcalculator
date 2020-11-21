<?php

namespace Jakmall\Recruitment\Calculator\Commands;

use Illuminate\Console\Command;
use Jakmall\Recruitment\Calculator\Calculate\Calculate;
use Jakmall\Recruitment\Calculator\History\Infrastructure\CommandHistoryManagerInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Jakmall\Recruitment\Calculator\History\History;

abstract class CalculateCommand extends Command
{
    /**
     * Command Name Signature
     *
     * @var string
     */
    protected $commandName;

    /**
     * Command Description
     *
     * @var string
     */
    protected $commandDescription;

    /**
     * Command logger object
     *
     * @var CommandHistoryManagerInterface
     */
    protected $logger;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Configure the calculation commands
     *
     * @return void
     */
    protected function configure(): void
    {
        $this->logger = new History();
        $this->ignoreValidationErrors();
        $this->description = $this->getCommandDescription();
        $this->commandDetailSet();
    }

    /**
     * Additional settings to define command
     *
     * @return void
     */
    protected function commandDetailSet(): void
    {
        $this
            ->setName($this->getCommandName())
            ->addArgument('numbers', InputArgument::IS_ARRAY, $this->getArgumentNumberDescription())
        ;
    }

    /**
     * Handle calculation from arguments
     *
     * @return void
     */
    protected function handle(): void
    {
        $numbers = $this->getInput();

        $calc = $this->calculateTask($this->getCommandName(), $numbers);
        if (!$calc->logToDatabase($calc->getName(), $calc->getDescription(), $calc->getResult(), $numbers)) {
                $this->error('Error while logging data!');
        }


        $this->comment(sprintf('%s = %s', $calc->getDescription(), $calc->getResult()));
    }

    /**
     * Do calculation task
     *
     * @param $name
     * @param $numbers
     *
     * @return Calculate
     */
    abstract protected function calculateTask($name, $numbers): Calculate;

    /**
     * Return command name signature
     *
     * @return string
     */
    protected function getCommandName(): string
    {
        return $this->commandName;
    }

    /**
     * Return command description
     *
     * @return string
     */
    protected function getCommandDescription(): string
    {
        return $this->commandDescription;
    }

    /**
     * Return an array of numbers with the name 'numbers'
     *
     * @return array
     */
    protected function getInput(): array
    {
        return $this->argument('numbers');
    }

    /**
     * Execute things to do upon command execution
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
     * Return the command verb
     *
     * @return string
     */
    abstract protected function getCommandVerb(): string;

    /**
     * Return the command passive verb
     *
     * @return string
     */
    abstract protected function getCommandPassiveVerb(): string;
}
