<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 4/12/19
 * Time: 8:43 PM
 */
namespace App\BusinessLogic\Pipeline\Flow;
use App\BusinessLogic\Pipeline\Flow\Contracts\IFlowLogic;
use App\BusinessLogic\Pipeline\Flow\Impl\TeacherFlowsLogic;
use App\User;

class FlowLogicFactory
{
    /**
     * @param User $user
     * @return IFlowLogic
     */
    public static function GetInstance(User $user){
        $instance = null;

        if($user->isTeacher()){
            $instance = new TeacherFlowsLogic($user);
        }

        return $instance;
    }
}