<?php

namespace Jakmall\Recruitment\Calculator\Commands;

use \Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class Calculate extends Command
{
    /**
     * @var string
     */
    protected $commandName;

    /**
     * @var string
     */
    protected $commandDescription;

    public function __construct()
    {
        parent::__construct();

        /*
         * $commandVerb = $this->getCommandName();
         * $this->signature = sprintf(
         *     '%s {numbers* : The numbers to be %s}',
         *     $commandVerb,
         *     $this->getCommandPassiveVerb()
         * );
         * $this->description = sprintf("%s", $this->getCommandDescription());
         */
    }

    protected function configure(): void
    {
        $this->ignoreValidationErrors();
        $this
            ->setName($this->getCommandName())
            ->setDescription($this->getCommandDescription()) // Description seems to be broken atm
            ->setHelp($this->getCommandDescription())        // I use this as a replacement since the use of help is
                                                             // similar to description
            ->addArgument('numbers', InputArgument::IS_ARRAY, $this->getArgumentNumberDescription())
        ;
    }

    protected function handle(): void
    {
        $numbers = $this->getInput();
        $description = $this->generateCalculationDescription($numbers);
        $result = $this->calculateAll($numbers);

        $this->comment(sprintf('%s = %s', $description, $result));
    }

    protected function getCommandName(): string
    {
        return $this->commandName;
    }

    protected function getCommandDescription(): string
    {
        return $this->commandDescription;
    }

    protected function getInput(): array
    {
        return $this->argument('numbers');
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->handle();
    }

    protected function generateCalculationDescription(array $numbers): string
    {
        $operator = $this->getOperator();
        $glue = sprintf(' %s ', $operator);

        return implode($glue, $numbers);
    }

    /**
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

    abstract protected function getCommandVerb(): string;

    abstract protected function getCommandPassiveVerb(): string;

    abstract protected function getOperator(): string;

    /**
     * @param int|float $number1
     * @param int|float $number2
     *
     * @return int|float
     */
    abstract protected function calculate($number1, $number2);
}
