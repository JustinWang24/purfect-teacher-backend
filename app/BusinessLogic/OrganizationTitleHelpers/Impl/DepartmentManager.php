<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 7/12/19
 * Time: 2:00 PM
 */

namespace App\BusinessLogic\OrganizationTitleHelpers\Impl;


use App\BusinessLogic\OrganizationTitleHelpers\Contracts\TitleToUsers;
use App\User;

class DepartmentManager implements TitleToUsers
{
    private $student;

    public function __construct(User $student)
    {
        $this->student = $student;
    }

    public function getUsers()
    {
        $users = [];
        if($manager = $this->student->gradeUser->departmentAdviser){
            $users[] = $manager;
        }
        return $users;
    }
}