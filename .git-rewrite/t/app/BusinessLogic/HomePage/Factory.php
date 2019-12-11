<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 17/10/19
 * Time: 2:14 PM
 */

namespace App\BusinessLogic\HomePage;
use App\BusinessLogic\HomePage\Contracts\IHomePageLogic;
use App\BusinessLogic\HomePage\Impl\OperatorHomePageLogic;
use App\BusinessLogic\HomePage\Impl\SchoolManagerHomePageLogic;
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
        }elseif($user->isOperatorOrAbove()){
            $instance = new OperatorHomePageLogic($user);
        }
        elseif ($user->isSchoolAdminOrAbove()){
            $instance = new SchoolManagerHomePageLogic($user);
        }

        return $instance;
    }
}
