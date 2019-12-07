<?php
namespace App\BusinessLogic\Pipeline\CreateActionMessage\Contracts;

use App\Utils\Pipeline\IAction;

interface IActionMessageLogic
{
    public function actionAndMessage(IAction $action);
}
