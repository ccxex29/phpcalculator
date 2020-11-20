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
            $this->initDatabase();

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
     * @param $table string
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
        if ($this->connectDatabase(DBDRV, DBNAME)) {
            return true;
        } else {
            $dbFile = new FileOperation(DBNAME);
            $dbFile->createFile('');
            try {
                $this->connectDatabase(DBDRV, DBNAME);
                return true;
            } catch (Throwable $e) {
                return false;
            }
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
                $tblCallback->string('name');
                $tblCallback->string('description');
                $tblCallback->biginteger('result');
                $tblCallback->string('timestamp');
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
            die('Error while fetching table' . PHP_EOL);
        }
    }

    public function pushRecord($name, $description, $result, $timestamp): bool
    {
        try {
            Database::table(DBTBL)->insert([
                'name' => $name,
                'description' => $description,
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
}
