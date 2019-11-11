<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 11/11/19
 * Time: 7:41 PM
 */

namespace App\Utils\Misc;


use App\Utils\Misc\Contracts\ISmsSender;

class SmsFactory
{
    /**
     * @return ISmsSender
     */
    public static function GetInstance(){
        $instance = null;

        $provider = config('SMS_PROVIDER','YUN_LIAN');

        return $instance;
    }
}