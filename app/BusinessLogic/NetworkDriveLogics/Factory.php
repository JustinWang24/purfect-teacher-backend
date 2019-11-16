<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 15/11/19
 * Time: 8:55 PM
 */

namespace App\BusinessLogic\NetworkDriveLogics;


use App\BusinessLogic\NetworkDriveLogics\Contracts\ICategoryLogic;
use App\BusinessLogic\NetworkDriveLogics\Impl\ForSchoolManager;
use App\BusinessLogic\NetworkDriveLogics\Impl\ForStudent;
use App\BusinessLogic\NetworkDriveLogics\Impl\ForTeacher;
use App\User;

class Factory
{
    /**
     * @param User $user
     * @param string $categoryUuid
     * @return ICategoryLogic
     */
    public static function GetCategoryLogic(User $user, $categoryUuid){
        $logic = null;

        if($user->isStudent()){
            $logic = new ForStudent($categoryUuid, $user);
        }
        elseif ($user->isTeacher()){
            $logic = new ForTeacher($categoryUuid, $user);
        }
        elseif ($user->isSchoolManager()){
            $logic = new ForSchoolManager($categoryUuid, $user);
        }

        return $logic;
    }
}