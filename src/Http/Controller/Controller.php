<?php


namespace Jakmall\Recruitment\Calculator\Http\Controller;


abstract class Controller
{
    /**
     * Get url without calculator/ prefix
     *
     * @param $r
     * @return string
     */
    protected function getAction($r): string
    {
        return str_replace('calculator/', '', $r);
    }

    protected function truncateIdPrefix($id): string
    {
        return str_replace('id', '', $id);
    }
}
