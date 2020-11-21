<?php

namespace Jakmall\Recruitment\Calculator\History;

use Jakmall\Recruitment\Calculator\Database\Database;
use LucidFrame\Console\ConsoleTable;

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
            $table = new ConsoleTable();
            $i = 1;
            $col = ['No', 'Command', 'Description', 'Result', 'Output', 'Time'];
            foreach ( $col as $header) {
                $table->addHeader($header);
            }
            foreach ($history as $li) {
                $table->addRow()
                    ->addColumn($i)
                    ->addColumn($li->name)
                    ->addColumn($li->description)
                    ->addColumn($li->result)
                    ->addColumn(sprintf('%s = %s', $li->description, $li->result))
                    ->addColumn(date('Y-m-d h:i:s', $li->timestamp));
                $i++;
            }
            $table->display();
        }
    }
}
