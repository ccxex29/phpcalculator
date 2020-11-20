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
        if (!is_numeric($command->description)){
            return false;
        }
        return true;
    }

    public function clearAll(): bool
    {
        return false;
    }
}
