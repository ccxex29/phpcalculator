<?php

namespace Jakmall\Recruitment\Calculator\Http\Controller;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Jakmall\Recruitment\Calculator\Calculate\AddCalculation;
use Jakmall\Recruitment\Calculator\Calculate\DivideCalculation;
use Jakmall\Recruitment\Calculator\Calculate\MultiplyCalculation;
use Jakmall\Recruitment\Calculator\Calculate\PowCalculation;
use Jakmall\Recruitment\Calculator\Calculate\SubtractCalculation;
use Jakmall\Recruitment\Calculator\History\History;

class CalculatorController
{
    public function calculate(Request $request)
    {
        $reqCalc = str_replace('calculator/', '', $request->path());
        $reqNumbers = $request->json()->get('input');
        $calcObj = $this->switcher($reqCalc, $reqNumbers);
        $calcObj->logToDatabase($calcObj->getName(), $calcObj->getDescription(), $calcObj->getResult(), $reqNumbers);
        return new JsonResponse([
            'command' => $calcObj->getName(),
            'operation' => $calcObj->getDescription(),
            'result' => $calcObj->getResult()
            ]);
    }

    protected function switcher($name, $numbers)
    {
        switch ($name) {
            case 'add': {
                return new AddCalculation(new History(), $name, $numbers);
            }
            case 'subtract': {
                return new SubtractCalculation(new History(), $name, $numbers);
            }
            case 'multiply': {
                return new MultiplyCalculation(new History(), $name, $numbers);
            }
            case 'divide': {
                return new DivideCalculation(new History(), $name, $numbers);
            }
            case 'pow': {
                return new PowCalculation(new History(), $name, $numbers);
            }
        }
    }
}
