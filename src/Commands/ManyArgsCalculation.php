<?php

namespace Jakmall\Recruitment\Calculator\Commands;

interface ManyArgsCalculation
{
    /**
     * Get the description for unknown number of calculations
     *
     * @return string
     */
    public function getArgumentNumberDescription(): string;
}
