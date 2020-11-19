<?php

namespace Jakmall\Recruitment\Calculator\History;

use Jakmall\Recruitment\Calculator\History\Infrastructure\CommandHistoryManagerInterface;

class History implements CommandHistoryManagerInterface
{
    public function findAll(): array
    {
        return [];
    }
    public function log($command): bool
    {
        return false;
    }

    public function clearAll(): bool
    {
        return false;
    }
}
