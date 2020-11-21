<?php

namespace Jakmall\Recruitment\Calculator\Database;

use Throwable;

include('config/file_drv_config.php');

class File implements DriverInterface
{
    private $csvHeader = ['id', 'name', 'description', 'input', 'result', 'timestamp'];

    /**
     * Get data from table
     *
     * @param array
     *
     * @return array
     */
    public function fetchFile($filter): array
    {
        $any = false;
        if ($filter === ['*']) {
            $any = true;
        }
        $file = new FileOperation(FILENAME);
        $array = $file->readCsv($filter, $any);
        $array = $this->convertIntoObjectArray($array, $this->csvHeader);
        return $array;
    }

    /**
     * Convert regular array from csv into keyed object array
     *
     * @param $array
     * @param $header
     *
     * @return array
     */
    protected function convertIntoObjectArray($array, $header): array
    {
        $objArray = [];
        foreach ($array as $l) {
            array_push(
                $objArray,
                (object)[
                    'id' => $l[0],
                    'name' => $l[1],
                    'description' => $l[2],
                    'input' => $l[3],
                    'result' => $l[4],
                    'timestamp' => $l[5]
                ]
            );
        }
        return $objArray;
    }

    public function pushRecord(string $name, string $description, string $input, $result, int $timestamp): bool
    {
        try{
            $file = new FileOperation(FILENAME);
            $id = new Database();
            $id = $id->getLastId();

            $file->appendCsv([$id, $name, $description, $input, $result, $timestamp], $this->csvHeader);
            return true;
        } catch (Throwable $e) {
            fwrite(STDERR, $e . PHP_EOL);
            return false;
        }
    }

    public function popAllRecord(): bool
    {
        try {
            $file = new FileOperation(FILENAME);
            $file->emptyCsv();
            return true;
        } catch (Throwable $e) {
            fwrite(STDERR, $e . PHP_EOL);
            return false;
        }
    }

    public function findId($id): array
    {
        try {
            $file = $this->fetchFile(['*']);
            $arr = [];
            foreach ($file as $f) {
                if ($f->id === $id) {
                    array_push($arr, $f);
                }
            }
            return $arr;
        } catch (Throwable $e) {
            fwrite(STDERR, $e . PHP_EOL);
            return [];
        }
    }

    public function deleteId($id): bool
    {
        $file = new FileOperation(DBNAME);
        if (!$file->dropRowCsv($id, $this->csvHeader)) {
            return true;
        }
        return false;
    }
}
