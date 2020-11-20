<?php

namespace Jakmall\Recruitment\Calculator\History;

use Jakmall\Recruitment\Calculator\Database\Database;

class HistoryListCommand extends HistoryCommand
{
    /**
     * @var string
     */
    protected $signature = 'history:list
                            {--D|driver=database : Driver for storage connection}
                            {commands?* : Filter the history by commands}';

    /**
     * @var string
     */
    protected $description = 'Show calculator history';

    protected function handle(): void
    {
        $driver = $this->option('driver');
        $filter = $this->argument('commands');
        if ($driver !== 'database' && $driver !== 'file') {
            $this->error('Error: Invalid driver option provided!');
            return;
        }
        $filter = $this->handleFilter($filter);
        $history = $this->showLog($driver, $filter);
        $this->printLog($history);
    }

    /**
     * Take arguments that are not redundant or garbage
     *
     * @param $filter
     *
     * @return array
     */
    protected function handleFilter($filter): array
    {
        $filterArray = [];
        foreach ($filter as $f) {
            if (in_array($f, $this->calculateList()) and !in_array($f, $filterArray)) {
                array_push($filterArray, $f);
            }
        }
        return $filterArray;
    }

    /**
     * Handle show log
     *
     * @param $driver
     * @param $filter
     *
     * @return array
     */
    protected function showLog($driver, $filter): array
    {
        $history = new History($driver, $filter);
        return $history->findAll();
    }

    /**
     * Print log to console
     *
     * @param $history
     *
     * @return void
     */
    protected function printLog($history): void
    {
        if (empty($history)) {
            $this->info('History is empty');
        } else {
            $i = 1;
            foreach ($history as $li) {
                echo $i . '. ' . $li->name . ' ' . $li->description . ' ' . $li->result . ' ' . date('Y-m-d h:i:s', $li->timestamp) . ' ' . PHP_EOL;
                $i++;
            }
        }
    }
}
