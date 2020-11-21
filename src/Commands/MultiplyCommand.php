<?php

namespace Jakmall\Recruitment\Calculator\Commands;

use Jakmall\Recruitment\Calculator\Calculate\Calculate;
use Jakmall\Recruitment\Calculator\Calculate\MultiplyCalculation;

class MultiplyCommand extends CalculateCommand implements ManyArgsCalculation
{
    /**
     * @var string
     */
    protected $commandName = 'multiply';

    /**
     * @var string
     */
    protected $commandDescription = 'Multiply all given Numbers';

    public function __construct()
    {
        parent::__construct();
    }

    public function getArgumentNumberDescription(): string
    {
        return 'The numbers to be multiplied';
    }

    protected function getCommandVerb(): string
    {
        return 'multiply';
    }

    protected function getCommandPassiveVerb(): string
    {
        return 'multiplied';
    }

    protected function calculateTask($name, $numbers): Calculate
    {
        return new MultiplyCalculation($this->logger, $name, $numbers);
    }
}
