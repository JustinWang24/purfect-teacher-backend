<?php
/**
 * 用户操作的步骤要求的选填项的数据
 */

namespace App\Utils\Pipeline;


interface IActionOption
{
    public function getAction(): IAction;

    public function getValue(): string;
}