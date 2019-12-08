<?php
// 表示下一步要做点什么

namespace App\Utils\Misc\Contracts;

interface INextAction
{
    /**
     * @return string
     */
    public function getActionUrl();

    /**
     * @return array
     */
    public function getActionData();
}
