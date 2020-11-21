<?php

namespace Jakmall\Recruitment\Calculator\Commands;

use Jakmall\Recruitment\Calculator\Calculate\Calculate;
use Jakmall\Recruitment\Calculator\Calculate\SubtractCalculation;

class SubtractCommand extends CalculateCommand implements ManyArgsCalculation
{
    /**
     * @var string
     */
    protected $commandName = 'subtract';

    /**
     * @var string
     */
    protected $commandDescription = 'Subtract all given Numbers';

    public function __construct()
    {
        parent::__construct();
    }

    public function getArgumentNumberDescription(): string
    {
        return 'The numbers to be subtracted';
    }

    protected function getCommandVerb(): string
    {
        return 'subtract';
    }

    protected function getCommandPassiveVerb(): string
    {
        return 'subtracted';
    }

    protected function calculateTask($name, $numbers): Calculate
    {
        return new SubtractCalculation($this->logger, $name, $numbers);
    }
}
