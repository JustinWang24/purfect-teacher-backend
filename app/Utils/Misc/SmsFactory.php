<?php

namespace App\Utils\Misc;

use App\Utils\Misc\Contracts\ISmsSender;
use App\Utils\Misc\Impl\YunLianSmsLogic;

class SmsFactory
{
    /**
     * @return ISmsSender
     */
    public static function GetInstance(){
        $instance = null;

        $provider = env('SMS_PROVIDER','YUN_LIAN');

        if ($provider == 'YUN_LIAN') {
            $instance =  new YunLianSmsLogic;
        }

        return $instance;
    }
}
