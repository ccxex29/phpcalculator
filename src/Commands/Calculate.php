<?php

namespace Jakmall\Recruitment\Calculator\Commands;

use Illuminate\Console\Command;
use Jakmall\Recruitment\Calculator\History\Infrastructure\CommandHistoryManagerInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Jakmall\Recruitment\Calculator\History\History;
use DateTime;

abstract class Calculate extends Command
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
        $description = $this->generateCalculationDescription($numbers);
        $result = $this->calculateAll($numbers);
        $timestamp = new DateTime();

        $this->logToDatabase($this->getCommandName(), $description, $result, $timestamp->getTimestamp());

        $this->comment(sprintf('%s = %s', $description, $result));
    }

    protected function logToDatabase($name, $description, $result, $timestamp): void
    {
        if (! $this->logger->log(
            (object) array(
                'name' => $name,
                'description' => $description,
                'result' => $result,
                'timestamp' => $timestamp,
            )
        )) {
            $this->error('Error while logging data!');
        }
    }

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
     * Generate the pre- calculation result output
     *
     * @param array $numbers
     *
     * @return string
     */
    protected function generateCalculationDescription(array $numbers): string
    {
        $operator = $this->getOperator();
        $glue = sprintf(' %s ', $operator);

        return implode($glue, $numbers);
    }

    /**
     * Handle the calculation recursion logic
     *
     * @param array $numbers
     *
     * @return float|int
     */
    protected function calculateAll(array $numbers)
    {
        $number = array_pop($numbers);

        if (count($numbers) <= 0) {
            return $number;
        }

        return $this->calculate($this->calculateAll($numbers), $number);
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

    /**
     * Return the operator visual
     *
     * @return string
     */
    abstract protected function getOperator(): string;

    /**
     * Single calculation of two numbers
     *
     * @param int|float $number1
     * @param int|float $number2
     *
     * @return int|float
     */
    abstract protected function calculate($number1, $number2);
}
