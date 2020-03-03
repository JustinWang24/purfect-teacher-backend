<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 6/12/19
 * Time: 4:17 PM
 */

namespace App\BusinessLogic\OrganizationTitleHelpers\Impl;
use App\BusinessLogic\OrganizationTitleHelpers\Contracts\TitleToUsers;
use App\User;

class ClassAdviser implements TitleToUsers
{
    private $student;

    public function __construct(User $student)
    {
        $this->student = $student;
    }

    public function getUsers()
    {
        $users = [];
        $adviser = $this->student->gradeUser->gradeManager->adviser;
        if($adviser){
            $users[] = $adviser;
        }
        return $users;
    }
}
