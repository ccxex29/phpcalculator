<?php


namespace Jakmall\Recruitment\Calculator\Database;


interface DriverInterface
{
    /**
     * Insert into database
     *
     * @param $name string
     * @param $description string
     * @param string $input
     * @param $result
     * @param $timestamp int
     *
     * @return bool
     */
    public function pushRecord(string $name, string $description, string $input, $result, int $timestamp): bool;

    /**
     * Pop every element on the database
     *
     * @return bool
     */
    public function popAllRecord(): bool;

    /**
     * Return the corresponding row with provided id
     *
     * @param $id
     *
     * @return array
     */
    public function findId($id): array;

    /**
     * Drop the corresponding row with provided id
     *
     * @param $id
     *
     * @return bool
     */
    public function deleteId($id): bool;
}
