<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 17/10/19
 * Time: 2:14 PM
 */

namespace App\BusinessLogic\HomePage;
use App\BusinessLogic\HomePage\Contracts\IHomePageLogic;
use App\BusinessLogic\HomePage\Impl\SuHomePageLogic;
use App\User;

class Factory
{
    public static function GetLogic(User $user){
        /**
         * @var IHomePageLogic $instance
         */
        $instance = null;

        if($user->isSuperAdmin()){
            $instance = new SuHomePageLogic($user);
        }

        return $instance;
    }
}