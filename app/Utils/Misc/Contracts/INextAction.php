<?php
// 表示下一步要做点什么

namespace App\Utils\Misc\Contracts;


use App\User;

interface INextAction
{
    public function getActionUrl(User $user);
}
