<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 7/12/19
 * Time: 2:00 PM
 */

namespace App\BusinessLogic\OrganizationTitleHelpers\Impl;


use App\BusinessLogic\OrganizationTitleHelpers\Contracts\TitleToUsers;
use App\Models\Users\GradeUser;
use App\User;

class DepartmentManager implements TitleToUsers
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUsers()
    {
        $users = [];
        /**
         * @var GradeUser $gradeUser
         */
        $gradeUser = $this->user->gradeUser;

        if (isset($gradeUser[0])){
            $gradeUser = $gradeUser[0];
        }
        if($departmentAdviser = $gradeUser->departmentAdviser){
            $users[] = $departmentAdviser->user;
        }
        return $users;
    }
}
