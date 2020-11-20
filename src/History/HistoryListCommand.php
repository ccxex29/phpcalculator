<?php

namespace Jakmall\Recruitment\Calculator\History;

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
}
