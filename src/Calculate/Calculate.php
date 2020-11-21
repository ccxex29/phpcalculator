<?php

namespace Jakmall\Recruitment\Calculator\Calculate;

use DateTime;
use Jakmall\Recruitment\Calculator\History\Infrastructure\CommandHistoryManagerInterface;

abstract class Calculate
{
    /**
     *
     * @var CommandHistoryManagerInterface
     */
    protected $logger;

    /**
     * Calculation Result
     *
     * @var
     */
    protected $result;

    /**
     * Calculation description
     *
     * @var string
     */
    protected $description;

    /**
     * Calculation name
     *
     * @var string
     */
    protected $name;

    /**
     * Calculate constructor.
     * @param $logger
     * @param $name
     * @param $numbers
     */
    public function __construct($logger, $name, $numbers)
    {
        $this->logger = $logger;

        $this->name = $name;
        $this->description = $this->generateCalculationDescription($numbers);

        $this->result = $this->calculateAll($numbers);
    }

    /**
     * Result Getter
     *
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Description Getter
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Name Getter
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Send data to logger
     *
     * @param $name
     * @param $description
     * @param $result
     *
     * @param $numbers
     * @return bool
     */
    public function logToDatabase($name, $description, $result, $numbers): bool
    {
        $timestamp = new DateTime();
        $timestamp = $timestamp->getTimestamp();
        return $this->logger->log(
            (object) array(
                'name' => $name,
                'description' => $description,
                'input' => $numbers,
                'result' => $result,
                'timestamp' => $timestamp,
            )
        );
    }

    /**
     * Generate the pre- calculation result output
     *
     * @param array $numbers
     *
     * @return string
     */
    protected function generateCalculationDescription(array $numbers): string
    {
        $operator = $this->getOperator();
        $glue = sprintf(' %s ', $operator);

        return implode($glue, $numbers);
    }

    /**
     * Handle the calculation recursion logic
     *
     * @param array $numbers
     *
     * @return float|int
     */
    protected function calculateAll(array $numbers)
    {
        $number = array_pop($numbers);

        if (count($numbers) <= 0) {
            return $number;
        }

        return $this->calculate($this->calculateAll($numbers), $number);
    }


    /**
     * Single calculation of two numbers
     *
     * @param int|float $number1
     * @param int|float $number2
     *
     * @return int|float
     */
    abstract protected function calculate($number1, $number2);

    /**
     * Return the operator visual
     *
     * @return string
     */
    abstract protected function getOperator(): string;
}
