<?php

namespace Jakmall\Recruitment\Calculator\Commands;

use Symfony\Component\Console\Input\InputArgument;

class PowCommand extends Calculate implements TwoArgsCalculation
{
    /**
     * @var string
     */
    protected $commandName = 'pow';

    /**
     * @var string
     */
    protected $commandDescription = 'Exponent the given number';

    public function __construct()
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->ignoreValidationErrors();

        $this
            ->setName($this->getCommandName())
            ->addArgument('base', InputArgument::REQUIRED, $this->getArgumentBaseDescription())
            ->addArgument('exp', InputArgument::REQUIRED, $this->getArgumentModDescription())
        ;
        $this->description = $this->getCommandDescription();
    }

    public function getArgumentBaseDescription(): string
    {
        return 'The base number';
    }

    public function getArgumentModDescription(): string
    {
        return 'The exponent number';
    }

    protected function getInput(): array
    {
        return [$this->argument('base'), $this->argument('exp')];
    }

    protected function getCommandVerb(): string
    {
        return 'power of';
    }

    protected function getCommandPassiveVerb(): string
    {
        return 'power of';
    }

    protected function getOperator(): string
    {
        return '^';
    }

    /**
     * @param int|float $number1
     * @param int|float $number2
     *
     * @return int|float
     */
    protected function calculate($number1, $number2)
    {
        return pow($number1, $number2);
    }

}
