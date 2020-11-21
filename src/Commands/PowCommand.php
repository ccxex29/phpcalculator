<?php

namespace Jakmall\Recruitment\Calculator\Commands;

use Jakmall\Recruitment\Calculator\Calculate\Calculate;
use Jakmall\Recruitment\Calculator\Calculate\PowCalculation;
use Symfony\Component\Console\Input\InputArgument;

class PowCommand extends CalculateCommand implements TwoArgsCalculation
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

    protected function commandDetailSet(): void
    {
        $this
            ->setName($this->getCommandName())
            ->addArgument('base', InputArgument::REQUIRED, $this->getArgumentBaseDescription())
            ->addArgument('exp', InputArgument::REQUIRED, $this->getArgumentModDescription())
        ;
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

    protected function calculateTask($name, $numbers): Calculate
    {
        return new PowCalculation($this->logger, $name, $numbers);
    }
}
