<?php

namespace Jakmall\Recruitment\Calculator\History;


class HistoryClearCommand extends HistoryCommand
{
    /**
     * @var string
     */
    protected $signature = 'history:clear';

    /**
     * @var string
     */
    protected $description = 'Clear saved history';

    protected function handle(): void
    {
        $this->clearAll();
    }

    /**
     * Clear every record on the database
     *
     * @return void
     */
    protected function clearAll(): void
    {
        $history = new History();
        $history->clearAll();
    }
}
