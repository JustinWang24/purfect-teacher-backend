<?php


namespace App\Utils\Misc;

use App\Utils\Misc\Impl\JPushLogic;

class JPushFactory
{

    /**
     * @return JPushLogic
     */
    public static function GetInstance(){
        $instance = null;

        $provider = env('PUSH_PROVIDER','J_PUSH');

        if ($provider == 'J_PUSH') {
            $instance =  new JPushLogic;
        }

        return $instance;
    }


}
