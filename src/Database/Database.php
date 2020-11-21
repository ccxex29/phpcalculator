<?php

namespace Jakmall\Recruitment\Calculator\Database;

use Illuminate\Database\Capsule\Manager;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
use Throwable;

include('config/db_config.php');

class Database extends Manager implements DriverInterface
{
    public function __construct()
    {
        parent::__construct();
        try {
            if(!$this->initDatabase()) {
                die('Error unable to initialise database!' . PHP_EOL);
            }

            $this->setEventDispatcher(new Dispatcher(new Container()));
            $this->setAsGlobal();

            $this->initTable();
        } catch (Throwable $e) {
            fwrite(STDERR, $e . PHP_EOL);
            die('Error connecting to database!' . PHP_EOL);
        }
    }

    /**
     * Connect to database
     *
     * @param $driver string
     * @param string $database
     *
     * @return bool
     */
    protected function connectDatabase(string $driver, string $database): bool
    {
        try{
            $this->addConnection([
                'driver'   => $driver,
                'database' => $database,
                'charset'  => 'utf8',
                'collation'=> 'utf8_unicode_ci',
            ]);
            return true;
        } catch (Throwable $e) {
            return false;
        }
    }

    /**
     * Handle database initialisation gracefully
     * even if the database does not exist
     *
     * @return bool
     */
    protected function initDatabase(): bool
    {
        if (!file_exists(DBNAME)) {
            $dbFile = new FileOperation(DBNAME);
            $dbFile->createFile('');
        }
        try {
            $this->connectDatabase(DBDRV, DBNAME);
            return true;
        } catch (Throwable $e) {
            return false;
        }
    }

    /**
     * Create database table
     *
     * @return bool
     */
    protected function createTable(): bool
    {
        try {
            $this->schema()->create(DBTBL, function ($tblCallback) {
                $tblCallback->integer('id');
                $tblCallback->string('name');
                $tblCallback->string('description');
                $tblCallback->string('input');
                $tblCallback->biginteger('result');
                $tblCallback->string('timestamp');
                $tblCallback->primary(['id']);
            });
            return true;
        } catch (Throwable $e) {
            return false;
        }
    }

    /**
     * Handle table initialisation gracefully
     * even if the table does not exist
     *
     * @return bool
     */
    protected function initTable(): bool
    {
        if ($this->createTable()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get data from table
     *
     * @param array
     *
     * @return array
     */
    public function fetchTable($filter): array
    {
        $any = false;
        $getElem = '';
        if ($filter === ['*']) {
            $any = true;
        } else {
            $getElem = '(\'' . implode('\', \'', $filter) . '\')';
        }

        try{
            if ($any) {
                return Database::table(DBTBL)->get()->toArray();
            } else {
                return Database::table(DBTBL)->whereRaw('name IN ' . $getElem)->get()->toArray();
            }
        } catch (Throwable $e) {
            echo $e;
            die('Error while fetching table' . PHP_EOL);
        }
    }

    public function getLastId(): int
    {
        return Database::table(DBTBL)->orderByDesc('id')->get()->toArray()[0]->id;
    }

    public function pushRecord(string $name, string $description, string $input, $result, int $timestamp): bool
    {
        try {
            Database::table(DBTBL)->insert([
                'name' => $name,
                'description' => $description,
                'input' => $input,
                'result' => $result,
                'timestamp' => $timestamp
            ]);
            return true;
        } catch (Throwable $e) {
            fwrite(STDERR, $e . PHP_EOL);
            return false;
        }
    }

    public function popAllRecord(): bool
    {
        try {
            Database::table(DBTBL)->delete();
            return true;
        } catch (Throwable $e) {
            fwrite(STDERR, $e . PHP_EOL);
            return false;
        }
    }

    public function findId($id): array
    {
        try {
            return Database::table(DBTBL)->where('id', '=', (string)$id)->get()->toArray();
        } catch (Throwable $e) {
            return [];
        }
    }

    public function deleteId($id): bool
    {
        try {
            Database::table(DBTBL)->delete($id);
            return true;
        } catch (Throwable $e) {
            return false;
        }
    }
}
