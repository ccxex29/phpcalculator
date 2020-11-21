<?php

namespace Jakmall\Recruitment\Calculator\Commands;

use Jakmall\Recruitment\Calculator\Calculate\DivideCalculation;
use Jakmall\Recruitment\Calculator\Calculate\Calculate;

class DivideCommand extends CalculateCommand implements ManyArgsCalculation
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

    protected function calculateTask($name, $numbers): Calculate
    {
        return new DivideCalculation($this->logger, $name, $numbers);
    }
}
