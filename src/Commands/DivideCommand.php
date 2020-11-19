<?php

namespace Jakmall\Recruitment\Calculator\Commands;

class DivideCommand extends Calculate implements ManyArgsCalculation
{
    /**
     * @var string
     */
    protected $commandName = 'divide';

    /**
     * @var string
     */
    protected $commandDescription = 'Divide all given Numbers';

    public function __construct()
    {
        parent::__construct();
    }

    public function getArgumentNumberDescription(): string
    {
        return 'The numbers to be divided';
    }

    protected function getCommandVerb(): string
    {
        return 'divide';
    }

    protected function getCommandPassiveVerb(): string
    {
        return 'divided';
    }

    protected function getOperator(): string
    {
        return '/';
    }

    /**
     * @param int|float $number1
     * @param int|float $number2
     *
     * @return int|float
     */
    protected function calculate($number1, $number2)
    {
        return $number1 / $number2;
    }

}
