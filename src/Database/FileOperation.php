<?php

namespace Jakmall\Recruitment\Calculator\Database;

use Throwable;

class FileOperation
{
    /**
     * File name with its directory information (full path)
     *
     * @var string
     */
    protected $fileName;

    public function __construct($fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * Create a file with the provided content
     *
     * @param $content string
     *
     * @param string $mode
     * @return void
     */
    public function createFile(string $content, string $mode='w'): void
    {
        $f = fopen($this->fileName, $mode);
        fwrite($f, $content);
        fclose($f);
    }

    /**
     * @param string $mode
     * @param int $len
     *
     * @return void | array
     */
    public function readFile(string $mode='r', int $len=1000)
    {
        if(($f = fopen($this->fileName, $mode)) === false) {
            return;
        }

        $data = [];
        while (($d = fread($f, $len)) !== false) {
            array_push($data, $d);
        }
        fclose($f);
    }

    /**
     * @param array $content
     */
    public function createCsv(array $content): void
    {
        $f = fopen($this->fileName, 'w');
        foreach ($content as $c) {
            fputcsv($f, $c, ',');
        }
    }

    /**
     * @param array $content
     * @param array $header
     */
    public function appendCsv(array $content, array $header): void
    {
        if (!file_exists($this->fileName)) {
            $this->createCsv([$header]);
        }
        $prev = $this->readCsv(['*'], true);
        $allRows = [];

        array_push($allRows, $header);
        foreach ($prev as $p) {
            array_push($allRows, $p);
        }
        array_push($allRows, $content);

        $this->createCsv($allRows);
    }

    /**
     * Remove csv row based on id
     *
     * @param $id
     *
     * @param array $header
     * @return bool
     */
    public function dropRowCsv($id, array $header): bool
    {
        try {
            $prev = $this->cherrypickReadCsv($id);
            $allRows = [];

            array_push($allRows, $header);
            foreach ($prev as $p) {
                array_push($allRows, $p);
            }

            $this->createCsv($allRows);
            return true;
        } catch (Throwable $e) {
            return false;
        }
    }

    protected function cherrypickReadCsv($id, $len=1000)
    {
        $f = $this->getFile();

        $data = [];
        $i = 1;
        while (($d = fgetcsv($f, $len, ',')) !== false) {
            if ($i > 1 && !empty($d) && $d[0] != $id) {
                array_push($data, $d);
            }
            $i++;
        }
        fclose($f);
        return $data;
    }

    /**
     * Empty CSV
     */
    public function emptyCsv(): void
    {
        $this->createCsv([['']]);
    }

    /**
     * @param $filter
     * @param $any
     * @param int $len
     *
     * @return void | array
     */
    public function readCsv($filter, $any, $len=1000)
    {
        $f = $this->getFile();

        $data = [];
        $i = 1;
        while (($d = fgetcsv($f, $len, ',')) !== false) {
            if ($i > 1 && !empty($d)) {
                if (($any) || (in_array($d[1], $filter))) {
                    array_push($data, $d);
                }
            }
            $i++;
        }
        fclose($f);
        return $data;
    }

    /**
     * @return resource|void
     */
    protected function getFile()
    {
        if(($f = fopen($this->fileName, 'r')) === false) {
            return;
        }
        return $f;
    }
}
