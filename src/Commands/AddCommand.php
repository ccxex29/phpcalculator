<?php

namespace Jakmall\Recruitment\Calculator\Commands;

use Jakmall\Recruitment\Calculator\Calculate\AddCalculation;
use Jakmall\Recruitment\Calculator\Calculate\Calculate;

class AddCommand extends CalculateCommand implements ManyArgsCalculation
{
    /**
     * @var string
     */
    protected $commandName = 'add';

    /**
     * @var string
     */
    protected $commandDescription = 'Add all given Numbers';

    public function __construct()
    {
        parent::__construct();
    }

    public function getArgumentNumberDescription(): string
    {
        return 'The numbers to be added';
    }

    protected function getCommandVerb(): string
    {
        return 'add';
    }

    protected function getCommandPassiveVerb(): string
    {
        return 'added';
    }

    protected function calculateTask($name, $numbers): Calculate
    {
        return new AddCalculation($this->logger, $name, $numbers);
    }
}
