<?php


namespace Jakmall\Recruitment\Calculator\Commands;


interface TwoArgsCalculation
{
    /**
     * Get the first description for two argument calculations
     *
     * @return string
     */
    public function getArgumentBaseDescription(): string;

    /**
     * Get the first description for two argument calculations
     *
     * @return string
     */
    public function getArgumentModDescription(): string;
}
