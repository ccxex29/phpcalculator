<?php


namespace Jakmall\Recruitment\Calculator\Database;


interface DriverInterface
{
    /**
     * Insert into database
     *
     * @param $name string
     * @param $description string
     * @param $result int
     * @param $timestamp int
     *
     * @return bool
     */
    public function pushRecord($name, $description, $result, $timestamp): bool;

    /**
     * Pop every element on the database
     *
     * @return bool
     */
    public function popAllRecord(): bool;
}
