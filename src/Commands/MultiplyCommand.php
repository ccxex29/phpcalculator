<?php

namespace Jakmall\Recruitment\Calculator\Commands;

class MultiplyCommand extends Calculate
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
        $commandVerb = $this->getCommandVerb();

        $this->signature = sprintf(
            '%s {numbers* : The numbers to be %s}',
            $commandVerb,
            $this->getCommandPassiveVerb()
        );
        $this->description = sprintf('%s all given Numbers', ucfirst($commandVerb));
    }

    protected function getArgumentNumberDescription(): string
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

    protected function getOperator(): string
    {
        return '*';
    }

    /**
     * @param int|float $number1
     * @param int|float $number2
     *
     * @return int|float
     */
    protected function calculate($number1, $number2)
    {
        return $number1 * $number2;
    }

}
