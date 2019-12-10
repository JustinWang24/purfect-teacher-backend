<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 5/12/19
 * Time: 9:33 AM
 */

namespace App\Utils\Pipeline;


use App\User;

class FlowEngineFactory
{
    public static function GetInstance(IFlow $flow, User $user){
        return new FlowEngine($flow, $user);
    }
}