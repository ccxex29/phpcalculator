<?php


namespace Jakmall\Recruitment\Calculator\Commands;


interface TwoArgsCalculation
{
    function getArgumentBaseDescription(): string;
    function getArgumentModDescription(): string;
}
