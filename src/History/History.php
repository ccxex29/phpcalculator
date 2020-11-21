<?php

namespace Jakmall\Recruitment\Calculator\History;

use Jakmall\Recruitment\Calculator\Database\Database;
use Jakmall\Recruitment\Calculator\Database\File;
use Jakmall\Recruitment\Calculator\History\Infrastructure\CommandHistoryManagerInterface;
use Throwable;

class History implements CommandHistoryManagerInterface
{
    /**
     * @var
     */
    protected $filter;

    /**
     * @var string
     */
    protected $driver;

    public function __construct($driver='database', $filter=null)
    {
        if ($filter != null) {
            $this->filter = $filter;
        } else {
            $this->filter = ['*'];
        }
        $this->driver = $driver;
    }

    public function findAll(): array
    {
        try {
            if ($this->driver === 'database') {
                $db = new Database();
                return $db->fetchTable($this->filter);
            }
            else {
                $file = new File();
                return $file->fetchFile($this->filter);
            }
        } catch (Throwable $e) {
        }
    }
    public function log($command): bool
    {
        if (!is_numeric($command->result)){
            return false;
        }

        try {
            try{
                $db = new Database();
                $db->pushRecord(
                    $command->name,
                    $command->description,
                    $command->result,
                    $command->timestamp
                );
                $file = new File();
                $file->pushRecord(
                    $command->name,
                    $command->description,
                    $command->result,
                    $command->timestamp
                );
            } catch (Throwable $e) {
                fwrite(STDERR, $e . PHP_EOL);
                return false;
            }
            return true;
        } catch (Throwable $e) {
            fwrite(STDERR, $e . PHP_EOL);
            return false;
        }
    }

    public function clearAll(): bool
    {
        try {
            $db = new Database();
            $db->popAllRecord();
            $file = new File();
            $file->popAllRecord();
            return true;
        } catch (Throwable $e) {
            fwrite(STDERR, $e . PHP_EOL);
            return false;
        }
    }
}
