<?php

namespace Jakmall\Recruitment\Calculator\Http\Controller;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Jakmall\Recruitment\Calculator\History\History;

class HistoryController
{
    public function index(Request $request)
    {
        $driverQuery = $request->query('driver', 'database');
        $history = new History($driverQuery);
//        print_r($history->findAll());
        return new JsonResponse($this->manipulateJson($history->findAll()));
    }

    public function show()
    {
        dd('create show history by id here');
    }

    public function remove()
    {
        // todo: modify codes to remove history
        dd('create remove history logic here');
    }

    protected function manipulateJson($history): array
    {
        $manip = [];
        foreach ($history as $his) {
            array_push($manip, (object) [
                'id' => 'id' . $his->id,
                'command' => $his->name,
                'operation' => $his->description,
                'input' => $his->input,
                'result' => $his->result,
                'time' => date('Y-m-d h:i:s', $his->timestamp)
            ]);
        }
        return $manip;
    }
}
